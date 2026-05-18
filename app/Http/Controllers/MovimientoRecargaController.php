<?php

namespace App\Http\Controllers;

use App\Models\MovimientoRecarga;
use App\Models\DistribuidoraRecarga;
use App\Models\Cuenta;
use App\Models\MovimientoNegocio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MovimientoRecargaController extends Controller
{
    public function index(Request $request)
    {
        $query = MovimientoRecarga::where('user_id', auth()->id())
            ->with('distribuidora', 'cuenta')
            ->orderByDesc('fecha');

        if ($request->filled('distribuidora_id')) {
            $query->where('distribuidora_id', $request->distribuidora_id);
        }
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }
        if ($request->filled('mes')) {
            $query->whereMonth('fecha', $request->mes);
        }

        $movimientos    = $query->paginate(20);
        $distribuidoras = DistribuidoraRecarga::where('user_id', auth()->id())->get();

        // Totales del mes actual
        $comisionesmes = MovimientoRecarga::where('user_id', auth()->id())
            ->where('tipo', 'venta')
            ->whereMonth('fecha', now()->month)
            ->whereYear('fecha', now()->year)
            ->sum('comision');

        $ventasMes = MovimientoRecarga::where('user_id', auth()->id())
            ->where('tipo', 'venta')
            ->whereMonth('fecha', now()->month)
            ->whereYear('fecha', now()->year)
            ->count();

        return view('recargas.index', compact(
            'movimientos', 'distribuidoras', 'comisionesmes', 'ventasMes'
        ));
    }

    public function create()
    {
        $distribuidoras = DistribuidoraRecarga::where('user_id', auth()->id())
            ->where('activa', true)->get();
        $cuentas = Cuenta::where('user_id', auth()->id())
            ->where('activa', true)->get();

        return view('recargas.create', compact('distribuidoras', 'cuentas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'distribuidora_id' => 'required|exists:distribuidoras_recarga,id',
            'tipo'             => 'required|in:deposito,venta,ajuste',
            'monto'            => 'required|numeric|min:0.01',
            'fecha'            => 'required|date',
            'cuenta_id'        => 'nullable|exists:cuentas,id',
            'comision'         => 'nullable|numeric|min:0',
            'numero_destino'   => 'nullable|string|max:20',
        ]);

        $distribuidora = DistribuidoraRecarga::findOrFail($request->distribuidora_id);

        // Verificar saldo suficiente en ventas
        if ($request->tipo === 'venta' && $distribuidora->saldo_actual < $request->monto) {
            return back()->withErrors(['monto' => "Saldo insuficiente con {$distribuidora->nombre}. Disponible: L. " . number_format($distribuidora->saldo_actual, 2)])->withInput();
        }

        DB::transaction(function () use ($request, $distribuidora) {

            // Calcular nuevo saldo
            $nuevoSaldo = match($request->tipo) {
                'deposito' => $distribuidora->saldo_actual + $request->monto,
                'venta'    => $distribuidora->saldo_actual - $request->monto,
                'ajuste'   => $request->monto,
            };

            // Registrar movimiento de recarga
            MovimientoRecarga::create([
                'user_id'          => auth()->id(),
                'distribuidora_id' => $request->distribuidora_id,
                'cuenta_id'        => $request->cuenta_id,
                'tipo'             => $request->tipo,
                'monto'            => $request->monto,
                'comision'         => $request->comision ?? 0,
                'saldo_despues'    => $nuevoSaldo,
                'numero_destino'   => $request->numero_destino,
                'fecha'            => $request->fecha,
                'notas'            => $request->notas,
            ]);

            // Actualizar saldo de la distribuidora
            $distribuidora->update(['saldo_actual' => $nuevoSaldo]);

            // Si es venta, registrar la comisión como ingreso del negocio
            if ($request->tipo === 'venta' && $request->comision > 0 && $request->cuenta_id) {
                MovimientoNegocio::create([
                    'user_id'     => auth()->id(),
                    'cuenta_id'   => $request->cuenta_id,
                    'area'        => 'papeleria',
                    'tipo'        => 'venta_efectivo',
                    'direccion'   => 'entrada',
                    'monto'       => $request->comision,
                    'descripcion' => "Comisión recarga {$distribuidora->nombre} L.{$request->monto} → {$request->numero_destino}",
                    'fecha'       => $request->fecha,
                    'estado'      => 'pagado',
                ]);
            }

            // Si es depósito, registrar como egreso del negocio
            if ($request->tipo === 'deposito' && $request->cuenta_id) {
                MovimientoNegocio::create([
                    'user_id'     => auth()->id(),
                    'cuenta_id'   => $request->cuenta_id,
                    'area'        => 'papeleria',
                    'tipo'        => 'gasto_varios',
                    'direccion'   => 'salida',
                    'monto'       => $request->monto,
                    'descripcion' => "Depósito saldo {$distribuidora->nombre}",
                    'fecha'       => $request->fecha,
                    'estado'      => 'pagado',
                ]);
            }
        });

        return redirect()->route('recargas.index')
            ->with('success', 'Recarga registrada correctamente.');
    }

    public function destroy(MovimientoRecarga $recarga)
    {
        abort_if($recarga->user_id !== auth()->id(), 403);
        $recarga->delete();

        return redirect()->route('recargas.index')
            ->with('success', 'Registro eliminado.');
    }
}
