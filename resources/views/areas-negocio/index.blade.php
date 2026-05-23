@extends('layouts.app')

@section('title', 'Áreas del negocio')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">Define las áreas o divisiones de tu negocio</p>
    <a href="{{ route('areas-negocio.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Nueva área
    </a>
</div>

@if($areas->isEmpty())
    <div class="card">
        <div class="card-body text-center py-5 text-muted">
            <i class="bi bi-diagram-3 fs-1 d-block mb-3"></i>
            No tienes áreas definidas todavía.<br>
            <a href="{{ route('areas-negocio.create') }}">Crea la primera</a>
            <p class="small mt-3">
                Ejemplos: Papelería, Impresiones, Cocina, Bar, Diseño, Consultoría...
            </p>
        </div>
    </div>
@else
    <div class="row g-3">
        @foreach($areas as $area)
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center"
                                     style="width:40px;height:40px;background:{{ $area->color }}20;">
                                    <i class="bi bi-{{ $area->icono ?? 'building' }}"
                                       style="color:{{ $area->color }};font-size:1.2rem;"></i>
                                </div>
                                <div>
                                    <h6 class="fw-semibold mb-0">{{ $area->nombre }}</h6>
                                    <small class="text-muted">
                                        {{ $area->movimientos()->count() }} movimientos
                                    </small>
                                </div>
                            </div>
                            <span class="badge {{ $area->activa ? 'bg-success' : 'bg-secondary' }}">
                                {{ $area->activa ? 'Activa' : 'Inactiva' }}
                            </span>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('areas-negocio.edit', $area) }}"
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i> Editar
                            </a>
                            <form method="POST"
                                  action="{{ route('areas-negocio.destroy', $area) }}"
                                  class="d-inline"
                                  onsubmit="return confirm('¿Desactivar esta área?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-eye-slash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

@endsection
