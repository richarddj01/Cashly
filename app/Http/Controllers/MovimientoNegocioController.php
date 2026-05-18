<?php

namespace App\Http\Controllers;

use App\Models\MovimientoNegocio;
use App\Models\Cuenta;
use App\Models\Categoria;
use App\Models\Empleado;
use Illuminate\Http\Request;

class MovimientoNegocioController extends Controller
{
    public function index(Request $request)
    {
        $query = MovimientoNegocio::where('user_id', auth()->id())
            ->with('cuenta', 'categoria', 'empleado')
            ->orderByDesc('fecha');

        // Filtros
        if ($request->filled('area')) {
            $query->where('area', $request->area);
        }
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        if ($request->filled('mes')) {
            $query->whereMonth('fecha', $request->mes);
        }
        if ($request->filled('anio')) {
            $query->whereYear('fecha', $request->anio);
        }

        $movimientos = $query->paginate(20);

        // Totales del filtro actual
        $totalEntradas = $query->where('direccion', 'entrada')->sum('monto');
        $totalSalidas  = $query->where('direccion', 'salida')->sum('monto');

        return view('movimientos-negocio.index', compact(
            'movimientos', 'totalEntradas', 'totalSalidas'
        ));
    }

    public function create()
    {
        $cuentas    = Cuenta::where('user_id', auth()->id())->where('activa', true)->where('contexto', 'negocio')->get();
        $categorias = Categoria::where('user_id', auth()->id())->whereIn('contexto', ['negocio', 'ambos'])->get();
        $empleados  = Empleado::where('user_id', auth()->id())->where('activo', true)->get();

        return view('movimientos-negocio.create', compact('cuentas', 'categorias', 'empleados'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cuenta_id'  => 'required|exists:cuentas,id',
            'area'       => 'required|in:papeleria,impresiones,compartido',
            'tipo'       => 'required',
            'direccion'  => 'required|in:entrada,salida',
            'monto'      => 'required|numeric|min:0.01',
            'fecha'      => 'required|date',
            'estado'     => 'required|in:pendiente,pagado,cancelado',
            'fecha_vencimiento' => 'nullable|date',
            'categoria_id'      => 'nullable|exists:categorias,id',
            'empleado_id'       => 'nullable|exists:empleados,id',
        ]);

        MovimientoNegocio::create([
            'user_id'           => auth()->id(),
            'cuenta_id'         => $request->cuenta_id,
            'categoria_id'      => $request->categoria_id,
            'empleado_id'       => $request->empleado_id,
            'area'              => $request->area,
            'tipo'              => $request->tipo,
            'direccion'         => $request->direccion,
            'monto'             => $request->monto,
            'descripcion'       => $request->descripcion,
            'fecha'             => $request->fecha,
            'estado'            => $request->estado,
            'fecha_vencimiento' => $request->fecha_vencimiento,
            'beneficiario'      => $request->beneficiario,
            'notas'             => $request->notas,
        ]);

        return redirect()->route('movimientos-negocio.index')
            ->with('success', 'Movimiento registrado correctamente.');
    }

    public function edit(MovimientoNegocio $movimientosNegocio)
    {
        abort_if($movimientosNegocio->user_id !== auth()->id(), 403);

        $cuentas    = Cuenta::where('user_id', auth()->id())->where('activa', true)->get();
        $categorias = Categoria::where('user_id', auth()->id())->whereIn('contexto', ['negocio', 'ambos'])->get();
        $empleados  = Empleado::where('user_id', auth()->id())->where('activo', true)->get();

        return view('movimientos-negocio.edit', compact('movimientosNegocio', 'cuentas', 'categorias', 'empleados'));
    }

    public function update(Request $request, MovimientoNegocio $movimientosNegocio)
    {
        abort_if($movimientosNegocio->user_id !== auth()->id(), 403);

        $request->validate([
            'cuenta_id'  => 'required|exists:cuentas,id',
            'area'       => 'required|in:papeleria,impresiones,compartido',
            'tipo'       => 'required',
            'direccion'  => 'required|in:entrada,salida',
            'monto'      => 'required|numeric|min:0.01',
            'fecha'      => 'required|date',
            'estado'     => 'required|in:pendiente,pagado,cancelado',
        ]);

        $movimientosNegocio->update($request->all());

        return redirect()->route('movimientos-negocio.index')
            ->with('success', 'Movimiento actualizado correctamente.');
    }

    public function destroy(MovimientoNegocio $movimientosNegocio)
    {
        abort_if($movimientosNegocio->user_id !== auth()->id(), 403);
        $movimientosNegocio->delete();

        return redirect()->route('movimientos-negocio.index')
            ->with('success', 'Movimiento eliminado correctamente.');
    }
}
