@extends('layouts.app')

@section('title', 'Editar área')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card">
            <div class="card-body p-4">

                <a href="{{ route('areas-negocio.index') }}"
                   class="text-muted small d-inline-flex align-items-center mb-3">
                    <i class="bi bi-arrow-left me-1"></i> Volver
                </a>

                <h5 class="fw-semibold mb-4">Editar área</h5>

                <form method="POST" action="{{ route('areas-negocio.update', $areasNegocio) }}">
                    @csrf @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-medium">Nombre</label>
                        <input type="text" name="nombre"
                               class="form-control @error('nombre') is-invalid @enderror"
                               value="{{ old('nombre', $areasNegocio->nombre) }}">
                        @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Color</label>
                        <div class="d-flex align-items-center gap-3">
                            <input type="color" name="color"
                                   class="form-control form-control-color"
                                   value="{{ old('color', $areasNegocio->color) }}"
                                   style="width:60px;height:40px;">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Ícono</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-{{ $areasNegocio->icono }}" id="preview-icon"></i>
                            </span>
                            <input type="text" name="icono" id="icono-input"
                                   class="form-control"
                                   value="{{ old('icono', $areasNegocio->icono) }}">
                        </div>
                    </div>

                    <div class="mb-4 form-check">
                        <input type="checkbox" name="activa" class="form-check-input"
                               id="activa" value="1"
                               {{ $areasNegocio->activa ? 'checked' : '' }}>
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

@push('scripts')
<script>
    document.getElementById('icono-input').addEventListener('input', function() {
        document.getElementById('preview-icon').className = 'bi bi-' + this.value;
    });
</script>
@endpush
