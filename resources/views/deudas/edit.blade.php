@extends('layouts.app')

@section('title', 'Actualizar deuda')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card">
            <div class="card-body p-4">

                <a href="{{ route('deudas.index') }}"
                   class="text-muted small d-inline-flex align-items-center mb-3">
                    <i class="bi bi-arrow-left me-1"></i> Volver
                </a>

                <h5 class="fw-semibold mb-1">Actualizar deuda</h5>
                <p class="text-muted small mb-4">{{ $deuda->acreedor }} — L. {{ number_format($deuda->monto_total, 2) }}</p>

                <form method="POST" action="{{ route('deudas.update', $deuda) }}">
                    @csrf @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-medium">Monto pagado hasta ahora</label>
                        <div class="input-group">
                            <span class="input-group-text">L.</span>
                            <input type="number" name="monto_pagado" step="0.01" min="0"
                                   class="form-control @error('monto_pagado') is-invalid @enderror"
                                   value="{{ old('monto_pagado', $deuda->monto_pagado) }}">
                            @error('monto_pagado') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <small class="text-muted">Total de la deuda: L. {{ number_format($deuda->monto_total, 2) }}</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium">Estado</label>
                        <select name="estado" class="form-select @error('estado') is-invalid @enderror">
                            <option value="activa"  {{ $deuda->estado == 'activa'  ? 'selected' : '' }}>Activa</option>
                            <option value="pagada"  {{ $deuda->estado == 'pagada'  ? 'selected' : '' }}>Pagada</option>
                            <option value="vencida" {{ $deuda->estado == 'vencida' ? 'selected' : '' }}>Vencida</option>
                        </select>
                        @error('estado') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium">Notas</label>
                        <textarea name="notas" class="form-control" rows="2">{{ old('notas', $deuda->notas) }}</textarea>
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
