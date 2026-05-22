<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Negocio — {{ \Carbon\Carbon::create($anio, $mes)->translatedFormat('F Y') }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #333; }

        .header { background: #1a1a2e; color: #fff; padding: 20px; margin-bottom: 20px; }
        .header h1 { font-size: 22px; font-weight: bold; }
        .header h1 span { color: #f8961e; }
        .header p { color: rgba(255,255,255,0.7); font-size: 11px; margin-top: 4px; }

        .resumen-grid { width: 100%; margin-bottom: 20px; }
        .resumen-grid td { padding: 10px; text-align: center; border-radius: 6px; }
        .resumen-grid label { font-size: 9px; color: #666; display: block; margin-bottom: 4px; }
        .resumen-grid strong { font-size: 14px; }

        .seccion-titulo {
            font-size: 12px; font-weight: bold; color: #1a1a2e;
            border-bottom: 2px solid #f8961e;
            padding-bottom: 4px; margin-bottom: 10px; margin-top: 20px;
        }

        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        th { background: #f1f5f9; padding: 7px 8px; text-align: left; font-size: 10px; color: #555; }
        td { padding: 6px 8px; border-bottom: 1px solid #f1f5f9; font-size: 10px; }
        tr:last-child td { border-bottom: none; }

        .entrada { color: #198754; font-weight: bold; }
        .salida  { color: #dc3545; font-weight: bold; }
        .badge   { padding: 2px 6px; border-radius: 4px; font-size: 9px; }
        .badge-papeleria   { background: #fff4e6; color: #c05c00; }
        .badge-impresiones { background: #f3e6fd; color: #6b21a8; }
        .badge-compartido  { background: #f1f5f9; color: #555; }

        .footer { margin-top: 30px; text-align: center; color: #aaa; font-size: 9px; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Cash<span>ly</span> — Reporte Negocio</h1>
        <p>{{ \Carbon\Carbon::create($anio, $mes)->translatedFormat('F Y') }} · {{ $user->name }} · Generado el {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    {{-- Resumen --}}
    <table class="resumen-grid">
        <tr>
            <td style="background:#d1fae5;">
                <label>Total ingresos</label>
                <strong style="color:#198754;">L. {{ number_format($ingresos, 2) }}</strong>
            </td>
            <td style="width:2%;"></td>
            <td style="background:#fee2e2;">
                <label>Total egresos</label>
                <strong style="color:#dc3545;">L. {{ number_format($egresos, 2) }}</strong>
            </td>
            <td style="width:2%;"></td>
            <td style="background:#dbeafe;">
                <label>Balance</label>
                @php $balance = $ingresos - $egresos; @endphp
                <strong style="color:{{ $balance >= 0 ? '#1d4ed8' : '#dc3545' }};">
                    L. {{ number_format($balance, 2) }}
                </strong>
            </td>
            <td style="width:2%;"></td>
            <td style="background:#f3e6fd;">
                <label>Comisiones recargas</label>
                <strong style="color:#7209b7;">L. {{ number_format($comisiones, 2) }}</strong>
            </td>
        </tr>
    </table>

    {{-- Papelería --}}
    @if($papeleria->count())
        <div class="seccion-titulo">Papelería general</div>
        <table>
            <thead>
                <tr>
                    <th>Fecha</th><th>Descripción</th><th>Tipo</th>
                    <th>Cuenta</th><th>Estado</th>
                    <th style="text-align:right;">Monto</th>
                </tr>
            </thead>
            <tbody>
                @foreach($papeleria as $mov)
                    <tr>
                        <td>{{ $mov->fecha->format('d/m/Y') }}</td>
                        <td>{{ $mov->descripcion ?? ucfirst(str_replace('_', ' ', $mov->tipo)) }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $mov->tipo)) }}</td>
                        <td>{{ $mov->cuenta->nombre ?? '—' }}</td>
                        <td>{{ ucfirst($mov->estado) }}</td>
                        <td style="text-align:right;" class="{{ $mov->direccion === 'entrada' ? 'entrada' : 'salida' }}">
                            {{ $mov->direccion === 'entrada' ? '+' : '-' }}L. {{ number_format($mov->monto, 2) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- Impresiones --}}
    @if($impresiones->count())
        <div class="seccion-titulo">Impresiones y servicios</div>
        <table>
            <thead>
                <tr>
                    <th>Fecha</th><th>Descripción</th><th>Tipo</th>
                    <th>Cuenta</th><th>Estado</th>
                    <th style="text-align:right;">Monto</th>
                </tr>
            </thead>
            <tbody>
                @foreach($impresiones as $mov)
                    <tr>
                        <td>{{ $mov->fecha->format('d/m/Y') }}</td>
                        <td>{{ $mov->descripcion ?? ucfirst(str_replace('_', ' ', $mov->tipo)) }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $mov->tipo)) }}</td>
                        <td>{{ $mov->cuenta->nombre ?? '—' }}</td>
                        <td>{{ ucfirst($mov->estado) }}</td>
                        <td style="text-align:right;" class="{{ $mov->direccion === 'entrada' ? 'entrada' : 'salida' }}">
                            {{ $mov->direccion === 'entrada' ? '+' : '-' }}L. {{ number_format($mov->monto, 2) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- Compartido --}}
    @if($compartido->count())
        <div class="seccion-titulo">Gastos compartidos</div>
        <table>
            <thead>
                <tr>
                    <th>Fecha</th><th>Descripción</th>
                    <th>Cuenta</th><th>Estado</th>
                    <th style="text-align:right;">Monto</th>
                </tr>
            </thead>
            <tbody>
                @foreach($compartido as $mov)
                    <tr>
                        <td>{{ $mov->fecha->format('d/m/Y') }}</td>
                        <td>{{ $mov->descripcion ?? ucfirst(str_replace('_', ' ', $mov->tipo)) }}</td>
                        <td>{{ $mov->cuenta->nombre ?? '—' }}</td>
                        <td>{{ ucfirst($mov->estado) }}</td>
                        <td style="text-align:right;" class="{{ $mov->direccion === 'entrada' ? 'entrada' : 'salida' }}">
                            {{ $mov->direccion === 'entrada' ? '+' : '-' }}L. {{ number_format($mov->monto, 2) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        Cashly — Sistema de finanzas · Reporte generado automáticamente
    </div>

</body>
</html>
