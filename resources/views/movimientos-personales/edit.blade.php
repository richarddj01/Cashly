@extends('layouts.app')

@section('title', 'Editar movimiento personal')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card">
            <div class="card-body p-4">

                <a href="{{ route('movimientos-personales.index') }}"
                   class="text-muted small d-inline-flex align-items-center mb-3">
                    <i class="bi bi-arrow-left me-1"></i> Volver
                </a>

                <h5 class="fw-semibold mb-4">Editar movimiento personal</h5>

                <form method="POST" action="{{ route('movimientos-personales.update', $movimientosPersonale) }}">
                    @csrf @method('PUT')

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Dirección</label>
                            <select name="direccion" class="form-select @error('direccion') is-invalid @enderror">
                                <option value="entrada" {{ $movimientosPersonale->direccion == 'entrada' ? 'selected' : '' }}>Entrada (ingreso)</option>
                                <option value="salida"  {{ $movimientosPersonale->direccion == 'salida'  ? 'selected' : '' }}>Salida (gasto)</option>
                            </select>
                            @error('direccion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Tipo</label>
                            <select name="tipo" class="form-select @error('tipo') is-invalid @enderror">
                                <optgroup label="Ingresos">
                                    <option value="salario"          {{ $movimientosPersonale->tipo == 'salario'          ? 'selected' : '' }}>Salario</option>
                                    <option value="freelance"        {{ $movimientosPersonale->tipo == 'freelance'        ? 'selected' : '' }}>Freelance</option>
                                    <option value="otro_ingreso"     {{ $movimientosPersonale->tipo == 'otro_ingreso'     ? 'selected' : '' }}>Otro ingreso</option>
                                </optgroup>
                                <optgroup label="Egresos">
                                    <option value="gasto_hogar"      {{ $movimientosPersonale->tipo == 'gasto_hogar'      ? 'selected' : '' }}>Gasto hogar</option>
                                    <option value="servicio"         {{ $movimientosPersonale->tipo == 'servicio'         ? 'selected' : '' }}>Servicio</option>
                                    <option value="deuda"            {{ $movimientosPersonale->tipo == 'deuda'            ? 'selected' : '' }}>Pago deuda</option>
                                    <option value="ahorro"           {{ $movimientosPersonale->tipo == 'ahorro'           ? 'selected' : '' }}>Ahorro</option>
                                    <option value="gasto_varios"     {{ $movimientosPersonale->tipo == 'gasto_varios'     ? 'selected' : '' }}>Gasto varios</option>
                                </optgroup>
                                <optgroup label="Otros">
                                    <option value="prestamo_negocio" {{ $movimientosPersonale->tipo == 'prestamo_negocio' ? 'selected' : '' }}>Préstamo al negocio</option>
                                </optgroup>
                            </select>
                            @error('tipo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Cuenta</label>
                            <select name="cuenta_id" class="form-select @error('cuenta_id') is-invalid @enderror">
                                @foreach($cuentas as $cuenta)
                                    <option value="{{ $cuenta->id }}" {{ $movimientosPersonale->cuenta_id == $cuenta->id ? 'selected' : '' }}>
                                        {{ $cuenta->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('cuenta_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Categoría</label>
                            <select name="categoria_id" class="form-select">
                                <option value="">Sin categoría</option>
                                @foreach($categorias as $cat)
                                    <option value="{{ $cat->id }}" {{ $movimientosPersonale->categoria_id == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Monto</label>
                            <div class="input-group">
                                <span class="input-group-text">L.</span>
                                <input type="number" name="monto" step="0.01" min="0.01"
                                       class="form-control @error('monto') is-invalid @enderror"
                                       value="{{ old('monto', $movimientosPersonale->monto) }}">
                                @error('monto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Fecha</label>
                            <input type="date" name="fecha"
                                   class="form-control @error('fecha') is-invalid @enderror"
                                   value="{{ old('fecha', $movimientosPersonale->fecha->format('Y-m-d')) }}">
                            @error('fecha') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Estado</label>
                            <select name="estado" class="form-select @error('estado') is-invalid @enderror">
                                <option value="pagado"    {{ $movimientosPersonale->estado == 'pagado'    ? 'selected' : '' }}>Pagado</option>
                                <option value="pendiente" {{ $movimientosPersonale->estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="cancelado" {{ $movimientosPersonale->estado == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                            </select>
                            @error('estado') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-medium">Descripción</label>
                            <input type="text" name="descripcion" class="form-control"
                                   value="{{ old('descripcion', $movimientosPersonale->descripcion) }}">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-medium">Notas</label>
                            <textarea name="notas" class="form-control" rows="2">{{ old('notas', $movimientosPersonale->notas) }}</textarea>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-check-lg me-1"></i> Guardar cambios
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
