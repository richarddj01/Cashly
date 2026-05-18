<?php

namespace App\Http\Controllers;

use App\Models\Prestamo;
use App\Models\Cuenta;
use App\Models\MovimientoPersonal;
use App\Models\MovimientoNegocio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrestamoController extends Controller
{
    public function index()
    {
        $prestamos = Prestamo::where('user_id', auth()->id())
            ->orderByDesc('fecha')
            ->paginate(20);

        $pendientePersonalANegocio = Prestamo::where('user_id', auth()->id())
            ->where('direccion', 'personal_a_negocio')
            ->where('estado', 'pendiente')
            ->sum('monto');

        $pendienteNegocioAPersonal = Prestamo::where('user_id', auth()->id())
            ->where('direccion', 'negocio_a_personal')
            ->where('estado', 'pendiente')
            ->sum('monto');

        return view('prestamos.index', compact(
            'prestamos', 'pendientePersonalANegocio', 'pendienteNegocioAPersonal'
        ));
    }

    public function create()
    {
        $cuentasPersonales = Cuenta::where('user_id', auth()->id())
            ->where('activa', true)->where('contexto', 'personal')->get();
        $cuentasNegocio = Cuenta::where('user_id', auth()->id())
            ->where('activa', true)->where('contexto', 'negocio')->get();

        return view('prestamos.create', compact('cuentasPersonales', 'cuentasNegocio'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'direccion'          => 'required|in:personal_a_negocio,negocio_a_personal',
            'monto'              => 'required|numeric|min:0.01',
            'fecha'              => 'required|date',
            'cuenta_personal_id' => 'required|exists:cuentas,id',
            'cuenta_negocio_id'  => 'required|exists:cuentas,id',
            'motivo'             => 'nullable|string|max:255',
            'fecha_devolucion'   => 'nullable|date',
        ]);

        DB::transaction(function () use ($request) {

            // Movimiento en finanzas personales
            $movPersonal = MovimientoPersonal::create([
                'user_id'     => auth()->id(),
                'cuenta_id'   => $request->cuenta_personal_id,
                'tipo'        => 'prestamo_negocio',
                'direccion'   => $request->direccion === 'personal_a_negocio' ? 'salida' : 'entrada',
                'monto'       => $request->monto,
                'descripcion' => $request->motivo ?? 'Préstamo al negocio',
                'fecha'       => $request->fecha,
                'estado'      => 'pagado',
            ]);

            // Movimiento en negocio
            $movNegocio = MovimientoNegocio::create([
                'user_id'     => auth()->id(),
                'cuenta_id'   => $request->cuenta_negocio_id,
                'area'        => 'compartido',
                'tipo'        => 'prestamo_personal',
                'direccion'   => $request->direccion === 'personal_a_negocio' ? 'entrada' : 'salida',
                'monto'       => $request->monto,
                'descripcion' => $request->motivo ?? 'Préstamo personal',
                'fecha'       => $request->fecha,
                'estado'      => 'pagado',
            ]);

            // Registro del préstamo
            Prestamo::create([
                'user_id'                => auth()->id(),
                'direccion'              => $request->direccion,
                'monto'                  => $request->monto,
                'motivo'                 => $request->motivo,
                'fecha'                  => $request->fecha,
                'fecha_devolucion'       => $request->fecha_devolucion,
                'estado'                 => 'pendiente',
                'movimiento_personal_id' => $movPersonal->id,
                'movimiento_negocio_id'  => $movNegocio->id,
                'notas'                  => $request->notas,
            ]);
        });

        return redirect()->route('prestamos.index')
            ->with('success', 'Préstamo registrado correctamente.');
    }

    public function update(Request $request, Prestamo $prestamo)
    {
        abort_if($prestamo->user_id !== auth()->id(), 403);

        $request->validate([
            'estado' => 'required|in:pendiente,devuelto,cancelado',
        ]);

        $prestamo->update(['estado' => $request->estado]);

        return redirect()->route('prestamos.index')
            ->with('success', 'Estado del préstamo actualizado.');
    }

    public function destroy(Prestamo $prestamo)
    {
        abort_if($prestamo->user_id !== auth()->id(), 403);
        $prestamo->delete();

        return redirect()->route('prestamos.index')
            ->with('success', 'Préstamo eliminado.');
    }
}
