@extends('layouts.app')

@section('title', 'Distribuidoras de recargas')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">Administra tus distribuidoras y su saldo disponible</p>
    <a href="{{ route('distribuidoras.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Nueva distribuidora
    </a>
</div>

<div class="row g-3">
    @forelse($distribuidoras as $dist)
        <div class="col-md-4">
            <div class="card h-100 {{ $dist->tienesSaldoBajo() ? 'border-warning' : '' }}">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h6 class="fw-semibold mb-0">{{ $dist->nombre }}</h6>
                            <small class="text-muted">Saldo mínimo: L. {{ number_format($dist->saldo_minimo, 2) }}</small>
                        </div>
                        <span class="badge {{ $dist->activa ? 'bg-success' : 'bg-secondary' }}">
                            {{ $dist->activa ? 'Activa' : 'Inactiva' }}
                        </span>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">Saldo disponible</small>
                        <h4 class="fw-bold {{ $dist->tienesSaldoBajo() ? 'text-warning' : 'text-success' }} mb-0">
                            L. {{ number_format($dist->saldo_actual, 2) }}
                        </h4>
                        @if($dist->tienesSaldoBajo())
                            <small class="text-warning">
                                <i class="bi bi-exclamation-triangle me-1"></i>Saldo bajo — recarga pronto
                            </small>
                        @endif
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('recargas.create', ['distribuidora_id' => $dist->id]) }}"
                           class="btn btn-sm btn-success">
                            <i class="bi bi-phone me-1"></i> Vender recarga
                        </a>
                        <a href="{{ route('distribuidoras.edit', $dist) }}"
                           class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5 text-muted">
            <i class="bi bi-sim fs-1 d-block mb-3"></i>
            No tienes distribuidoras registradas.
            <a href="{{ route('distribuidoras.create') }}">Crear la primera</a>
        </div>
    @endforelse
</div>

@endsection
