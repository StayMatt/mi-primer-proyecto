@extends('layouts.admin')

@section('title', $category->name)
@section('header', 'Contenido de Categoría')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i> Volver a Categorías
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex align-items-center gap-3">
            <h5 class="m-0 fw-bold text-primary">
                {{ $category->name }}
            </h5>
            @if($category->type == 'dish')
                <span class="badge bg-warning text-dark">🍽️ Sección Cocina</span>
            @else
                <span class="badge bg-info text-dark">📦 Sección Barra</span>
            @endif
        </div>

        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4">Imagen</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        
                        @if($category->type == 'product')
                            <th>Stock Actual</th>
                        @endif
                        
                        <th></th> 
                    </tr>
                </thead>
                <tbody>
                    @forelse($category->products as $product)
                        <tr>
                            <td class="ps-4">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" class="rounded" width="40" height="40" style="object-fit: cover;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted" style="width: 40px; height: 40px;">
                                        @if($category->type == 'dish')
                                            <i class="fas fa-utensils"></i>
                                        @else
                                            <i class="fas fa-wine-bottle"></i>
                                        @endif
                                    </div>
                                @endif
                            </td>
                            
                            <td class="fw-bold">{{ $product->name }}</td>
                            
                            <td class="text-success fw-bold">${{ number_format($product->price, 2) }}</td>
                            
                            @if($category->type == 'product')
                                <td>
                                    @if($product->stock <= $product->min_stock)
                                        <span class="badge bg-danger">Bajo: {{ $product->stock }}</span>
                                    @else
                                        <span class="badge bg-success">{{ $product->stock }} un.</span>
                                    @endif
                                </td>
                            @endif

                            <td class="text-end pe-4">
                                <span class="badge bg-light text-muted border">ID: {{ $product->id }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $category->type == 'product' ? '5' : '4' }}" class="text-center py-5 text-muted">
                                <i class="fas fa-box-open fa-2x mb-3 d-block opacity-25"></i>
                                No hay ítems registrados en esta categoría.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection