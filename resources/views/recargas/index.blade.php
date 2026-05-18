@extends('layouts.app')

@section('title', 'Recargas')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">Historial de ventas y depósitos de recargas</p>
    <div class="d-flex gap-2">
        <a href="{{ route('distribuidoras.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-sim me-1"></i> Distribuidoras
        </a>
        <a href="{{ route('recargas.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Nueva recarga
        </a>
    </div>
</div>

{{-- Resumen del mes --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card card-stat h-100" style="border-color:#7209b7;">
            <div class="card-body">
                <p class="text-muted small mb-1">Ventas este mes</p>
                <h4 class="fw-bold mb-0" style="color:#7209b7;">{{ $ventasMes }}</h4>
                <small class="text-muted">recargas vendidas</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-stat h-100" style="border-color:#198754;">
            <div class="card-body">
                <p class="text-muted small mb-1">Comisiones este mes</p>
                <h4 class="fw-bold text-success mb-0">L. {{ number_format($comisionesmes, 2) }}</h4>
                <small class="text-muted">ganancia neta</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-stat h-100" style="border-color:#4cc9f0;">
            <div class="card-body">
                <p class="text-muted small mb-1">Saldos disponibles</p>
                @foreach($distribuidoras->where('activa', true) as $dist)
                    <div class="d-flex justify-content-between">
                        <small>{{ $dist->nombre }}</small>
                        <small class="fw-bold {{ $dist->tienesSaldoBajo() ? 'text-warning' : 'text-success' }}">
                            L. {{ number_format($dist->saldo_actual, 2) }}
                        </small>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- Filtros --}}
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('recargas.index') }}" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-medium mb-1">Distribuidora</label>
                <select name="distribuidora_id" class="form-select form-select-sm">
                    <option value="">Todas</option>
                    @foreach($distribuidoras as $dist)
                        <option value="{{ $dist->id }}" {{ request('distribuidora_id') == $dist->id ? 'selected' : '' }}>
                            {{ $dist->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-medium mb-1">Tipo</label>
                <select name="tipo" class="form-select form-select-sm">
                    <option value="">Todos</option>
                    <option value="venta"   {{ request('tipo') == 'venta'   ? 'selected' : '' }}>Venta</option>
                    <option value="deposito"{{ request('tipo') == 'deposito'? 'selected' : '' }}>Depósito</option>
                    <option value="ajuste"  {{ request('tipo') == 'ajuste'  ? 'selected' : '' }}>Ajuste</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-medium mb-1">Mes</label>
                <select name="mes" class="form-select form-select-sm">
                    <option value="">Todos</option>
                    @foreach(range(1,12) as $m)
                        <option value="{{ $m }}" {{ request('mes') == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-primary w-100">
                    <i class="bi bi-funnel me-1"></i> Filtrar
                </button>
                <a href="{{ route('recargas.index') }}" class="btn btn-sm btn-outline-secondary w-100">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Tabla --}}
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Fecha</th>
                    <th>Distribuidora</th>
                    <th>Tipo</th>
                    <th>Teléfono</th>
                    <th class="text-end">Monto</th>
                    <th class="text-end">Comisión</th>
                    <th class="text-end">Saldo después</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($movimientos as $mov)
                    <tr>
                        <td class="text-muted small">{{ $mov->fecha->format('d/m/Y') }}</td>
                        <td>{{ $mov->distribuidora->nombre ?? '—' }}</td>
                        <td>
                            <span class="badge {{
                                $mov->tipo === 'venta'    ? 'bg-success' :
                                ($mov->tipo === 'deposito' ? 'bg-primary' : 'bg-secondary')
                            }}">
                                {{ ucfirst($mov->tipo) }}
                            </span>
                        </td>
                        <td class="text-muted small">{{ $mov->numero_destino ?? '—' }}</td>
                        <td class="text-end fw-bold">L. {{ number_format($mov->monto, 2) }}</td>
                        <td class="text-end text-success">
                            {{ $mov->comision > 0 ? '+L. ' . number_format($mov->comision, 2) : '—' }}
                        </td>
                        <td class="text-end text-muted small">L. {{ number_format($mov->saldo_despues, 2) }}</td>
                        <td class="text-end">
                            <form method="POST" action="{{ route('recargas.destroy', $mov) }}"
                                  class="d-inline"
                                  onsubmit="return confirm('¿Eliminar este registro?')">
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
                            <i class="bi bi-phone fs-1 d-block mb-3"></i>
                            No hay recargas registradas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($movimientos->hasPages())
        <div class="card-footer">{{ $movimientos->links() }}</div>
    @endif
</div>

@endsection
