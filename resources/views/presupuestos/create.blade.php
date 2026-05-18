@extends('layouts.app')

@section('title', 'Nuevo presupuesto')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card">
            <div class="card-body p-4">

                <a href="{{ route('presupuestos.index') }}"
                   class="text-muted small d-inline-flex align-items-center mb-3">
                    <i class="bi bi-arrow-left me-1"></i> Volver
                </a>

                <h5 class="fw-semibold mb-4">Nuevo presupuesto</h5>

                <form method="POST" action="{{ route('presupuestos.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-medium">Categoría</label>
                        <select name="categoria_id"
                                class="form-select @error('categoria_id') is-invalid @enderror">
                            <option value="">Selecciona...</option>
                            @foreach($categorias as $cat)
                                <option value="{{ $cat->id }}" {{ old('categoria_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('categoria_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Monto límite</label>
                        <div class="input-group">
                            <span class="input-group-text">L.</span>
                            <input type="number" name="monto_limite" step="0.01" min="0.01"
                                   class="form-control @error('monto_limite') is-invalid @enderror"
                                   value="{{ old('monto_limite') }}" placeholder="0.00">
                            @error('monto_limite') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <label class="form-label fw-medium">Mes</label>
                            <select name="mes" class="form-select @error('mes') is-invalid @enderror">
                                @foreach(range(1, 12) as $m)
                                    <option value="{{ $m }}" {{ old('mes', date('n')) == $m ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                    </option>
                                @endforeach
                            </select>
                            @error('mes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-medium">Año</label>
                            <select name="anio" class="form-select @error('anio') is-invalid @enderror">
                                @foreach(range(date('Y'), date('Y') + 1) as $y)
                                    <option value="{{ $y }}" {{ old('anio', date('Y')) == $y ? 'selected' : '' }}>
                                        {{ $y }}
                                    </option>
                                @endforeach
                            </select>
                            @error('anio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check-lg me-1"></i> Guardar presupuesto
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
