@extends('layouts.app')

@section('title', 'Actualizar meta')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card">
            <div class="card-body p-4">

                <a href="{{ route('metas-ahorro.index') }}"
                   class="text-muted small d-inline-flex align-items-center mb-3">
                    <i class="bi bi-arrow-left me-1"></i> Volver
                </a>

                <h5 class="fw-semibold mb-1">Actualizar meta</h5>
                <p class="text-muted small mb-4">
                    {{ $metasAhorro->nombre }} —
                    Objetivo: L. {{ number_format($metasAhorro->monto_objetivo, 2) }}
                </p>

                <form method="POST" action="{{ route('metas-ahorro.update', $metasAhorro) }}">
                    @csrf @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-medium">Monto ahorrado hasta ahora</label>
                        <div class="input-group">
                            <span class="input-group-text">L.</span>
                            <input type="number" name="monto_actual" step="0.01" min="0"
                                   class="form-control @error('monto_actual') is-invalid @enderror"
                                   value="{{ old('monto_actual', $metasAhorro->monto_actual) }}">
                            @error('monto_actual') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium">Estado</label>
                        <select name="estado" class="form-select @error('estado') is-invalid @enderror">
                            <option value="activa"     {{ $metasAhorro->estado == 'activa'     ? 'selected' : '' }}>Activa</option>
                            <option value="completada" {{ $metasAhorro->estado == 'completada' ? 'selected' : '' }}>Completada</option>
                            <option value="cancelada"  {{ $metasAhorro->estado == 'cancelada'  ? 'selected' : '' }}>Cancelada</option>
                        </select>
                        @error('estado') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium">Notas</label>
                        <textarea name="notas" class="form-control" rows="2">{{ old('notas', $metasAhorro->notas) }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check-lg me-1"></i> Guardar cambios
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
