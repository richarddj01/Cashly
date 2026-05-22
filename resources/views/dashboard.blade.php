@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

{{-- Tarjetas de resumen --}}
<div class="row g-3 mb-4">

    <div class="col-md-3">
        <div class="card card-stat h-100" style="border-color: #4cc9f0;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted small mb-1">Ingresos personales</p>
                        <h4 class="fw-bold mb-0">L. {{ number_format($personalIngresos, 2) }}</h4>
                        <small class="text-muted">Este mes</small>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                         style="width:42px;height:42px;background:#e8f9fd;">
                        <i class="bi bi-arrow-down-circle" style="color:#4cc9f0;font-size:1.3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-stat h-100" style="border-color: #f72585;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted small mb-1">Egresos personales</p>
                        <h4 class="fw-bold mb-0">L. {{ number_format($personalEgresos, 2) }}</h4>
                        <small class="text-muted">Este mes</small>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                         style="width:42px;height:42px;background:#fef0f7;">
                        <i class="bi bi-arrow-up-circle" style="color:#f72585;font-size:1.3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-stat h-100" style="border-color: #f8961e;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted small mb-1">Balance negocio</p>
                        <h4 class="fw-bold mb-0">L. {{ number_format($negocioIngresos - $negocioEgresos, 2) }}</h4>
                        <small class="text-muted">Este mes</small>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                         style="width:42px;height:42px;background:#fff4e6;">
                        <i class="bi bi-shop" style="color:#f8961e;font-size:1.3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-stat h-100" style="border-color: #7209b7;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted small mb-1">Comisiones recargas</p>
                        <h4 class="fw-bold mb-0">L. {{ number_format($comisionesRecargas, 2) }}</h4>
                        <small class="text-muted">Este mes</small>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                         style="width:42px;height:42px;background:#f3e6fd;">
                        <i class="bi bi-phone" style="color:#7209b7;font-size:1.3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="text-end mb-3">
    <a href="{{ route('reportes.resumen') }}" class="btn btn-outline-danger" target="_blank">
        <i class="bi bi-file-pdf me-1"></i> Descargar resumen del mes
    </a>
</div>

{{-- Gráficas --}}
<div class="row g-3 mb-4">

    {{-- Flujo de caja personal --}}
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="fw-semibold mb-3">
                    <i class="bi bi-graph-up me-2 text-primary"></i>
                    Flujo de caja personal — últimos 6 meses
                </h6>
                <canvas id="grafica-personal" height="200"></canvas>
            </div>
        </div>
    </div>

    {{-- Flujo de caja negocio --}}
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="fw-semibold mb-3">
                    <i class="bi bi-graph-up me-2 text-warning"></i>
                    Flujo de caja negocio — últimos 6 meses
                </h6>
                <canvas id="grafica-negocio" height="200"></canvas>
            </div>
        </div>
    </div>

    {{-- Gastos personales por categoría --}}
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="fw-semibold mb-3">
                    <i class="bi bi-pie-chart me-2 text-danger"></i>
                    Gastos personales por categoría — este mes
                </h6>
                @if($gastosPorCategoria->isEmpty())
                    <p class="text-muted small text-center py-4">Sin gastos categorizados este mes.</p>
                @else
                    <canvas id="grafica-categorias" height="200"></canvas>
                @endif
            </div>
        </div>
    </div>

    {{-- Ingresos negocio por área --}}
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="fw-semibold mb-3">
                    <i class="bi bi-bar-chart me-2 text-success"></i>
                    Ingresos negocio por área — este mes
                </h6>
                <canvas id="grafica-areas" height="200"></canvas>
            </div>
        </div>
    </div>

</div>

{{-- Cuentas, facturas y últimos movimientos --}}
<div class="row g-3 mb-4">

    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="fw-semibold mb-3">
                    <i class="bi bi-wallet2 me-2 text-primary"></i>Mis cuentas
                </h6>
                @forelse($cuentas as $cuenta)
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <div>
                            <span class="fw-medium">{{ $cuenta->nombre }}</span>
                            <br>
                            <small class="text-muted">{{ ucfirst($cuenta->tipo) }} · {{ ucfirst($cuenta->contexto) }}</small>
                        </div>
                        <span class="fw-bold {{ $cuenta->saldo_actual >= 0 ? 'text-success' : 'text-danger' }}">
                            L. {{ number_format($cuenta->saldo_actual, 2) }}
                        </span>
                    </div>
                @empty
                    <p class="text-muted small text-center py-3">
                        No tienes cuentas registradas.<br>
                        <a href="{{ route('cuentas.create') }}">Crear primera cuenta</a>
                    </p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="fw-semibold mb-3">
                    <i class="bi bi-exclamation-circle me-2 text-warning"></i>Facturas pendientes
                </h6>
                @forelse($facturasPendientes as $factura)
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <div>
                            <span class="fw-medium small">{{ $factura->descripcion ?? 'Sin descripción' }}</span>
                            <br>
                            <small class="text-muted">
                                Vence: {{ $factura->fecha_vencimiento?->format('d/m/Y') ?? 'Sin fecha' }}
                            </small>
                        </div>
                        <span class="fw-bold text-danger small">
                            L. {{ number_format($factura->monto, 2) }}
                        </span>
                    </div>
                @empty
                    <p class="text-muted small text-center py-3">Sin facturas pendientes</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="fw-semibold mb-3">
                    <i class="bi bi-clock-history me-2 text-info"></i>Últimos movimientos
                </h6>
                @forelse($ultimosMovimientos as $mov)
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <div>
                            <span class="fw-medium small">
                                {{ $mov->descripcion ?? ucfirst(str_replace('_', ' ', $mov->tipo)) }}
                            </span>
                            <br>
                            <small class="text-muted">{{ $mov->fecha->format('d/m/Y') }}</small>
                        </div>
                        <span class="fw-bold small {{ $mov->direccion === 'entrada' ? 'text-success' : 'text-danger' }}">
                            {{ $mov->direccion === 'entrada' ? '+' : '-' }}L. {{ number_format($mov->monto, 2) }}
                        </span>
                    </div>
                @empty
                    <p class="text-muted small text-center py-3">Sin movimientos recientes</p>
                @endforelse
            </div>
        </div>
    </div>

