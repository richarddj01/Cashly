<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resumen General — {{ \Carbon\Carbon::create($anio, $mes)->translatedFormat('F Y') }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #333; }

        .header { background: #1a1a2e; color: #fff; padding: 20px; margin-bottom: 20px; }
        .header h1 { font-size: 22px; font-weight: bold; }
        .header h1 span { color: #4cc9f0; }
        .header p { color: rgba(255,255,255,0.7); font-size: 11px; margin-top: 4px; }

        .seccion-titulo {
            font-size: 12px; font-weight: bold; color: #1a1a2e;
            border-bottom: 2px solid #4cc9f0;
            padding-bottom: 4px; margin-bottom: 10px; margin-top: 20px;
        }

        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        th { background: #f1f5f9; padding: 7px 8px; text-align: left; font-size: 10px; color: #555; }
        td { padding: 8px; border-bottom: 1px solid #f1f5f9; font-size: 11px; }
        tr:last-child td { border-bottom: none; }

        .positivo { color: #198754; font-weight: bold; }
        .negativo { color: #dc3545; font-weight: bold; }
        .neutro   { color: #0d6efd; font-weight: bold; }

        .total-row td { background: #f8f9fa; font-weight: bold; }

        .footer { margin-top: 30px; text-align: center; color: #aaa; font-size: 9px; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Cash<span>ly</span> — Resumen General</h1>
        <p>{{ \Carbon\Carbon::create($anio, $mes)->translatedFormat('F Y') }} · {{ $user->name }} · Generado el {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    {{-- Resumen personal --}}
    <div class="seccion-titulo">Finanzas personales</div>
    <table>
        <tbody>
            <tr>
                <td>Ingresos del mes</td>
                <td class="positivo" style="text-align:right;">L. {{ number_format($personalIngresos, 2) }}</td>
            </tr>
            <tr>
                <td>Egresos del mes</td>
                <td class="negativo" style="text-align:right;">L. {{ number_format($personalEgresos, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td>Balance personal</td>
                @php $balancePersonal = $personalIngresos - $personalEgresos; @endphp
                <td class="{{ $balancePersonal >= 0 ? 'positivo' : 'negativo' }}" style="text-align:right;">
                    L. {{ number_format($balancePersonal, 2) }}
                </td>
            </tr>
        </tbody>
    </table>

    {{-- Resumen negocio --}}
    <div class="seccion-titulo">Negocio (papelería)</div>
    <table>
        <tbody>
            <tr>
                <td>Ingresos del mes</td>
                <td class="positivo" style="text-align:right;">L. {{ number_format($negocioIngresos, 2) }}</td>
            </tr>
            <tr>
                <td>Egresos del mes</td>
                <td class="negativo" style="text-align:right;">L. {{ number_format($negocioEgresos, 2) }}</td>
            </tr>
            <tr>
                <td>Comisiones recargas</td>
                <td class="positivo" style="text-align:right;">L. {{ number_format($comisiones, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td>Balance negocio</td>
                @php $balanceNegocio = $negocioIngresos - $negocioEgresos; @endphp
                <td class="{{ $balanceNegocio >= 0 ? 'positivo' : 'negativo' }}" style="text-align:right;">
                    L. {{ number_format($balanceNegocio, 2) }}
                </td>
            </tr>
        </tbody>
    </table>

    {{-- Saldos cuentas --}}
    <div class="seccion-titulo">Saldos de cuentas</div>
    <table>
        <thead>
            <tr>
                <th>Cuenta</th>
                <th>Tipo</th>
                <th>Contexto</th>
                <th style="text-align:right;">Saldo actual</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cuentas as $cuenta)
                <tr>
                    <td>{{ $cuenta->nombre }}</td>
                    <td>{{ ucfirst($cuenta->tipo) }}</td>
                    <td>{{ ucfirst($cuenta->contexto) }}</td>
                    <td style="text-align:right;"
                        class="{{ $cuenta->saldo_actual >= 0 ? 'positivo' : 'negativo' }}">
                        L. {{ number_format($cuenta->saldo_actual, 2) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Préstamos pendientes --}}
    @if($prestamos->count())
        <div class="seccion-titulo">Préstamos pendientes</div>
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Dirección</th>
                    <th>Motivo</th>
                    <th style="text-align:right;">Monto</th>
                </tr>
            </thead>
            <tbody>
                @foreach($prestamos as $prestamo)
                    <tr>
                        <td>{{ $prestamo->fecha->format('d/m/Y') }}</td>
                        <td>{{ $prestamo->direccion === 'personal_a_negocio' ? 'Personal → Negocio' : 'Negocio → Personal' }}</td>
                        <td>{{ $prestamo->motivo ?? '—' }}</td>
                        <td style="text-align:right;" class="neutro">
                            L. {{ number_format($prestamo->monto, 2) }}
                        </td>
                    </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="3">Total pendiente</td>
                    <td style="text-align:right;" class="neutro">
                        L. {{ number_format($prestamos->sum('monto'), 2) }}
                    </td>
                </tr>
            </tbody>
        </table>
    @endif

    {{-- Balance total --}}
    <div class="seccion-titulo">Balance total</div>
    <table>
        <tbody>
            <tr class="total-row">
                <td>Balance personal + negocio</td>
                @php $balanceTotal = $balancePersonal + $balanceNegocio + $comisiones; @endphp
                <td style="text-align:right; font-size:14px;"
                    class="{{ $balanceTotal >= 0 ? 'positivo' : 'negativo' }}">
                    L. {{ number_format($balanceTotal, 2) }}
                </td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Cashly — Sistema de finanzas personales · Reporte generado automáticamente
    </div>

</body>
</html>
