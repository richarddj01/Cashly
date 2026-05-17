<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cuenta;
use App\Models\MovimientoNegocio;
use App\Models\MovimientoPersonal;
use App\Models\MovimientoRecarga;
use App\Models\Prestamo;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Saldos de cuentas
        $cuentas = Cuenta::where('user_id', $user->id)
            ->where('activa', true)
            ->get();

        // Resumen personal del mes actual
        $personalIngresos = MovimientoPersonal::where('user_id', $user->id)
            ->where('direccion', 'entrada')
            ->where('estado', 'pagado')
            ->whereMonth('fecha', now()->month)
            ->whereYear('fecha', now()->year)
            ->sum('monto');

        $personalEgresos = MovimientoPersonal::where('user_id', $user->id)
            ->where('direccion', 'salida')
            ->where('estado', 'pagado')
            ->whereMonth('fecha', now()->month)
            ->whereYear('fecha', now()->year)
            ->sum('monto');

        // Resumen negocio del mes actual
        $negocioIngresos = MovimientoNegocio::where('user_id', $user->id)
            ->where('direccion', 'entrada')
            ->where('estado', 'pagado')
            ->whereMonth('fecha', now()->month)
            ->whereYear('fecha', now()->year)
            ->sum('monto');

        $negocioEgresos = MovimientoNegocio::where('user_id', $user->id)
            ->where('direccion', 'salida')
            ->where('estado', 'pagado')
            ->whereMonth('fecha', now()->month)
            ->whereYear('fecha', now()->year)
            ->sum('monto');

        // Facturas pendientes
        $facturasPendientes = MovimientoNegocio::where('user_id', $user->id)
            ->where('tipo', 'factura_proveedor')
            ->where('estado', 'pendiente')
            ->orderBy('fecha_vencimiento')
            ->take(5)
            ->get();

        // Préstamos pendientes
        $prestamosPendientes = Prestamo::where('user_id', $user->id)
            ->where('estado', 'pendiente')
            ->sum('monto');

        // Comisiones de recargas del mes
        $comisionesRecargas = MovimientoRecarga::where('user_id', $user->id)
            ->where('tipo', 'venta')
            ->whereMonth('fecha', now()->month)
            ->whereYear('fecha', now()->year)
            ->sum('comision');

        // Últimos movimientos
        $ultimosMovimientos = MovimientoPersonal::where('user_id', $user->id)
            ->with('cuenta', 'categoria')
            ->orderByDesc('fecha')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'cuentas',
            'personalIngresos',
            'personalEgresos',
            'negocioIngresos',
            'negocioEgresos',
            'facturasPendientes',
            'prestamosPendientes',
            'comisionesRecargas',
            'ultimosMovimientos',
        ));
    }
}
