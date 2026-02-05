@extends('layouts.admin')

@section('title', 'Inteligencia de Inventario')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold m-0">Inteligencia de Inventario</h3>
        <p class="text-muted small m-0">Prioridad basada en disponibilidad y valor</p>
    </div>
    <a href="{{ route('stock.index') }}" class="btn btn-dark rounded-pill px-4 shadow-sm fw-bold">
        <i class="fas fa-shipping-fast me-2"></i> Gestionar Abastecimiento
    </a>
</div>

<div class="row g-3 mb-5">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4 border-start border-5 border-danger h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold text-danger m-0"><i class="fas fa-times-circle me-2"></i> AGOTADOS (Pérdida de venta activa)</h6>
                    <span class="badge bg-danger rounded-pill">{{ $outOfStock->count() }}</span>
                </div>
                <div class="list-group list-group-flush">
                    @forelse($outOfStock->take(3) as $item)
                        <div class="list-group-item px-0 py-2 border-0 d-flex justify-content-between small">
                            <span>{{ $item->name }}</span>
                            <span class="fw-bold text-danger">0 unid.</span>
                        </div>
                    @empty
                        <p class="text-muted small py-2">Excelente, no hay quiebres de stock.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4 border-start border-5 border-warning h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold text-warning m-0"><i class="fas fa-exclamation-triangle me-2"></i> POR AGOTARSE (Punto de Reorden)</h6>
                    <span class="badge bg-warning text-dark rounded-pill">{{ $criticalStock->count() }}</span>
                </div>
                <div class="list-group list-group-flush">
                    @forelse($criticalStock->take(3) as $item)
                        <div class="list-group-item px-0 py-2 border-0 d-flex justify-content-between small">
                            <span>{{ $item->name }}</span>
                            <span class="text-muted">Stock: <b class="text-dark">{{ $item->stock }}</b> (Min: {{ $item->min_stock ?? 5 }})</span>
                        </div>
                    @empty
                        <p class="text-muted small py-2">Todo el inventario está en niveles óptimos.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white p-4 border-0">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="fw-bold m-0">Maestro de Existencias</h5>
            <input type="text" id="searchInput" class="form-control form-control-sm rounded-pill" style="width: 250px;" placeholder="Buscar SKU o nombre...">
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light text-muted small text-uppercase">
                <tr>
                    <th class="ps-4">Item</th>
                    <th class="text-end">Costo</th>
                    <th class="text-end">Margen</th>
                    <th class="text-center">Estado</th>
                    <th class="text-end pe-4">Stock</th>
                </tr>
            </thead>
            <tbody id="inventoryBody">
                @foreach($products as $product)
                    @php
                        $min = $product->min_stock ?? 5;
                        $status = ($product->stock == 0) ? ['danger', 'Agotado'] : (($product->stock <= $min) ? ['warning', 'Bajo'] : ['success', 'OK']);
                    @endphp
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold">{{ $product->name }}</div>
                            <small class="text-muted font-monospace">SKU-{{ $product->id }}</small>
                        </td>
                        <td class="text-end font-monospace text-muted">${{ number_format($product->cost, 2) }}</td>
                        <td class="text-end">
                            <span class="text-success fw-bold small">+{{ round((($product->price - $product->cost) / $product->price) * 100) }}%</span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-{{ $status[0] }} bg-opacity-10 text-{{ $status[0] }} rounded-pill px-3">{{ $status[1] }}</span>
                        </td>
                        <td class="text-end pe-4 fw-bold {{ $product->stock <= $min ? 'text-danger' : '' }}">
                            {{ $product->stock }} <small class="text-muted fw-normal">ud.</small>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection