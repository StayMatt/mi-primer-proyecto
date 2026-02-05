@extends('layouts.admin')

@section('title', 'Panel de Control')
@section('header', 'Resumen General')

@section('content')
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card h-100 border-start border-4 border-primary">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-light p-3 rounded-circle me-3">
                        <i class="fas fa-dollar-sign fa-2x text-primary"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 text-uppercase small fw-bold">Ventas del Día</h6>
                        <h3 class="fw-bold mb-0">$0.00</h3>
                        <small class="text-success"><i class="fas fa-arrow-up"></i> 0% vs ayer</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 border-start border-4 border-success">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-light p-3 rounded-circle me-3">
                        <i class="fas fa-shopping-bag fa-2x text-success"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 text-uppercase small fw-bold">Pedidos Activos</h6>
                        <h3 class="fw-bold mb-0">0</h3>
                        <small class="text-muted">En cocina ahora mismo</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card h-100 border-start border-4 border-warning">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-light p-3 rounded-circle me-3">
                        <i class="fas fa-box-open fa-2x text-warning"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 text-uppercase small fw-bold">Stock Bajo</h6>
                        <h3 class="fw-bold mb-0">5</h3>
                        <small class="text-danger fw-bold">¡Atención requerida!</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-clock me-2"></i> Últimas Ventas</span>
                    <button class="btn btn-sm btn-light">Ver todo</button>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Folio</th>
                                <th>Cliente</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th>Hora</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3 d-block opacity-25"></i>
                                    Aún no hay ventas registradas hoy.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection