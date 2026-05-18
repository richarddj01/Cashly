@extends('layouts.app')

@section('title', 'Deudas')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">Control de tus deudas personales</p>
    <a href="{{ route('deudas.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Nueva deuda
    </a>
</div>

{{-- Resumen --}}
<div class="card card-stat mb-4" style="border-color:#dc3545;">
    <div class="card-body">
        <p class="text-muted small mb-1">Total deuda pendiente</p>
        <h3 class="fw-bold text-danger mb-0">L. {{ number_format($totalPendiente, 2) }}</h3>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Acreedor</th>
                    <th>Tipo</th>
                    <th>Vencimiento</th>
                    <th>Progreso</th>
                    <th>Estado</th>
                    <th class="text-end">Pendiente</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($deudas as $deuda)
                    <tr>
                        <td class="fw-medium">{{ $deuda->acreedor }}</td>
                        <td><span class="badge bg-secondary">{{ ucfirst($deuda->tipo) }}</span></td>
                        <td class="small text-muted">
                            {{ $deuda->fecha_vencimiento?->format('d/m/Y') ?? '—' }}
                        </td>
                        <td style="min-width:120px;">
                            <div class="progress" style="height:6px;">
                                <div class="progress-bar bg-success"
                                     style="width:{{ $deuda->porcentaje }}%"></div>
                            </div>
                            <small class="text-muted">{{ $deuda->porcentaje }}% pagado</small>
                        </td>
                        <td>
                            <span class="badge {{
                                $deuda->estado === 'pagada'  ? 'bg-success' :
                                ($deuda->estado === 'vencida' ? 'bg-danger'  : 'bg-warning text-dark')
                            }}">
                                {{ ucfirst($deuda->estado) }}
                            </span>
                        </td>
                        <td class="text-end fw-bold text-danger">
                            L. {{ number_format($deuda->saldo_pendiente, 2) }}
                        </td>
                        <td class="text-end">
                            <a href="{{ route('deudas.edit', $deuda) }}"
                               class="btn btn-sm btn-outline-primary me-1">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST"
                                  action="{{ route('deudas.destroy', $deuda) }}"
                                  class="d-inline"
                                  onsubmit="return confirm('¿Eliminar esta deuda?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">
                            <i class="bi bi-credit-card fs-1 d-block mb-3"></i>
                            No tienes deudas registradas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($deudas->hasPages())
        <div class="card-footer">{{ $deudas->links() }}</div>
    @endif
</div>

@endsection
