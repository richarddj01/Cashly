<?php

namespace App\Http\Controllers;

use App\Models\MovimientoPersonal;
use App\Models\Cuenta;
use App\Models\Categoria;
use Illuminate\Http\Request;

class MovimientoPersonalController extends Controller
{
    public function index(Request $request)
    {
        $query = MovimientoPersonal::where('user_id', auth()->id())
            ->with('cuenta', 'categoria')
            ->orderByDesc('fecha');

        if ($request->filled('direccion')) {
            $query->where('direccion', $request->direccion);
        }
        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
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

        $totalEntradas = (clone $query)->where('direccion', 'entrada')->sum('monto');
        $totalSalidas  = (clone $query)->where('direccion', 'salida')->sum('monto');

        $categorias = Categoria::where('user_id', auth()->id())
            ->whereIn('contexto', ['personal', 'ambos'])
            ->orderBy('nombre')
            ->get();

        return view('movimientos-personales.index', compact(
            'movimientos', 'totalEntradas', 'totalSalidas', 'categorias'
        ));
    }

    public function create()
    {
        $cuentas = Cuenta::where('user_id', auth()->id())
            ->where('activa', true)
            ->where('contexto', 'personal')
            ->get();

        $categorias = Categoria::where('user_id', auth()->id())
            ->whereIn('contexto', ['personal', 'ambos'])
            ->orderBy('tipo')
            ->orderBy('nombre')
            ->get();

        return view('movimientos-personales.create', compact('cuentas', 'categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cuenta_id'  => 'required|exists:cuentas,id',
            'tipo'       => 'required',
            'direccion'  => 'required|in:entrada,salida',
            'monto'      => 'required|numeric|min:0.01',
            'fecha'      => 'required|date',
            'estado'     => 'required|in:pendiente,pagado,cancelado',
            'categoria_id' => 'nullable|exists:categorias,id',
        ]);

        MovimientoPersonal::create([
            'user_id'     => auth()->id(),
            'cuenta_id'   => $request->cuenta_id,
            'categoria_id'=> $request->categoria_id,
            'tipo'        => $request->tipo,
            'direccion'   => $request->direccion,
            'monto'       => $request->monto,
            'descripcion' => $request->descripcion,
            'fecha'       => $request->fecha,
            'estado'      => $request->estado,
            'notas'       => $request->notas,
        ]);

        return redirect()->route('movimientos-personales.index')
            ->with('success', 'Movimiento registrado correctamente.');
    }

    public function edit(MovimientoPersonal $movimientosPersonale)
    {
        abort_if($movimientosPersonale->user_id !== auth()->id(), 403);

        $cuentas = Cuenta::where('user_id', auth()->id())
            ->where('activa', true)->get();

        $categorias = Categoria::where('user_id', auth()->id())
            ->whereIn('contexto', ['personal', 'ambos'])
            ->orderBy('nombre')->get();

        return view('movimientos-personales.edit', compact(
            'movimientosPersonale', 'cuentas', 'categorias'
        ));
    }

    public function update(Request $request, MovimientoPersonal $movimientosPersonale)
    {
        abort_if($movimientosPersonale->user_id !== auth()->id(), 403);

        $request->validate([
            'cuenta_id'  => 'required|exists:cuentas,id',
            'tipo'       => 'required',
            'direccion'  => 'required|in:entrada,salida',
            'monto'      => 'required|numeric|min:0.01',
            'fecha'      => 'required|date',
            'estado'     => 'required|in:pendiente,pagado,cancelado',
        ]);

        $movimientosPersonale->update($request->all());

        return redirect()->route('movimientos-personales.index')
            ->with('success', 'Movimiento actualizado correctamente.');
    }

    public function destroy(MovimientoPersonal $movimientosPersonale)
    {
        abort_if($movimientosPersonale->user_id !== auth()->id(), 403);
        $movimientosPersonale->delete();

        return redirect()->route('movimientos-personales.index')
            ->with('success', 'Movimiento eliminado correctamente.');
    }
}
