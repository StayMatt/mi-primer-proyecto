<div class="modal fade" id="createProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">
                    @if($defaultType == 'product')
                        📦 Nuevo Producto (Inventario)
                    @else
                        🍽️ Nuevo Preparado (Cocina)
                    @endif
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="type" value="{{ $defaultType }}">

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Imagen de Referencia</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nombre</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Categoría</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Selecciona una...</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold">Precio Venta ($)</label>
                            <input type="number" step="0.01" name="price" class="form-control" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold">Costo ($)</label>
                            <input type="number" step="0.01" name="cost" class="form-control" value="0">
                        </div>
                    </div>

                    @if($defaultType == 'product')
                        <div class="row bg-light p-2 rounded border">
                            <div class="col-12 mb-2"><small class="text-primary fw-bold"><i class="fas fa-boxes"></i> Control de Inventario</small></div>
                            <div class="col-6 mb-3">
                                <label class="form-label small fw-bold">Stock Inicial</label>
                                <input type="number" name="stock" class="form-control" value="0">
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label small fw-bold">Alerta Mínimo</label>
                                <input type="number" name="min_stock" class="form-control" value="5">
                            </div>
                        </div>
                    @endif

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>