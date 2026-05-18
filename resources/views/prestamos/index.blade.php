@extends('layouts.app')

@section('title', 'Préstamos')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">Control de préstamos entre tu cuenta personal y el negocio</p>
    <a href="{{ route('prestamos.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Nuevo préstamo
    </a>
</div>

{{-- Resumen --}}
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card card-stat h-100" style="border-color:#7209b7;">
            <div class="card-body">
                <p class="text-muted small mb-1">El negocio te debe</p>
                <h4 class="fw-bold mb-0" style="color:#7209b7;">
                    L. {{ number_format($pendientePersonalANegocio, 2) }}
                </h4>
                <small class="text-muted">Préstamos personal → negocio pendientes</small>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card card-stat h-100" style="border-color:#f8961e;">
            <div class="card-body">
                <p class="text-muted small mb-1">Tú le debes al negocio</p>
                <h4 class="fw-bold mb-0" style="color:#f8961e;">
                    L. {{ number_format($pendienteNegocioAPersonal, 2) }}
                </h4>
                <small class="text-muted">Préstamos negocio → personal pendientes</small>
            </div>
        </div>
    </div>
</div>

{{-- Tabla --}}
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Fecha</th>
                    <th>Dirección</th>
                    <th>Motivo</th>
                    <th>Devolución</th>
                    <th>Estado</th>
                    <th class="text-end">Monto</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($prestamos as $prestamo)
                    <tr>
                        <td class="text-muted small">{{ $prestamo->fecha->format('d/m/Y') }}</td>
                        <td>
                            @if($prestamo->direccion === 'personal_a_negocio')
                                <span class="badge bg-primary">
                                    <i class="bi bi-arrow-right me-1"></i>Personal → Negocio
                                </span>
                            @else
                                <span class="badge bg-warning text-dark">
                                    <i class="bi bi-arrow-left me-1"></i>Negocio → Personal
                                </span>
                            @endif
                        </td>
                        <td class="small">{{ $prestamo->motivo ?? '—' }}</td>
                        <td class="small text-muted">
                            {{ $prestamo->fecha_devolucion?->format('d/m/Y') ?? '—' }}
                        </td>
                        <td>
                            <span class="badge {{
                                $prestamo->estado === 'devuelto'  ? 'bg-success' :
                                ($prestamo->estado === 'pendiente' ? 'bg-warning text-dark' : 'bg-secondary')
                            }}">
                                {{ ucfirst($prestamo->estado) }}
                            </span>
                        </td>
                        <td class="text-end fw-bold">L. {{ number_format($prestamo->monto, 2) }}</td>
                        <td class="text-end">
                            @if($prestamo->estado === 'pendiente')
                                <form method="POST"
                                      action="{{ route('prestamos.update', $prestamo) }}"
                                      class="d-inline">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="estado" value="devuelto">
                                    <button class="btn btn-sm btn-success me-1"
                                            onclick="return confirm('¿Marcar como devuelto?')">
                                        <i class="bi bi-check-lg"></i> Devuelto
                                    </button>
                                </form>
                            @endif
                            <form method="POST"
                                  action="{{ route('prestamos.destroy', $prestamo) }}"
                                  class="d-inline"
                                  onsubmit="return confirm('¿Eliminar este préstamo?')">
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
                            <i class="bi bi-arrow-down-up fs-1 d-block mb-3"></i>
                            No hay préstamos registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($prestamos->hasPages())
        <div class="card-footer">{{ $prestamos->links() }}</div>
    @endif
</div>

@endsection
