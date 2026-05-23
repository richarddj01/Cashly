<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cuenta;
use App\Models\MovimientoNegocio;
use App\Models\MovimientoPersonal;
use App\Models\MovimientoRecarga;
use App\Models\Prestamo;
use App\Models\Categoria;
use App\Models\AreaNegocio;

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

        // ── DATOS PARA GRÁFICAS ──────────────────────────────

        // Flujo de caja personal — últimos 6 meses
        $meses = collect(range(5, 0))->map(function ($i) {
            return now()->subMonths($i);
        });

        $flujoCajaPersonal = $meses->map(function ($mes) use ($user) {
            return [
                'mes'      => $mes->translatedFormat('M'),
                'ingresos' => MovimientoPersonal::where('user_id', $user->id)
                    ->where('direccion', 'entrada')->where('estado', 'pagado')
                    ->whereMonth('fecha', $mes->month)->whereYear('fecha', $mes->year)
                    ->sum('monto'),
                'egresos'  => MovimientoPersonal::where('user_id', $user->id)
                    ->where('direccion', 'salida')->where('estado', 'pagado')
                    ->whereMonth('fecha', $mes->month)->whereYear('fecha', $mes->year)
                    ->sum('monto'),
            ];
        });

        // Flujo de caja negocio — últimos 6 meses
        $flujoCajaNegocio = $meses->map(function ($mes) use ($user) {
            return [
                'mes'      => $mes->translatedFormat('M'),
                'ingresos' => MovimientoNegocio::where('user_id', $user->id)
                    ->where('direccion', 'entrada')->where('estado', 'pagado')
                    ->whereMonth('fecha', $mes->month)->whereYear('fecha', $mes->year)
                    ->sum('monto'),
                'egresos'  => MovimientoNegocio::where('user_id', $user->id)
                    ->where('direccion', 'salida')->where('estado', 'pagado')
                    ->whereMonth('fecha', $mes->month)->whereYear('fecha', $mes->year)
                    ->sum('monto'),
            ];
        });

        // Gastos personales por categoría este mes
        $gastosPorCategoria = MovimientoPersonal::where('user_id', $user->id)
            ->where('direccion', 'salida')
            ->where('estado', 'pagado')
            ->whereMonth('fecha', now()->month)
            ->whereYear('fecha', now()->year)
            ->whereNotNull('categoria_id')
            ->with('categoria')
            ->get()
            ->groupBy('categoria_id')
            ->map(function ($movimientos) {
                return [
                    'nombre' => $movimientos->first()->categoria->nombre,
                    'color'  => $movimientos->first()->categoria->color,
                    'total'  => $movimientos->sum('monto'),
                ];
            })
            ->values();

       // Ingresos negocio por área este mes
        $areas = AreaNegocio::where('user_id', $user->id)->where('activa', true)->get();

        $ingresosPorArea = $areas->map(function ($area) use ($user) {
            return [
                'nombre' => $area->nombre,
                'color'  => $area->color,
                'total'  => MovimientoNegocio::where('user_id', $user->id)
                    ->where('area_id', $area->id)
                    ->where('direccion', 'entrada')
                    ->whereMonth('fecha', now()->month)
                    ->whereYear('fecha', now()->year)
                    ->sum('monto'),
            ];
        });

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
            'flujoCajaPersonal',
            'flujoCajaNegocio',
            'gastosPorCategoria',
            'ingresosPorArea',
            'areas',
        ));
    }
}
