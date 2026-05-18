@extends('layouts.app')

@section('title', 'Nueva categoría')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body p-4">

                <a href="{{ route('categorias.index') }}"
                   class="text-muted small d-inline-flex align-items-center mb-3">
                    <i class="bi bi-arrow-left me-1"></i> Volver
                </a>

                <h5 class="fw-semibold mb-4">Nueva categoría</h5>

                <form method="POST" action="{{ route('categorias.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-medium">Nombre</label>
                        <input type="text" name="nombre"
                               class="form-control @error('nombre') is-invalid @enderror"
                               placeholder="Ej: Alimentación, Transporte, Ventas..."
                               value="{{ old('nombre') }}">
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Tipo</label>
                        <select name="tipo" class="form-select @error('tipo') is-invalid @enderror">
                            <option value="">Selecciona...</option>
                            <option value="ingreso" {{ old('tipo') == 'ingreso' ? 'selected' : '' }}>Ingreso</option>
                            <option value="egreso"  {{ old('tipo') == 'egreso'  ? 'selected' : '' }}>Egreso</option>
                        </select>
                        @error('tipo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Contexto</label>
                        <select name="contexto" class="form-select @error('contexto') is-invalid @enderror">
                            <option value="">Selecciona...</option>
                            <option value="personal" {{ old('contexto') == 'personal' ? 'selected' : '' }}>Personal</option>
                            <option value="negocio"  {{ old('contexto') == 'negocio'  ? 'selected' : '' }}>Negocio</option>
                            <option value="ambos"    {{ old('contexto') == 'ambos'    ? 'selected' : '' }}>Ambos</option>
                        </select>
                        @error('contexto')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Color</label>
                        <div class="d-flex align-items-center gap-3">
                            <input type="color" name="color"
                                   class="form-control form-control-color"
                                   value="{{ old('color', '#4cc9f0') }}"
                                   style="width:60px;height:40px;">
                            <small class="text-muted">Elige un color para identificar la categoría</small>
                        </div>
                        @error('color')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium">
                            Ícono
                            <a href="https://icons.getbootstrap.com" target="_blank"
                               class="text-muted small ms-2">
                                <i class="bi bi-box-arrow-up-right"></i> Ver íconos disponibles
                            </a>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi" id="preview-icon"></i>
                            </span>
                            <input type="text" name="icono" id="icono-input"
                                   class="form-control @error('icono') is-invalid @enderror"
                                   placeholder="Ej: cart, house, phone"
                                   value="{{ old('icono') }}">
                            @error('icono')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="text-muted">Escribe el nombre del ícono de Bootstrap Icons sin el prefijo "bi-"</small>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check-lg me-1"></i> Crear categoría
                    </button>
                </form>

            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.getElementById('icono-input').addEventListener('input', function() {
        document.getElementById('preview-icon').className = 'bi bi-' + this.value;
    });
</script>
@endpush
