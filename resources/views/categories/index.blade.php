@extends('layouts.admin')

@section('title', 'Categorías')
@section('header', 'Gestión de Categorías')

@section('content')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-primary"><i class="fas fa-list me-2"></i> Categorías Actuales</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted small text-uppercase">
                            <tr>
                                <th class="ps-4">ID</th>
                                <th>Nombre</th>
                                <th>Sección</th>
                                <th class="text-end pe-4">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                                <tr>
                                    <td class="ps-4 text-muted">#{{ $category->id }}</td>
                                    <td>
                                        <a href="{{ route('categories.show', $category->id) }}" class="fw-bold text-decoration-none text-primary">
                                            <i class="fas fa-folder-open me-2"></i> {{ $category->name }}
                                        </a>
                                        <div class="small text-muted">{{ $category->products->count() }} ítems</div>
                                    </td>
                                    <td>
                                        @if($category->type == 'dish')
                                            <span class="badge bg-warning text-dark">🍽️ Cocina</span>
                                        @else
                                            <span class="badge bg-info text-dark">📦 Barra</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('¿Eliminar esta categoría?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger border-0">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="fas fa-folder-open fa-2x mb-3 d-block opacity-25"></i>
                                        No hay categorías registradas aún.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h6 class="m-0 fw-bold"><i class="fas fa-plus-circle me-2"></i> Nueva Categoría</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('categories.store') }}" method="POST">
                        @csrf 
                        
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">¿A qué sección pertenece?</label>
                            <select name="type" class="form-select bg-light border-primary" required>
                                <option value="dish">🍽️ Cocina (Platos, Preparados)</option>
                                <option value="product">📦 Barra (Gaseosas, Stock)</option>
                            </select>
                            <div class="form-text small">Esto definirá dónde aparecen los productos.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Nombre de la Categoría</label>
                            <input type="text" name="name" class="form-control form-control-lg" placeholder="Ej: Hamburguesas" required>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                Guardar Categoría
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection