@extends('layouts.app')

@section('title', 'Editar empleado')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body p-4">

                <a href="{{ route('empleados.index') }}"
                   class="text-muted small d-inline-flex align-items-center mb-3">
                    <i class="bi bi-arrow-left me-1"></i> Volver
                </a>

                <h5 class="fw-semibold mb-4">Editar empleado</h5>

                <form method="POST" action="{{ route('empleados.update', $empleado) }}">
                    @csrf @method('PUT')

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Nombre</label>
                            <input type="text" name="nombre"
                                   class="form-control @error('nombre') is-invalid @enderror"
                                   value="{{ old('nombre', $empleado->nombre) }}">
                            @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Cargo</label>
                            <input type="text" name="cargo"
                                   class="form-control"
                                   value="{{ old('cargo', $empleado->cargo) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Salario base</label>
                            <div class="input-group">
                                <span class="input-group-text">L.</span>
                                <input type="number" name="salario" step="0.01" min="0"
                                       class="form-control @error('salario') is-invalid @enderror"
                                       value="{{ old('salario', $empleado->salario) }}">
                                @error('salario') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Fecha de ingreso</label>
                            <input type="date" name="fecha_ingreso"
                                   class="form-control @error('fecha_ingreso') is-invalid @enderror"
                                   value="{{ old('fecha_ingreso', $empleado->fecha_ingreso->format('Y-m-d')) }}">
                            @error('fecha_ingreso') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12 form-check ms-2">
                            <input type="checkbox" name="activo" class="form-check-input"
                                   id="activo" value="1" {{ $empleado->activo ? 'checked' : '' }}>
                            <label class="form-check-label" for="activo">Activo</label>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-check-lg me-1"></i> Guardar cambios
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
