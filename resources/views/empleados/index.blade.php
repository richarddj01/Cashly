@extends('layouts.app')

@section('title', 'Empleados')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">Administra los empleados del negocio</p>
    <a href="{{ route('empleados.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Nuevo empleado
    </a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nombre</th>
                    <th>Cargo</th>
                    <th>Fecha ingreso</th>
                    <th>Estado</th>
                    <th class="text-end">Salario</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($empleados as $empleado)
                    <tr>
                        <td class="fw-medium">{{ $empleado->nombre }}</td>
                        <td class="text-muted small">{{ $empleado->cargo ?? '—' }}</td>
                        <td class="text-muted small">{{ $empleado->fecha_ingreso->format('d/m/Y') }}</td>
                        <td>
                            <span class="badge {{ $empleado->activo ? 'bg-success' : 'bg-secondary' }}">
                                {{ $empleado->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td class="text-end fw-bold">L. {{ number_format($empleado->salario, 2) }}</td>
                        <td class="text-end">
                            <a href="{{ route('empleados.edit', $empleado) }}"
                               class="btn btn-sm btn-outline-primary me-1">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST"
                                  action="{{ route('empleados.destroy', $empleado) }}"
                                  class="d-inline"
                                  onsubmit="return confirm('¿Desactivar este empleado?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-eye-slash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                            <i class="bi bi-people fs-1 d-block mb-3"></i>
                            No tienes empleados registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
