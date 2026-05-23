@extends('layouts.app')

@section('title', 'Nuevo movimiento')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card">
            <div class="card-body p-4">

                <a href="{{ route('movimientos-negocio.index') }}"
                   class="text-muted small d-inline-flex align-items-center mb-3">
                    <i class="bi bi-arrow-left me-1"></i> Volver
                </a>

                <h5 class="fw-semibold mb-4">Nuevo movimiento del negocio</h5>

                <form method="POST" action="{{ route('movimientos-negocio.store') }}">
                    @csrf

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Área</label>
                            <select name="area_id" class="form-select @error('area_id') is-invalid @enderror">
                                <option value="">Sin área</option>
                                @foreach($areas as $area)
                                    <option value="{{ $area->id }}" {{ old('area_id') == $area->id ? 'selected' : '' }}>
                                        {{ $area->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @if($areas->isEmpty())
                                <small class="text-muted">
                                    <a href="{{ route('areas-negocio.create') }}" target="_blank">
                                        Crear áreas del negocio
                                    </a>
                                </small>
                            @endif
                            @error('area_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Dirección</label>
                            <select name="direccion" class="form-select @error('direccion') is-invalid @enderror">
                                <option value="">Selecciona...</option>
                                <option value="entrada" {{ old('direccion') == 'entrada' ? 'selected' : '' }}>Entrada (ingreso)</option>
                                <option value="salida"  {{ old('direccion') == 'salida'  ? 'selected' : '' }}>Salida (egreso)</option>
                            </select>
                            @error('direccion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Tipo</label>
                            <select name="tipo" class="form-select @error('tipo') is-invalid @enderror">
                                <option value="">Selecciona...</option>
                                <optgroup label="Ingresos">
                                    <option value="venta_efectivo"  {{ old('tipo') == 'venta_efectivo'  ? 'selected' : '' }}>Venta efectivo</option>
                                    <option value="deposito_banco"  {{ old('tipo') == 'deposito_banco'  ? 'selected' : '' }}>Depósito banco</option>
                                </optgroup>
                                <optgroup label="Egresos">
                                    <option value="pago_empleado"      {{ old('tipo') == 'pago_empleado'      ? 'selected' : '' }}>Pago empleado</option>
                                    <option value="servicio_basico"    {{ old('tipo') == 'servicio_basico'    ? 'selected' : '' }}>Servicio básico</option>
                                    <option value="factura_proveedor"  {{ old('tipo') == 'factura_proveedor'  ? 'selected' : '' }}>Factura proveedor</option>
                                    <option value="gasto_insumos"      {{ old('tipo') == 'gasto_insumos'      ? 'selected' : '' }}>Gasto insumos</option>
                                    <option value="gasto_varios"       {{ old('tipo') == 'gasto_varios'       ? 'selected' : '' }}>Gasto varios</option>
                                </optgroup>
                                <optgroup label="Otros">
                                    <option value="prestamo_personal" {{ old('tipo') == 'prestamo_personal' ? 'selected' : '' }}>Préstamo personal</option>
                                    <option value="transferencia"     {{ old('tipo') == 'transferencia'     ? 'selected' : '' }}>Transferencia</option>
                                </optgroup>
                            </select>
                            @error('tipo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Cuenta</label>
                            <select name="cuenta_id" class="form-select @error('cuenta_id') is-invalid @enderror">
                                <option value="">Selecciona...</option>
                                @foreach($cuentas as $cuenta)
                                    <option value="{{ $cuenta->id }}" {{ old('cuenta_id') == $cuenta->id ? 'selected' : '' }}>
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
                            <label class="form-label fw-medium">Estado</label>
                            <select name="estado" class="form-select @error('estado') is-invalid @enderror">
                                <option value="pagado"    {{ old('estado', 'pagado') == 'pagado'    ? 'selected' : '' }}>Pagado</option>
                                <option value="pendiente" {{ old('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="cancelado" {{ old('estado') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                            </select>
                            @error('estado') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Fecha vencimiento <small class="text-muted">(facturas)</small></label>
                            <input type="date" name="fecha_vencimiento"
                                   class="form-control"
                                   value="{{ old('fecha_vencimiento') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Categoría <small class="text-muted">(opcional)</small></label>
                            <select name="categoria_id" class="form-select">
                                <option value="">Sin categoría</option>
                                @foreach($categorias as $cat)
                                    <option value="{{ $cat->id }}" {{ old('categoria_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->nombre }} ({{ ucfirst($cat->tipo) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Empleado <small class="text-muted">(pagos de nómina)</small></label>
                            <select name="empleado_id" class="form-select">
                                <option value="">Sin empleado</option>
                                @foreach($empleados as $emp)
                                    <option value="{{ $emp->id }}" {{ old('empleado_id') == $emp->id ? 'selected' : '' }}>
                                        {{ $emp->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-medium">Descripción</label>
                            <input type="text" name="descripcion"
                                   class="form-control"
                                   placeholder="Ej: Pago internet Tigo, Venta del día..."
                                   value="{{ old('descripcion') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Beneficiario <small class="text-muted">(opcional)</small></label>
                            <input type="text" name="beneficiario"
                                   class="form-control"
                                   placeholder="Ej: nombre del proveedor"
                                   value="{{ old('beneficiario') }}">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-medium">Notas <small class="text-muted">(opcional)</small></label>
                            <textarea name="notas" class="form-control" rows="2"
                                      placeholder="Cualquier detalle adicional...">{{ old('notas') }}</textarea>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-check-lg me-1"></i> Registrar movimiento
                            </button>
                        </div>

                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

@endsection
