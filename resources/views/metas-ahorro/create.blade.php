@extends('layouts.app')

@section('title', 'Nueva meta de ahorro')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body p-4">

                <a href="{{ route('metas-ahorro.index') }}"
                   class="text-muted small d-inline-flex align-items-center mb-3">
                    <i class="bi bi-arrow-left me-1"></i> Volver
                </a>

                <h5 class="fw-semibold mb-4">Nueva meta de ahorro</h5>

                <form method="POST" action="{{ route('metas-ahorro.store') }}">
                    @csrf

                    <div class="row g-3">

                        <div class="col-12">
                            <label class="form-label fw-medium">Nombre de la meta</label>
                            <input type="text" name="nombre"
                                   class="form-control @error('nombre') is-invalid @enderror"
                                   placeholder="Ej: Vacaciones, Laptop nueva, Fondo de emergencia..."
                                   value="{{ old('nombre') }}">
                            @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Cuenta</label>
                            <select name="cuenta_id"
                                    class="form-select @error('cuenta_id') is-invalid @enderror">
                                <option value="">Selecciona...</option>
                                @foreach($cuentas as $cuenta)
                                    <option value="{{ $cuenta->id }}" {{ old('cuenta_id') == $cuenta->id ? 'selected' : '' }}>
                                        {{ $cuenta->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('cuenta_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Monto objetivo</label>
                            <div class="input-group">
                                <span class="input-group-text">L.</span>
                                <input type="number" name="monto_objetivo" step="0.01" min="0.01"
                                       class="form-control @error('monto_objetivo') is-invalid @enderror"
                                       value="{{ old('monto_objetivo') }}" placeholder="0.00">
                                @error('monto_objetivo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Ya tengo ahorrado <small class="text-muted">(opcional)</small></label>
                            <div class="input-group">
                                <span class="input-group-text">L.</span>
                                <input type="number" name="monto_actual" step="0.01" min="0"
                                       class="form-control"
                                       value="{{ old('monto_actual', 0) }}" placeholder="0.00">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Fecha límite <small class="text-muted">(opcional)</small></label>
                            <input type="date" name="fecha_limite"
                                   class="form-control"
                                   value="{{ old('fecha_limite') }}">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-medium">Notas <small class="text-muted">(opcional)</small></label>
                            <textarea name="notas" class="form-control" rows="2">{{ old('notas') }}</textarea>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-check-lg me-1"></i> Crear meta
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
