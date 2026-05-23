@extends('layouts.app')

@section('title', 'Nueva área')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card">
            <div class="card-body p-4">

                <a href="{{ route('areas-negocio.index') }}"
                   class="text-muted small d-inline-flex align-items-center mb-3">
                    <i class="bi bi-arrow-left me-1"></i> Volver
                </a>

                <h5 class="fw-semibold mb-1">Nueva área del negocio</h5>
                <p class="text-muted small mb-4">
                    Ejemplos: Papelería, Impresiones, Cocina, Bar, Diseño...
                </p>

                <form method="POST" action="{{ route('areas-negocio.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-medium">Nombre</label>
                        <input type="text" name="nombre"
                               class="form-control @error('nombre') is-invalid @enderror"
                               placeholder="Ej: Papelería, Cocina, Diseño..."
                               value="{{ old('nombre') }}">
                        @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Color</label>
                        <div class="d-flex align-items-center gap-3">
                            <input type="color" name="color"
                                   class="form-control form-control-color"
                                   value="{{ old('color', '#4cc9f0') }}"
                                   style="width:60px;height:40px;">
                            <small class="text-muted">Color para identificar el área en reportes</small>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium">
                            Ícono
                            <a href="https://icons.getbootstrap.com" target="_blank"
                               class="text-muted small ms-2">
                                <i class="bi bi-box-arrow-up-right"></i> Ver íconos
                            </a>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi" id="preview-icon"></i>
                            </span>
                            <input type="text" name="icono" id="icono-input"
                                   class="form-control"
                                   placeholder="Ej: shop, cup-hot, printer"
                                   value="{{ old('icono') }}">
                        </div>
                        <small class="text-muted">Nombre del ícono Bootstrap sin el prefijo "bi-"</small>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check-lg me-1"></i> Crear área
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
