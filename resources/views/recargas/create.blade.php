@extends('layouts.app')

@section('title', 'Nueva recarga')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body p-4">

                <a href="{{ route('recargas.index') }}"
                   class="text-muted small d-inline-flex align-items-center mb-3">
                    <i class="bi bi-arrow-left me-1"></i> Volver
                </a>

                <h5 class="fw-semibold mb-4">Registrar recarga</h5>

                <form method="POST" action="{{ route('recargas.store') }}">
                    @csrf

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Tipo</label>
                            <select name="tipo" id="tipo" class="form-select @error('tipo') is-invalid @enderror">
                                <option value="">Selecciona...</option>
                                <option value="venta"    {{ old('tipo') == 'venta'    ? 'selected' : '' }}>Venta de recarga</option>
                                <option value="deposito" {{ old('tipo') == 'deposito' ? 'selected' : '' }}>Depósito a distribuidora</option>
                                <option value="ajuste"   {{ old('tipo') == 'ajuste'   ? 'selected' : '' }}>Ajuste de saldo</option>
                            </select>
                            @error('tipo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Distribuidora</label>
                            <select name="distribuidora_id" class="form-select @error('distribuidora_id') is-invalid @enderror">
                                <option value="">Selecciona...</option>
                                @foreach($distribuidoras as $dist)
                                    <option value="{{ $dist->id }}"
                                            data-saldo="{{ $dist->saldo_actual }}"
                                            {{ old('distribuidora_id', request('distribuidora_id')) == $dist->id ? 'selected' : '' }}>
                                        {{ $dist->nombre }} — L. {{ number_format($dist->saldo_actual, 2) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('distribuidora_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Monto de la recarga</label>
                            <div class="input-group">
                                <span class="input-group-text">L.</span>
                                <input type="number" name="monto" step="0.01" min="0.01"
                                       class="form-control @error('monto') is-invalid @enderror"
                                       value="{{ old('monto') }}" placeholder="0.00">
                                @error('monto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="col-md-6" id="comision-field">
                            <label class="form-label fw-medium">Comisión ganada</label>
                            <div class="input-group">
                                <span class="input-group-text">L.</span>
                                <input type="number" name="comision" step="0.01" min="0"
                                       class="form-control @error('comision') is-invalid @enderror"
                                       value="{{ old('comision', 0) }}" placeholder="0.00">
                                @error('comision') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <small class="text-muted">Cuánto ganas tú por esta venta.</small>
                        </div>

                        <div class="col-md-6" id="telefono-field">
                            <label class="form-label fw-medium">Teléfono recargado</label>
                            <input type="text" name="numero_destino"
                                   class="form-control @error('numero_destino') is-invalid @enderror"
                                   placeholder="Ej: 9999-1234"
                                   value="{{ old('numero_destino') }}">
                            @error('numero_destino') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Cuenta</label>
                            <select name="cuenta_id" class="form-select">
                                <option value="">Sin cuenta</option>
                                @foreach($cuentas as $cuenta)
                                    <option value="{{ $cuenta->id }}" {{ old('cuenta_id') == $cuenta->id ? 'selected' : '' }}>
                                        {{ $cuenta->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Cuenta donde entra la comisión o sale el depósito.</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Fecha</label>
                            <input type="date" name="fecha"
                                   class="form-control @error('fecha') is-invalid @enderror"
                                   value="{{ old('fecha', date('Y-m-d')) }}">
                            @error('fecha') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-medium">Notas <small class="text-muted">(opcional)</small></label>
                            <textarea name="notas" class="form-control" rows="2"
                                      placeholder="Cualquier detalle adicional...">{{ old('notas') }}</textarea>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-check-lg me-1"></i> Registrar
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Mostrar/ocultar campos según el tipo
    document.getElementById('tipo').addEventListener('change', function() {
        const esVenta = this.value === 'venta';
        document.getElementById('comision-field').style.display = esVenta ? 'block' : 'none';
        document.getElementById('telefono-field').style.display = esVenta ? 'block' : 'none';
    });
</script>
@endpush
