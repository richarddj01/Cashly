@extends('layouts.app')

@section('title', 'Nueva distribuidora')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card">
            <div class="card-body p-4">

                <a href="{{ route('distribuidoras.index') }}"
                   class="text-muted small d-inline-flex align-items-center mb-3">
                    <i class="bi bi-arrow-left me-1"></i> Volver
                </a>

                <h5 class="fw-semibold mb-4">Nueva distribuidora</h5>

                <form method="POST" action="{{ route('distribuidoras.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-medium">Nombre</label>
                        <input type="text" name="nombre"
                               class="form-control @error('nombre') is-invalid @enderror"
                               placeholder="Ej: Tigo, Claro, Honduras"
                               value="{{ old('nombre') }}">
                        @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Saldo inicial</label>
                        <div class="input-group">
                            <span class="input-group-text">L.</span>
                            <input type="number" name="saldo_actual" step="0.01" min="0"
                                   class="form-control @error('saldo_actual') is-invalid @enderror"
                                   value="{{ old('saldo_actual', 0) }}" placeholder="0.00">
                            @error('saldo_actual') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <small class="text-muted">Saldo que ya tienes con esta distribuidora.</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium">Saldo mínimo para alerta</label>
                        <div class="input-group">
                            <span class="input-group-text">L.</span>
                            <input type="number" name="saldo_minimo" step="0.01" min="0"
                                   class="form-control @error('saldo_minimo') is-invalid @enderror"
                                   value="{{ old('saldo_minimo', 100) }}" placeholder="100.00">
                            @error('saldo_minimo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <small class="text-muted">Te avisaremos cuando el saldo baje de este monto.</small>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check-lg me-1"></i> Crear distribuidora
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
