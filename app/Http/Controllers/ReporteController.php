<?php

namespace App\Http\Controllers;

use App\Models\MovimientoPersonal;
use App\Models\MovimientoNegocio;
use App\Models\MovimientoRecarga;
use App\Models\Cuenta;
use App\Models\Prestamo;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    // Reporte personal mensual
    public function personal(Request $request)
    {
        $mes  = $request->get('mes', now()->month);
        $anio = $request->get('anio', now()->year);
        $user = auth()->user();

        $movimientos = MovimientoPersonal::where('user_id', $user->id)
            ->with('cuenta', 'categoria')
            ->whereMonth('fecha', $mes)
            ->whereYear('fecha', $anio)
            ->orderBy('fecha')
            ->get();

        $ingresos = $movimientos->where('direccion', 'entrada')->sum('monto');
        $egresos  = $movimientos->where('direccion', 'salida')->sum('monto');

        $cuentas = Cuenta::where('user_id', $user->id)
            ->where('activa', true)
            ->where('contexto', 'personal')
            ->get();

        $pdf = Pdf::loadView('reportes.personal', compact(
            'movimientos', 'ingresos', 'egresos', 'cuentas', 'mes', 'anio', 'user'
        ))->setPaper('letter', 'portrait');

        $nombre = "cashly-personal-{$anio}-{$mes}.pdf";
        return $pdf->download($nombre);
    }

    // Reporte negocio mensual
    public function negocio(Request $request)
    {
        $mes  = $request->get('mes', now()->month);
        $anio = $request->get('anio', now()->year);
        $user = auth()->user();

        $movimientos = MovimientoNegocio::where('user_id', $user->id)
            ->with('cuenta', 'categoria', 'empleado')
            ->whereMonth('fecha', $mes)
            ->whereYear('fecha', $anio)
            ->orderBy('fecha')
            ->get();

        $ingresos     = $movimientos->where('direccion', 'entrada')->sum('monto');
        $egresos      = $movimientos->where('direccion', 'salida')->sum('monto');
        $papeleria    = $movimientos->where('area', 'papeleria');
        $impresiones  = $movimientos->where('area', 'impresiones');
        $compartido   = $movimientos->where('area', 'compartido');

        $comisiones = MovimientoRecarga::where('user_id', $user->id)
            ->where('tipo', 'venta')
            ->whereMonth('fecha', $mes)
            ->whereYear('fecha', $anio)
            ->sum('comision');

        $pdf = Pdf::loadView('reportes.negocio', compact(
            'movimientos', 'ingresos', 'egresos',
            'papeleria', 'impresiones', 'compartido',
            'comisiones', 'mes', 'anio', 'user'
        ))->setPaper('letter', 'portrait');

        $nombre = "cashly-negocio-{$anio}-{$mes}.pdf";
        return $pdf->download($nombre);
    }

    // Resumen general
    public function resumen(Request $request)
    {
        $mes  = $request->get('mes', now()->month);
        $anio = $request->get('anio', now()->year);
        $user = auth()->user();

        $personalIngresos = MovimientoPersonal::where('user_id', $user->id)
            ->where('direccion', 'entrada')->where('estado', 'pagado')
            ->whereMonth('fecha', $mes)->whereYear('fecha', $anio)
            ->sum('monto');

        $personalEgresos = MovimientoPersonal::where('user_id', $user->id)
            ->where('direccion', 'salida')->where('estado', 'pagado')
            ->whereMonth('fecha', $mes)->whereYear('fecha', $anio)
            ->sum('monto');

        $negocioIngresos = MovimientoNegocio::where('user_id', $user->id)
            ->where('direccion', 'entrada')->where('estado', 'pagado')
            ->whereMonth('fecha', $mes)->whereYear('fecha', $anio)
            ->sum('monto');

        $negocioEgresos = MovimientoNegocio::where('user_id', $user->id)
            ->where('direccion', 'salida')->where('estado', 'pagado')
            ->whereMonth('fecha', $mes)->whereYear('fecha', $anio)
            ->sum('monto');

        $comisiones = MovimientoRecarga::where('user_id', $user->id)
            ->where('tipo', 'venta')
            ->whereMonth('fecha', $mes)->whereYear('fecha', $anio)
            ->sum('comision');

        $prestamos = Prestamo::where('user_id', $user->id)
            ->where('estado', 'pendiente')
            ->get();

        $cuentas = Cuenta::where('user_id', $user->id)
            ->where('activa', true)->get();

        $pdf = Pdf::loadView('reportes.resumen', compact(
            'personalIngresos', 'personalEgresos',
            'negocioIngresos', 'negocioEgresos',
            'comisiones', 'prestamos', 'cuentas',
            'mes', 'anio', 'user'
        ))->setPaper('letter', 'portrait');

        $nombre = "cashly-resumen-{$anio}-{$mes}.pdf";
        return $pdf->download($nombre);
    }
}
