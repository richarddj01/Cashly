@extends('layouts.app')

@section('title', 'Editar movimiento')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card">
            <div class="card-body p-4">

                <a href="{{ route('movimientos-negocio.index') }}"
                   class="text-muted small d-inline-flex align-items-center mb-3">
                    <i class="bi bi-arrow-left me-1"></i> Volver
                </a>

                <h5 class="fw-semibold mb-4">Editar movimiento</h5>

                <form method="POST" action="{{ route('movimientos-negocio.update', $movimientosNegocio) }}">
                    @csrf @method('PUT')

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Área</label>
                            <select name="area" class="form-select @error('area') is-invalid @enderror">
                                <option value="papeleria"   {{ $movimientosNegocio->area == 'papeleria'   ? 'selected' : '' }}>Papelería</option>
                                <option value="impresiones" {{ $movimientosNegocio->area == 'impresiones' ? 'selected' : '' }}>Impresiones</option>
                                <option value="compartido"  {{ $movimientosNegocio->area == 'compartido'  ? 'selected' : '' }}>Compartido</option>
                            </select>
                            @error('area') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Dirección</label>
                            <select name="direccion" class="form-select @error('direccion') is-invalid @enderror">
                                <option value="entrada" {{ $movimientosNegocio->direccion == 'entrada' ? 'selected' : '' }}>Entrada (ingreso)</option>
                                <option value="salida"  {{ $movimientosNegocio->direccion == 'salida'  ? 'selected' : '' }}>Salida (egreso)</option>
                            </select>
                            @error('direccion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Tipo</label>
                            <select name="tipo" class="form-select @error('tipo') is-invalid @enderror">
                                <optgroup label="Ingresos">
                                    <option value="venta_efectivo" {{ $movimientosNegocio->tipo == 'venta_efectivo' ? 'selected' : '' }}>Venta efectivo</option>
                                    <option value="deposito_banco" {{ $movimientosNegocio->tipo == 'deposito_banco' ? 'selected' : '' }}>Depósito banco</option>
                                </optgroup>
                                <optgroup label="Egresos">
                                    <option value="pago_empleado"     {{ $movimientosNegocio->tipo == 'pago_empleado'     ? 'selected' : '' }}>Pago empleado</option>
                                    <option value="servicio_basico"   {{ $movimientosNegocio->tipo == 'servicio_basico'   ? 'selected' : '' }}>Servicio básico</option>
                                    <option value="factura_proveedor" {{ $movimientosNegocio->tipo == 'factura_proveedor' ? 'selected' : '' }}>Factura proveedor</option>
                                    <option value="gasto_insumos"     {{ $movimientosNegocio->tipo == 'gasto_insumos'     ? 'selected' : '' }}>Gasto insumos</option>
                                    <option value="gasto_varios"      {{ $movimientosNegocio->tipo == 'gasto_varios'      ? 'selected' : '' }}>Gasto varios</option>
                                </optgroup>
                                <optgroup label="Otros">
                                    <option value="prestamo_personal" {{ $movimientosNegocio->tipo == 'prestamo_personal' ? 'selected' : '' }}>Préstamo personal</option>
                                    <option value="transferencia"     {{ $movimientosNegocio->tipo == 'transferencia'     ? 'selected' : '' }}>Transferencia</option>
                                </optgroup>
                            </select>
                            @error('tipo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Cuenta</label>
                            <select name="cuenta_id" class="form-select @error('cuenta_id') is-invalid @enderror">
                                @foreach($cuentas as $cuenta)
                                    <option value="{{ $cuenta->id }}" {{ $movimientosNegocio->cuenta_id == $cuenta->id ? 'selected' : '' }}>
                                        {{ $cuenta->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('cuenta_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Monto</label>
                            <div class="input-group">
                                <span class="input-group-text">L.</span>
                                <input type="number" name="monto" step="0.01" min="0.01"
                                       class="form-control @error('monto') is-invalid @enderror"
                                       value="{{ old('monto', $movimientosNegocio->monto) }}">
                                @error('monto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Fecha</label>
                            <input type="date" name="fecha"
                                   class="form-control @error('fecha') is-invalid @enderror"
                                   value="{{ old('fecha', $movimientosNegocio->fecha->format('Y-m-d')) }}">
                            @error('fecha') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Estado</label>
                            <select name="estado" class="form-select @error('estado') is-invalid @enderror">
                                <option value="pagado"    {{ $movimientosNegocio->estado == 'pagado'    ? 'selected' : '' }}>Pagado</option>
                                <option value="pendiente" {{ $movimientosNegocio->estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="cancelado" {{ $movimientosNegocio->estado == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                            </select>
                            @error('estado') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Fecha vencimiento</label>
                            <input type="date" name="fecha_vencimiento"
                                   class="form-control"
                                   value="{{ old('fecha_vencimiento', $movimientosNegocio->fecha_vencimiento?->format('Y-m-d')) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Categoría</label>
                            <select name="categoria_id" class="form-select">
                                <option value="">Sin categoría</option>
                                @foreach($categorias as $cat)
                                    <option value="{{ $cat->id }}" {{ $movimientosNegocio->categoria_id == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Empleado</label>
                            <select name="empleado_id" class="form-select">
                                <option value="">Sin empleado</option>
                                @foreach($empleados as $emp)
                                    <option value="{{ $emp->id }}" {{ $movimientosNegocio->empleado_id == $emp->id ? 'selected' : '' }}>
                                        {{ $emp->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-medium">Descripción</label>
                            <input type="text" name="descripcion" class="form-control"
                                   value="{{ old('descripcion', $movimientosNegocio->descripcion) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Beneficiario</label>
                            <input type="text" name="beneficiario" class="form-control"
                                   value="{{ old('beneficiario', $movimientosNegocio->beneficiario) }}">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-medium">Notas</label>
                            <textarea name="notas" class="form-control" rows="2">{{ old('notas', $movimientosNegocio->notas) }}</textarea>
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
