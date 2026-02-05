@extends('layouts.admin')

@section('title', 'Gestión de Abastecimiento')

@section('content')
<style>
    .form-control:focus, .form-select:focus {
        border-color: #4361ee;
        box-shadow: 0 0 0 0.25 million rgba(67, 97, 238, 0.1);
    }
    .input-group-text {
        background-color: #f8fafc;
        border-right: none;
        color: #94a3b8;
    }
    .form-control, .form-select {
        border-left: none;
    }
    .card-reabastecer {
        border: 1px solid #e2e8f0;
        transition: box-shadow 0.3s ease;
    }
    .card-reabastecer:hover {
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05) !important;
    }
    .history-item {
        border-left: 4px solid #e2e8f0;
        transition: all 0.2s;
    }
    .history-item:hover {
        border-left-color: #4361ee;
        background-color: #f8fafc;
    }
</style>

<div class="mb-4">
    <h4 class="fw-bold text-dark m-0">Reabastecimiento de Almacén</h4>
    <p class="text-muted small m-0">Registra entradas de mercadería para actualizar el stock y costos.</p>
</div>

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm mb-4 d-flex align-items-center">
        <i class="fas fa-check-circle me-3 fs-4"></i>
        <div>
            <span class="fw-bold">¡Operación exitosa!</span><br>
            <small>{{ session('success') }}</small>
        </div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-4">
    <div class="col-lg-5">
        <div class="card card-reabastecer shadow-sm border-0 rounded-4 h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-3 me-3">
                        <i class="fas fa-box-open fs-4"></i>
                    </div>
                    <h5 class="mb-0 fw-bold text-dark">Nueva Entrada</h5>
                </div>

                <form action="{{ route('stock.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted text-uppercase mb-2">Seleccionar Producto</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <select name="product_id" class="form-select form-select-lg fw-bold" required>
                                <option value="" selected disabled>Buscar producto...</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">
                                        {{ $product->name }} ({{ $product->stock }} en stock)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-6">
                            <label class="form-label fw-bold small text-muted text-uppercase mb-2">Cantidad</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-plus"></i></span>
                                <input type="number" name="quantity" class="form-control form-control-lg fw-bold" placeholder="0" min="1" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold small text-muted text-uppercase mb-2">Costo Unit.</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                <input type="number" step="0.01" name="cost" class="form-control form-control-lg fw-bold" placeholder="0.00" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted text-uppercase mb-2">Referencia o Notas</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-pen"></i></span>
                            <input type="text" name="notes" class="form-control" placeholder="Ej: Factura #123 o Proveedor X">
                        </div>
                    </div>

                    <div class="d-grid mt-5">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold shadow">
                            Confirmar Ingreso <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card card-reabastecer shadow-sm border-0 rounded-4 h-100">
            <div class="card-header bg-white py-4 border-0 px-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-dark mb-0">Movimientos Recientes</h5>
                    <span class="badge bg-light text-dark border rounded-pill px-3">Últimas 10 entradas</span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($entries as $entry)
                        <div class="list-group-item history-item px-4 py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1 fw-bold text-dark">{{ $entry->product->name }}</h6>
                                    <div class="d-flex align-items-center text-muted small">
                                        <i class="fas fa-calendar-day me-1"></i> {{ $entry->created_at->format('d M, Y - h:i A') }}
                                        <span class="mx-2">•</span>
                                        <i class="fas fa-tag me-1"></i> ${{ number_format($entry->cost, 2) }} c/u
                                    </div>
                                    @if($entry->notes)
                                        <p class="mb-0 mt-2 text-muted italic small bg-light p-2 rounded">
                                            <i class="fas fa-info-circle me-1"></i> {{ $entry->notes }}
                                        </p>
                                    @endif
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 fs-6">
                                        +{{ $entry->quantity }} <small>unid.</small>
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-history fa-3x mb-3 opacity-10"></i>
                            <p class="fw-bold mb-0">No se registran movimientos</p>
                            <small>Las nuevas entradas aparecerán aquí.</small>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection