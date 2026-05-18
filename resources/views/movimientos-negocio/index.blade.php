@extends('layouts.app')

@section('title', 'Movimientos del negocio')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">Registra ingresos y egresos de la papelería</p>
    <a href="{{ route('movimientos-negocio.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Nuevo movimiento
    </a>
</div>

{{-- Filtros --}}
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('movimientos-negocio.index') }}"
              class="row g-2 align-items-end">
            <div class="col-md-2">
                <label class="form-label small fw-medium mb-1">Área</label>
                <select name="area" class="form-select form-select-sm">
                    <option value="">Todas</option>
                    <option value="papeleria"   {{ request('area') == 'papeleria'   ? 'selected' : '' }}>Papelería</option>
                    <option value="impresiones" {{ request('area') == 'impresiones' ? 'selected' : '' }}>Impresiones</option>
                    <option value="compartido"  {{ request('area') == 'compartido'  ? 'selected' : '' }}>Compartido</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-medium mb-1">Estado</label>
                <select name="estado" class="form-select form-select-sm">
                    <option value="">Todos</option>
                    <option value="pagado"    {{ request('estado') == 'pagado'    ? 'selected' : '' }}>Pagado</option>
                    <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="cancelado" {{ request('estado') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-medium mb-1">Mes</label>
                <select name="mes" class="form-select form-select-sm">
                    <option value="">Todos</option>
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ request('mes') == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-medium mb-1">Año</label>
                <select name="anio" class="form-select form-select-sm">
                    <option value="">Todos</option>
                    @foreach(range(date('Y'), date('Y') - 3) as $y)
                        <option value="{{ $y }}" {{ request('anio') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-sm btn-primary w-100">
                    <i class="bi bi-funnel me-1"></i> Filtrar
                </button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('movimientos-negocio.index') }}" class="btn btn-sm btn-outline-secondary w-100">
                    <i class="bi bi-x-lg me-1"></i> Limpiar
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Resumen --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card card-stat h-100" style="border-color:#198754;">
            <div class="card-body">
                <p class="text-muted small mb-1">Total entradas</p>
                <h4 class="fw-bold text-success mb-0">L. {{ number_format($totalEntradas, 2) }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-stat h-100" style="border-color:#dc3545;">
            <div class="card-body">
                <p class="text-muted small mb-1">Total salidas</p>
                <h4 class="fw-bold text-danger mb-0">L. {{ number_format($totalSalidas, 2) }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-stat h-100" style="border-color:#0d6efd;">
            <div class="card-body">
                <p class="text-muted small mb-1">Balance</p>
                @php $balance = $totalEntradas - $totalSalidas; @endphp
                <h4 class="fw-bold {{ $balance >= 0 ? 'text-success' : 'text-danger' }} mb-0">
                    L. {{ number_format($balance, 2) }}
                </h4>
            </div>
        </div>
    </div>
</div>

{{-- Tabla --}}
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Fecha</th>
                    <th>Descripción</th>
                    <th>Área</th>
                    <th>Tipo</th>
                    <th>Cuenta</th>
                    <th>Estado</th>
                    <th class="text-end">Monto</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($movimientos as $mov)
                    <tr>
                        <td class="text-muted small">{{ $mov->fecha->format('d/m/Y') }}</td>
                        <td>
                            <span class="fw-medium">
                                {{ $mov->descripcion ?? ucfirst(str_replace('_', ' ', $mov->tipo)) }}
                            </span>
                            @if($mov->categoria)
                                <br>
                                <small class="text-muted">
                                    <span class="badge"
                                          style="background-color:{{ $mov->categoria->color }};">
                                        &nbsp;
                                    </span>
                                    {{ $mov->categoria->nombre }}
                                </small>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ ucfirst($mov->area) }}</span>
                        </td>
                        <td class="small text-muted">
                            {{ ucfirst(str_replace('_', ' ', $mov->tipo)) }}
                        </td>
                        <td class="small text-muted">{{ $mov->cuenta->nombre ?? '—' }}</td>
                        <td>
                            <span class="badge {{
                                $mov->estado === 'pagado'    ? 'bg-success' :
                                ($mov->estado === 'pendiente' ? 'bg-warning text-dark' : 'bg-secondary')
                            }}">
                                {{ ucfirst($mov->estado) }}
                            </span>
                        </td>
                        <td class="text-end fw-bold {{ $mov->direccion === 'entrada' ? 'text-success' : 'text-danger' }}">
                            {{ $mov->direccion === 'entrada' ? '+' : '-' }}L. {{ number_format($mov->monto, 2) }}
                        </td>
                        <td class="text-end">
                            <a href="{{ route('movimientos-negocio.edit', $mov) }}"
                               class="btn btn-sm btn-outline-primary me-1">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST"
                                  action="{{ route('movimientos-negocio.destroy', $mov) }}"
                                  class="d-inline"
                                  onsubmit="return confirm('¿Eliminar este movimiento?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5">
                            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                            No hay movimientos registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($movimientos->hasPages())
        <div class="card-footer">
            {{ $movimientos->links() }}
        </div>
    @endif
</div>

@endsection
