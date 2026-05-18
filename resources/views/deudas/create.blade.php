@extends('layouts.app')

@section('title', 'Nueva deuda')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body p-4">

                <a href="{{ route('deudas.index') }}"
                   class="text-muted small d-inline-flex align-items-center mb-3">
                    <i class="bi bi-arrow-left me-1"></i> Volver
                </a>

                <h5 class="fw-semibold mb-4">Nueva deuda</h5>

                <form method="POST" action="{{ route('deudas.store') }}">
                    @csrf

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Acreedor</label>
                            <input type="text" name="acreedor"
                                   class="form-control @error('acreedor') is-invalid @enderror"
                                   placeholder="Ej: Banco, persona, tienda..."
                                   value="{{ old('acreedor') }}">
                            @error('acreedor') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Tipo</label>
                            <select name="tipo" class="form-select @error('tipo') is-invalid @enderror">
                                <option value="">Selecciona...</option>
                                <option value="prestamo" {{ old('tipo') == 'prestamo' ? 'selected' : '' }}>Préstamo</option>
                                <option value="tarjeta"  {{ old('tipo') == 'tarjeta'  ? 'selected' : '' }}>Tarjeta de crédito</option>
                                <option value="cuota"    {{ old('tipo') == 'cuota'    ? 'selected' : '' }}>Cuota / abono</option>
                                <option value="otro"     {{ old('tipo') == 'otro'     ? 'selected' : '' }}>Otro</option>
                            </select>
                            @error('tipo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Monto total</label>
                            <div class="input-group">
                                <span class="input-group-text">L.</span>
                                <input type="number" name="monto_total" step="0.01" min="0.01"
                                       class="form-control @error('monto_total') is-invalid @enderror"
                                       value="{{ old('monto_total') }}" placeholder="0.00">
                                @error('monto_total') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Interés % <small class="text-muted">(opcional)</small></label>
                            <div class="input-group">
                                <input type="number" name="interes" step="0.01" min="0"
                                       class="form-control"
                                       value="{{ old('interes', 0) }}" placeholder="0">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Fecha inicio</label>
                            <input type="date" name="fecha_inicio"
                                   class="form-control @error('fecha_inicio') is-invalid @enderror"
                                   value="{{ old('fecha_inicio', date('Y-m-d')) }}">
                            @error('fecha_inicio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Fecha vencimiento <small class="text-muted">(opcional)</small></label>
                            <input type="date" name="fecha_vencimiento"
                                   class="form-control"
                                   value="{{ old('fecha_vencimiento') }}">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-medium">Notas <small class="text-muted">(opcional)</small></label>
                            <textarea name="notas" class="form-control" rows="2">{{ old('notas') }}</textarea>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-check-lg me-1"></i> Registrar deuda
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
