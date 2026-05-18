@extends('layouts.app')

@section('title', 'Nuevo préstamo')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body p-4">

                <a href="{{ route('prestamos.index') }}"
                   class="text-muted small d-inline-flex align-items-center mb-3">
                    <i class="bi bi-arrow-left me-1"></i> Volver
                </a>

                <h5 class="fw-semibold mb-1">Nuevo préstamo</h5>
                <p class="text-muted small mb-4">
                    Se registrará automáticamente en ambos módulos (personal y negocio).
                </p>

                <form method="POST" action="{{ route('prestamos.store') }}">
                    @csrf

                    <div class="row g-3">

                        <div class="col-12">
                            <label class="form-label fw-medium">Dirección del préstamo</label>
                            <select name="direccion"
                                    class="form-select @error('direccion') is-invalid @enderror">
                                <option value="">Selecciona...</option>
                                <option value="personal_a_negocio" {{ old('direccion') == 'personal_a_negocio' ? 'selected' : '' }}>
                                    Personal → Negocio (saco de mi bolsillo para el negocio)
                                </option>
                                <option value="negocio_a_personal" {{ old('direccion') == 'negocio_a_personal' ? 'selected' : '' }}>
                                    Negocio → Personal (saco del negocio para uso personal)
                                </option>
                            </select>
                            @error('direccion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Cuenta personal</label>
                            <select name="cuenta_personal_id"
                                    class="form-select @error('cuenta_personal_id') is-invalid @enderror">
                                <option value="">Selecciona...</option>
                                @foreach($cuentasPersonales as $cuenta)
                                    <option value="{{ $cuenta->id }}" {{ old('cuenta_personal_id') == $cuenta->id ? 'selected' : '' }}>
                                        {{ $cuenta->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('cuenta_personal_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Cuenta del negocio</label>
                            <select name="cuenta_negocio_id"
                                    class="form-select @error('cuenta_negocio_id') is-invalid @enderror">
                                <option value="">Selecciona...</option>
                                @foreach($cuentasNegocio as $cuenta)
                                    <option value="{{ $cuenta->id }}" {{ old('cuenta_negocio_id') == $cuenta->id ? 'selected' : '' }}>
                                        {{ $cuenta->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('cuenta_negocio_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Monto</label>
                            <div class="input-group">
                                <span class="input-group-text">L.</span>
                                <input type="number" name="monto" step="0.01" min="0.01"
                                       class="form-control @error('monto') is-invalid @enderror"
                                       value="{{ old('monto') }}" placeholder="0.00">
                                @error('monto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Fecha</label>
                            <input type="date" name="fecha"
                                   class="form-control @error('fecha') is-invalid @enderror"
                                   value="{{ old('fecha', date('Y-m-d')) }}">
                            @error('fecha') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Fecha devolución <small class="text-muted">(opcional)</small></label>
                            <input type="date" name="fecha_devolucion"
                                   class="form-control"
                                   value="{{ old('fecha_devolucion') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Motivo <small class="text-muted">(opcional)</small></label>
                            <input type="text" name="motivo" class="form-control"
                                   placeholder="Ej: Pago proveedor, Cubrir caja..."
                                   value="{{ old('motivo') }}">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-medium">Notas <small class="text-muted">(opcional)</small></label>
                            <textarea name="notas" class="form-control" rows="2">{{ old('notas') }}</textarea>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-check-lg me-1"></i> Registrar préstamo
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
