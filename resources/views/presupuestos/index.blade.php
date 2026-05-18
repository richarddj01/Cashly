@extends('layouts.app')

@section('title', 'Presupuestos')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">
        Presupuestos de {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}
    </p>
    <a href="{{ route('presupuestos.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Nuevo presupuesto
    </a>
</div>

<div class="row g-3">
    @forelse($presupuestos as $pres)
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h6 class="fw-semibold mb-0">{{ $pres->categoria->nombre }}</h6>
                            <small class="text-muted">Límite: L. {{ number_format($pres->monto_limite, 2) }}</small>
                        </div>
                        <span class="badge {{
                            $pres->porcentaje >= 100 ? 'bg-danger' :
                            ($pres->porcentaje >= 80  ? 'bg-warning text-dark' : 'bg-success')
                        }}">
                            {{ $pres->porcentaje }}%
                        </span>
                    </div>

                    <div class="progress mb-2" style="height:8px;">
                        <div class="progress-bar {{
                            $pres->porcentaje >= 100 ? 'bg-danger' :
                            ($pres->porcentaje >= 80  ? 'bg-warning' : 'bg-success')
                        }}"
                             style="width: {{ min($pres->porcentaje, 100) }}%">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between small">
                        <span class="text-muted">Gastado: <strong>L. {{ number_format($pres->gastado, 2) }}</strong></span>
                        <span class="{{ $pres->disponible >= 0 ? 'text-success' : 'text-danger' }}">
                            Disponible: <strong>L. {{ number_format($pres->disponible, 2) }}</strong>
                        </span>
                    </div>

                    <div class="mt-3">
                        <form method="POST" action="{{ route('presupuestos.destroy', $pres) }}"
                              class="d-inline"
                              onsubmit="return confirm('¿Eliminar este presupuesto?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i> Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5 text-muted">
            <i class="bi bi-pie-chart fs-1 d-block mb-3"></i>
            No tienes presupuestos para este mes.
            <a href="{{ route('presupuestos.create') }}">Crear el primero</a>
        </div>
    @endforelse
</div>

@endsection
