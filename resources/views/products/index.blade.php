@extends('layouts.admin')

@section('title', $title)
@section('header', $title)

@section('content')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 fw-bold text-primary">{{ $title }}</h6>
            
            <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="fas fa-plus me-2"></i> 
                Nuevo {{ $viewType == 'product' ? 'Producto' : 'Preparado' }}
            </button>
        </div>
        
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4">Imagen</th>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Precio</th>
                        
                        @if($viewType == 'product')
                            <th>Stock</th>
                        @endif

                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td class="ps-4">
                                @if($product->image) <img src="{{ asset('storage/'.$product->image) }}" width="40" height="40" class="rounded object-fit-cover"> 
                                @else <div class="bg-light rounded text-center text-muted pt-2" style="width:40px;height:40px;"><i class="fas fa-image"></i></div> @endif
                            </td>
                            <td class="fw-bold">{{ $product->name }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $product->category->name }}</span></td>
                            <td class="text-success fw-bold">${{ number_format($product->price, 2) }}</td>
                            
                            @if($viewType == 'product')
                                <td>
                                    @if($product->stock <= $product->min_stock)
                                        <span class="badge bg-danger">{{ $product->stock }}</span>
                                    @else
                                        <span class="badge bg-success">{{ $product->stock }}</span>
                                    @endif
                                </td>
                            @endif

                            <td class="text-end pe-4">
                                <button class="btn btn-sm btn-light text-warning" onclick="editItem({ $product })" data-bs-toggle="modal" data-bs-target="#editModal"><i class="fas fa-edit"></i></button>
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Borrar?');">@csrf @method('DELETE')<button class="btn btn-sm btn-light text-danger"><i class="fas fa-trash"></i></button></form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-5 text-muted">No hay registros aquí todavía.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="createModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title">Registrar en {{ $viewType == 'product' ? 'Inventario' : 'Cocina' }}</h5>
                    <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        
                        <input type="hidden" name="type" value="{{ $viewType }}">

                        <div class="mb-3"><label class="fw-bold small">Imagen</label><input type="file" name="image" class="form-control"></div>
                        <div class="mb-3"><label class="fw-bold small">Nombre</label><input type="text" name="name" class="form-control" required></div>
                        <div class="mb-3">
                            <label class="fw-bold small">Categoría</label>
                            <select name="category_id" class="form-select" required>
                                @foreach($categories as $cat) <option value="{{ $cat->id }}">{{ $cat->name }}</option> @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3"><label class="fw-bold small">Precio</label><input type="number" step="0.01" name="price" class="form-control" required></div>
                            <div class="col-6 mb-3"><label class="fw-bold small">Costo</label><input type="number" step="0.01" name="cost" class="form-control" value="0"></div>
                        </div>

                        @if($viewType == 'product')
                            <div class="row bg-light p-2 border rounded">
                                <div class="col-6"><label class="fw-bold small">Stock Inicial</label><input type="number" name="stock" class="form-control" value="0"></div>
                                <div class="col-6"><label class="fw-bold small">Mínimo</label><input type="number" name="min_stock" class="form-control" value="5"></div>
                            </div>
                        @endif

                    </div>
                    <div class="modal-footer"><button type="submit" class="btn btn-primary">Guardar</button></div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark"><h5 class="modal-title">Editar</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
                <form id="editForm" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3"><label class="fw-bold small">Imagen (Opcional)</label><input type="file" name="image" class="form-control"></div>
                        <div class="mb-3"><label class="fw-bold small">Nombre</label><input type="text" name="name" id="e_name" class="form-control" required></div>
                        <div class="mb-3">
                            <label class="fw-bold small">Categoría</label>
                            <select name="category_id" id="e_cat" class="form-select">
                                @foreach($categories as $cat) <option value="{{ $cat->id }}">{{ $cat->name }}</option> @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3"><label class="fw-bold small">Precio</label><input type="number" step="0.01" name="price" id="e_price" class="form-control" required></div>
                            <div class="col-6 mb-3"><label class="fw-bold small">Costo</label><input type="number" step="0.01" name="cost" id="e_cost" class="form-control"></div>
                        </div>
                        
                        @if($viewType == 'product')
                            <div class="row bg-light p-2 border rounded">
                                <div class="col-6"><label class="fw-bold small">Stock</label><input type="number" name="stock" id="e_stock" class="form-control"></div>
                                <div class="col-6"><label class="fw-bold small">Mínimo</label><input type="number" name="min_stock" id="e_min" class="form-control"></div>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer"><button type="submit" class="btn btn-warning">Actualizar</button></div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function editItem(item) {
            document.getElementById('editForm').action = '/productos/' + item.id;
            document.getElementById('e_name').value = item.name;
            document.getElementById('e_cat').value = item.category_id;
            document.getElementById('e_price').value = item.price;
            document.getElementById('e_cost').value = item.cost;
            
            // Solo intentamos llenar stock si existe el campo (o sea, si estamos en productos)
            if(document.getElementById('e_stock')) {
                document.getElementById('e_stock').value = item.stock;
                document.getElementById('e_min').value = item.min_stock;
            }
        }
    </script>
@endsection