</div>

{{-- Préstamos pendientes --}}
@if($prestamosPendientes > 0)
    <div class="alert alert-warning d-flex align-items-center" role="alert">
        <i class="bi bi-arrow-down-up me-2 fs-5"></i>
        <div>
            Tienes <strong>L. {{ number_format($prestamosPendientes, 2) }}</strong> en préstamos pendientes
            entre tu cuenta personal y el negocio.
            <a href="{{ route('prestamos.index') }}" class="alert-link ms-2">Ver detalle →</a>
        </div>
    </div>
@endif

@endsection

@push('scripts')
<script>
// ── Datos desde Laravel ──────────────────────────────
const flujoCajaPersonal = @json($flujoCajaPersonal);
const flujoCajaNegocio  = @json($flujoCajaNegocio);
const gastosCategorias  = @json($gastosPorCategoria);
const ingresosPorArea   = @json($ingresosPorArea);

// ── Colores comunes ──────────────────────────────────
const colorEntrada = 'rgba(25, 135, 84, 0.8)';
const colorSalida  = 'rgba(220, 53, 69, 0.8)';

// ── Gráfica 1: Flujo caja personal ──────────────────
new Chart(document.getElementById('grafica-personal'), {
    type: 'bar',
    data: {
        labels: flujoCajaPersonal.map(d => d.mes),
        datasets: [
            {
                label: 'Ingresos',
                data: flujoCajaPersonal.map(d => d.ingresos),
                backgroundColor: colorEntrada,
                borderRadius: 6,
            },
            {
                label: 'Egresos',
                data: flujoCajaPersonal.map(d => d.egresos),
                backgroundColor: colorSalida,
                borderRadius: 6,
            }
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: val => 'L. ' + val.toLocaleString()
                }
            }
        }
    }
});

// ── Gráfica 2: Flujo caja negocio ───────────────────
new Chart(document.getElementById('grafica-negocio'), {
    type: 'bar',
    data: {
        labels: flujoCajaNegocio.map(d => d.mes),
        datasets: [
            {
                label: 'Ingresos',
                data: flujoCajaNegocio.map(d => d.ingresos),
                backgroundColor: 'rgba(248, 150, 30, 0.8)',
                borderRadius: 6,
            },
            {
                label: 'Egresos',
                data: flujoCajaNegocio.map(d => d.egresos),
                backgroundColor: colorSalida,
                borderRadius: 6,
            }
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: val => 'L. ' + val.toLocaleString()
                }
            }
        }
    }
});

// ── Gráfica 3: Gastos por categoría ─────────────────
@if($gastosPorCategoria->isNotEmpty())
new Chart(document.getElementById('grafica-categorias'), {
    type: 'doughnut',
    data: {
        labels: gastosCategorias.map(d => d.nombre),
        datasets: [{
            data: gastosCategorias.map(d => d.total),
            backgroundColor: gastosCategorias.map(d => d.color),
            borderWidth: 2,
            borderColor: '#fff',
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom' },
            tooltip: {
                callbacks: {
                    label: ctx => ' L. ' + ctx.parsed.toLocaleString('es-HN', {minimumFractionDigits: 2})
                }
            }
        }
    }
});
@endif

// ── Gráfica 4: Ingresos negocio por área ────────────
new Chart(document.getElementById('grafica-areas'), {
    type: 'doughnut',
    data: {
        labels: ['Papelería', 'Impresiones', 'Recargas'],
        datasets: [{
            data: [
                ingresosPorArea.papeleria,
                ingresosPorArea.impresiones,
                ingresosPorArea.recargas,
            ],
            backgroundColor: [
                'rgba(248, 150, 30, 0.8)',
                'rgba(114, 9, 183, 0.8)',
                'rgba(76, 201, 240, 0.8)',
            ],
            borderWidth: 2,
            borderColor: '#fff',
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom' },
            tooltip: {
                callbacks: {
                    label: ctx => ' L. ' + ctx.parsed.toLocaleString('es-HN', {minimumFractionDigits: 2})
                }
            }
        }
    }
});
</script>
@endpush
