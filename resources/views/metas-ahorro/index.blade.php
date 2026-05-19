@extends('layouts.app')

@section('title', 'Metas de ahorro')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">Registra y da seguimiento a tus objetivos de ahorro</p>
    <a href="{{ route('metas-ahorro.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Nueva meta
    </a>
</div>

<div class="row g-3">
    @forelse($metas as $meta)
        <div class="col-md-4">
            <div class="card h-100 {{ $meta->estado === 'completada' ? 'border-success' : '' }}">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="fw-semibold mb-0">{{ $meta->nombre }}</h6>
                        <span class="badge {{
                            $meta->estado === 'completada' ? 'bg-success' :
                            ($meta->estado === 'cancelada'  ? 'bg-secondary' : 'bg-primary')
                        }}">
                            {{ ucfirst($meta->estado) }}
                        </span>
                    </div>

                    <small class="text-muted d-block mb-3">
                        {{ $meta->cuenta->nombre ?? '—' }}
                        @if($meta->fecha_limite)
                            · Límite: {{ $meta->fecha_limite->format('d/m/Y') }}
                        @endif
                    </small>

                    <div class="progress mb-2" style="height:10px;">
                        <div class="progress-bar {{ $meta->porcentaje >= 100 ? 'bg-success' : 'bg-primary' }}"
                             style="width:{{ min($meta->porcentaje, 100) }}%">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between small mb-3">
                        <span class="text-muted">
                            L. {{ number_format($meta->monto_actual, 2) }} ahorrado
                        </span>
                        <span class="fw-bold">{{ $meta->porcentaje }}%</span>
                    </div>

                    <div class="d-flex justify-content-between small mb-3">
                        <span class="text-muted">Objetivo:</span>
                        <span class="fw-bold">L. {{ number_format($meta->monto_objetivo, 2) }}</span>
                    </div>

                    @if($meta->falta > 0)
                        <div class="alert alert-light py-1 px-2 small mb-3">
                            Falta: <strong>L. {{ number_format($meta->falta, 2) }}</strong>
                        </div>
                    @endif

                    <div class="d-flex gap-2">
                        <a href="{{ route('metas-ahorro.edit', $meta) }}"
                           class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i> Actualizar
                        </a>
                        <form method="POST"
                              action="{{ route('metas-ahorro.destroy', $meta) }}"
                              class="d-inline"
                              onsubmit="return confirm('¿Eliminar esta meta?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5 text-muted">
            <i class="bi bi-piggy-bank fs-1 d-block mb-3"></i>
            No tienes metas de ahorro.
            <a href="{{ route('metas-ahorro.create') }}">Crear la primera</a>
        </div>
    @endforelse
</div>

@endsection
