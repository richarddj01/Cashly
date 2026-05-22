<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Personal — {{ \Carbon\Carbon::create($anio, $mes)->translatedFormat('F Y') }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #333; }

        .header { background: #1a1a2e; color: #fff; padding: 20px; margin-bottom: 20px; }
        .header h1 { font-size: 22px; font-weight: bold; }
        .header h1 span { color: #4cc9f0; }
        .header p { color: rgba(255,255,255,0.7); font-size: 11px; margin-top: 4px; }

        .resumen { display: table; width: 100%; margin-bottom: 20px; }
        .resumen-col { display: table-cell; width: 33%; padding: 12px; text-align: center; }
        .resumen-col.ingresos { background: #d1fae5; border-radius: 8px; }
        .resumen-col.egresos  { background: #fee2e2; border-radius: 8px; }
        .resumen-col.balance  { background: #dbeafe; border-radius: 8px; }
        .resumen-col label { font-size: 10px; color: #666; display: block; margin-bottom: 4px; }
        .resumen-col strong { font-size: 16px; }

        .seccion-titulo {
            font-size: 12px;
            font-weight: bold;
            color: #1a1a2e;
            border-bottom: 2px solid #4cc9f0;
            padding-bottom: 4px;
            margin-bottom: 10px;
            margin-top: 20px;
        }

        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        th { background: #f1f5f9; padding: 7px 8px; text-align: left; font-size: 10px; color: #555; }
        td { padding: 6px 8px; border-bottom: 1px solid #f1f5f9; font-size: 10px; }
        tr:last-child td { border-bottom: none; }

        .entrada { color: #198754; font-weight: bold; }
        .salida  { color: #dc3545; font-weight: bold; }
        .badge   { padding: 2px 6px; border-radius: 4px; font-size: 9px; }
        .badge-success  { background: #d1fae5; color: #065f46; }
        .badge-warning  { background: #fef9c3; color: #854d0e; }
        .badge-secondary{ background: #f1f5f9; color: #555; }

        .footer { margin-top: 30px; text-align: center; color: #aaa; font-size: 9px; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Cash<span>ly</span> — Reporte Personal</h1>
        <p>{{ \Carbon\Carbon::create($anio, $mes)->translatedFormat('F Y') }} · {{ $user->name }} · Generado el {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    {{-- Resumen --}}
    <div class="resumen">
        <div class="resumen-col ingresos">
            <label>Ingresos</label>
            <strong style="color:#198754;">L. {{ number_format($ingresos, 2) }}</strong>
        </div>
        <div class="resumen-col" style="width:4%;"></div>
        <div class="resumen-col egresos">
            <label>Egresos</label>
            <strong style="color:#dc3545;">L. {{ number_format($egresos, 2) }}</strong>
        </div>
        <div class="resumen-col" style="width:4%;"></div>
        <div class="resumen-col balance">
            <label>Balance</label>
            @php $balance = $ingresos - $egresos; @endphp
            <strong style="color:{{ $balance >= 0 ? '#1d4ed8' : '#dc3545' }};">
                L. {{ number_format($balance, 2) }}
            </strong>
        </div>
    </div>

    {{-- Movimientos --}}
    <div class="seccion-titulo">Detalle de movimientos</div>
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Descripción</th>
                <th>Categoría</th>
                <th>Cuenta</th>
                <th>Estado</th>
                <th style="text-align:right;">Monto</th>
            </tr>
        </thead>
        <tbody>
            @forelse($movimientos as $mov)
                <tr>
                    <td>{{ $mov->fecha->format('d/m/Y') }}</td>
                    <td>{{ $mov->descripcion ?? ucfirst(str_replace('_', ' ', $mov->tipo)) }}</td>
                    <td>{{ $mov->categoria->nombre ?? '—' }}</td>
                    <td>{{ $mov->cuenta->nombre ?? '—' }}</td>
                    <td>
                        <span class="badge badge-{{ $mov->estado === 'pagado' ? 'success' : ($mov->estado === 'pendiente' ? 'warning' : 'secondary') }}">
                            {{ ucfirst($mov->estado) }}
                        </span>
                    </td>
                    <td style="text-align:right;" class="{{ $mov->direccion === 'entrada' ? 'entrada' : 'salida' }}">
                        {{ $mov->direccion === 'entrada' ? '+' : '-' }}L. {{ number_format($mov->monto, 2) }}
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" style="text-align:center;color:#aaa;">Sin movimientos este mes.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- Saldos de cuentas --}}
    <div class="seccion-titulo">Saldos de cuentas personales</div>
    <table>
        <thead>
            <tr>
                <th>Cuenta</th>
                <th>Tipo</th>
                <th style="text-align:right;">Saldo actual</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cuentas as $cuenta)
                <tr>
                    <td>{{ $cuenta->nombre }}</td>
                    <td>{{ ucfirst($cuenta->tipo) }}</td>
                    <td style="text-align:right;" class="{{ $cuenta->saldo_actual >= 0 ? 'entrada' : 'salida' }}">
                        L. {{ number_format($cuenta->saldo_actual, 2) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Cashly — Sistema de finanzas personales · Reporte generado automáticamente
    </div>

</body>
</html>
