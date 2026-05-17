@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

{{-- Tarjetas de resumen --}}
<div class="row g-3 mb-4">

    {{-- Ingresos personales --}}
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

    {{-- Egresos personales --}}
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

    {{-- Balance negocio --}}
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

    {{-- Comisiones recargas --}}
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

<div class="row g-3 mb-4">

    {{-- Cuentas --}}
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

    {{-- Facturas pendientes --}}
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

    {{-- Últimos movimientos personales --}}
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="fw-semibold mb-3">
                    <i class="bi bi-clock-history me-2 text-info"></i>Últimos movimientos
                </h6>
                @forelse($ultimosMovimientos as $mov)
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <div>
                            <span class="fw-medium small">{{ $mov->descripcion ?? ucfirst(str_replace('_', ' ', $mov->tipo)) }}</span>
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
            Tienes <strong>L. {{ number_format($prestamosPendientes, 2) }}</strong> en préstamos pendientes entre tu cuenta personal y el negocio.
            <a href="{{ route('prestamos.index') }}" class="alert-link ms-2">Ver detalle →</a>
        </div>
    </div>
@endif

@endsection
