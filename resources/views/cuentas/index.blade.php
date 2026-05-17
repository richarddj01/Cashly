@extends('layouts.app')

@section('title', 'Cuentas')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">Administra tus cuentas de dinero</p>
    <a href="{{ route('cuentas.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Nueva cuenta
    </a>
</div>

{{-- Personal --}}
<h6 class="text-muted fw-semibold mb-2 text-uppercase" style="font-size:0.75rem;letter-spacing:0.08em;">
    Personal
</h6>
<div class="row g-3 mb-4">
    @forelse($cuentas->where('contexto', 'personal') as $cuenta)
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h6 class="fw-semibold mb-0">{{ $cuenta->nombre }}</h6>
                            <small class="text-muted">{{ ucfirst($cuenta->tipo) }}</small>
                        </div>
                        <span class="badge {{ $cuenta->activa ? 'bg-success' : 'bg-secondary' }}">
                            {{ $cuenta->activa ? 'Activa' : 'Inactiva' }}
                        </span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Saldo actual</small>
                        <h4 class="fw-bold {{ $cuenta->saldo_actual >= 0 ? 'text-success' : 'text-danger' }} mb-0">
                            L. {{ number_format($cuenta->saldo_actual, 2) }}
                        </h4>
                        <small class="text-muted">Saldo inicial: L. {{ number_format($cuenta->saldo_inicial, 2) }}</small>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('cuentas.edit', $cuenta) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i> Editar
                        </a>
                        <form method="POST" action="{{ route('cuentas.destroy', $cuenta) }}">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-eye-slash"></i> Desactivar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <p class="text-muted small">No tienes cuentas personales.
                <a href="{{ route('cuentas.create') }}">Crear una</a>
            </p>
        </div>
    @endforelse
</div>

{{-- Negocio --}}
<h6 class="text-muted fw-semibold mb-2 text-uppercase" style="font-size:0.75rem;letter-spacing:0.08em;">
    Negocio
</h6>
<div class="row g-3">
    @forelse($cuentas->where('contexto', 'negocio') as $cuenta)
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h6 class="fw-semibold mb-0">{{ $cuenta->nombre }}</h6>
                            <small class="text-muted">{{ ucfirst($cuenta->tipo) }}</small>
                        </div>
                        <span class="badge {{ $cuenta->activa ? 'bg-success' : 'bg-secondary' }}">
                            {{ $cuenta->activa ? 'Activa' : 'Inactiva' }}
                        </span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Saldo actual</small>
                        <h4 class="fw-bold {{ $cuenta->saldo_actual >= 0 ? 'text-success' : 'text-danger' }} mb-0">
                            L. {{ number_format($cuenta->saldo_actual, 2) }}
                        </h4>
                        <small class="text-muted">Saldo inicial: L. {{ number_format($cuenta->saldo_inicial, 2) }}</small>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('cuentas.edit', $cuenta) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i> Editar
                        </a>
                        <form method="POST" action="{{ route('cuentas.destroy', $cuenta) }}">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-eye-slash"></i> Desactivar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <p class="text-muted small">No tienes cuentas del negocio.
                <a href="{{ route('cuentas.create') }}">Crear una</a>
            </p>
        </div>
    @endforelse
</div>

@endsection
