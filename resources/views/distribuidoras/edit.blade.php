@extends('layouts.app')

@section('title', 'Editar distribuidora')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card">
            <div class="card-body p-4">

                <a href="{{ route('distribuidoras.index') }}"
                   class="text-muted small d-inline-flex align-items-center mb-3">
                    <i class="bi bi-arrow-left me-1"></i> Volver
                </a>

                <h5 class="fw-semibold mb-4">Editar distribuidora</h5>

                <form method="POST" action="{{ route('distribuidoras.update', $distribuidora) }}">
                    @csrf @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-medium">Nombre</label>
                        <input type="text" name="nombre"
                               class="form-control @error('nombre') is-invalid @enderror"
                               value="{{ old('nombre', $distribuidora->nombre) }}">
                        @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Saldo mínimo para alerta</label>
                        <div class="input-group">
                            <span class="input-group-text">L.</span>
                            <input type="number" name="saldo_minimo" step="0.01" min="0"
                                   class="form-control @error('saldo_minimo') is-invalid @enderror"
                                   value="{{ old('saldo_minimo', $distribuidora->saldo_minimo) }}">
                            @error('saldo_minimo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-4 form-check">
                        <input type="checkbox" name="activa" class="form-check-input"
                               id="activa" value="1" {{ $distribuidora->activa ? 'checked' : '' }}>
                        <label class="form-check-label" for="activa">Activa</label>
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
