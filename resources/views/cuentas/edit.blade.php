@extends('layouts.app')

@section('title', 'Editar cuenta')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body p-4">

                <a href="{{ route('cuentas.index') }}" class="text-muted small d-inline-flex align-items-center mb-3">
                    <i class="bi bi-arrow-left me-1"></i> Volver
                </a>

                <h5 class="fw-semibold mb-4">Editar cuenta</h5>

                <form method="POST" action="{{ route('cuentas.update', $cuenta) }}">
                    @csrf @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-medium">Nombre</label>
                        <input type="text" name="nombre"
                               class="form-control @error('nombre') is-invalid @enderror"
                               value="{{ old('nombre', $cuenta->nombre) }}">
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Tipo</label>
                        <select name="tipo" class="form-select @error('tipo') is-invalid @enderror">
                            <option value="efectivo" {{ $cuenta->tipo == 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                            <option value="banco"    {{ $cuenta->tipo == 'banco'    ? 'selected' : '' }}>Banco</option>
                            <option value="digital"  {{ $cuenta->tipo == 'digital'  ? 'selected' : '' }}>Digital</option>
                        </select>
                        @error('tipo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Contexto</label>
                        <select name="contexto" class="form-select @error('contexto') is-invalid @enderror">
                            <option value="personal" {{ $cuenta->contexto == 'personal' ? 'selected' : '' }}>Personal</option>
                            <option value="negocio"  {{ $cuenta->contexto == 'negocio'  ? 'selected' : '' }}>Negocio</option>
                        </select>
                        @error('contexto')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium">Saldo inicial</label>
                        <div class="input-group">
                            <span class="input-group-text">L.</span>
                            <input type="number" name="saldo_inicial" step="0.01" min="0"
                                   class="form-control @error('saldo_inicial') is-invalid @enderror"
                                   value="{{ old('saldo_inicial', $cuenta->saldo_inicial) }}">
                            @error('saldo_inicial')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
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
