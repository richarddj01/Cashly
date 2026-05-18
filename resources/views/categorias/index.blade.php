@extends('layouts.app')

@section('title', 'Categorías')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">Clasifica tus ingresos y egresos</p>
    <a href="{{ route('categorias.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Nueva categoría
    </a>
</div>

@foreach([['personal', 'Personal'], ['negocio', 'Negocio'], ['ambos', 'Ambos']] as [$ctx, $label])
    @php $grupo = $categorias->where('contexto', $ctx); @endphp
    @if($grupo->count())
        <h6 class="text-muted fw-semibold mb-2 text-uppercase"
            style="font-size:0.75rem;letter-spacing:0.08em;">
            {{ $label }}
        </h6>
        <div class="card mb-4">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Categoría</th>
                            <th>Tipo</th>
                            <th>Ícono</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($grupo as $categoria)
                            <tr>
                                <td>
                                    <span class="badge me-2"
                                          style="background-color: {{ $categoria->color }};">
                                        &nbsp;
                                    </span>
                                    {{ $categoria->nombre }}
                                </td>
                                <td>
                                    <span class="badge {{ $categoria->tipo === 'ingreso' ? 'bg-success' : 'bg-danger' }}">
                                        {{ ucfirst($categoria->tipo) }}
                                    </span>
                                </td>
                                <td>
                                    @if($categoria->icono)
                                        <i class="bi bi-{{ $categoria->icono }}"></i>
                                        <small class="text-muted ms-1">{{ $categoria->icono }}</small>
                                    @else
                                        <small class="text-muted">—</small>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('categorias.edit', $categoria) }}"
                                       class="btn btn-sm btn-outline-primary me-1">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST"
                                          action="{{ route('categorias.destroy', $categoria) }}"
                                          class="d-inline"
                                          onsubmit="return confirm('¿Eliminar esta categoría?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endforeach

@if($categorias->isEmpty())
    <div class="text-center py-5 text-muted">
        <i class="bi bi-tags fs-1 d-block mb-3"></i>
        No tienes categorías todavía.
        <a href="{{ route('categorias.create') }}">Crea la primera</a>
    </div>
@endif

@endsection
