@extends('layouts.admin')

@section('title', 'Panel de Control')

@section('content')
<style>
    /* DISEÑO SOFT UI (Moderno y Limpio) */
    .stat-card {
        border: none;
        border-radius: 20px;
        background: #fff;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.05) !important;
    }
    .icon-box {
        width: 50px; height: 50px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem;
    }
    /* Gradientes para los iconos */
    .bg-gradient-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
    .bg-gradient-success { background: linear-gradient(135deg, #2af598 0%, #009efd 100%); color: white; }
    .bg-gradient-warning { background: linear-gradient(135deg, #f6d365 0%, #fda085 100%); color: white; }

    /* Tabla */
    .table-modern thead th {
        font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.5px;
        color: #8898aa; border-bottom: 1px solid #e9ecef; padding: 1.2rem 1rem;
    }
    .table-modern td { padding: 1.2rem 1rem; border-bottom: 1px solid #f6f9fc; vertical-align: middle; }
    .table-modern tr:last-child td { border-bottom: none; }
    
    .status-badge {
        padding: 6px 12px; border-radius: 30px; font-size: 0.75rem; font-weight: 600;
        display: inline-flex; align-items: center; gap: 5px;
    }
</style>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-5">
    <div>
        <h3 class="fw-bold text-dark mb-1">
            Hola, Administrador 👋
        </h3>
        <p class="text-muted mb-0">Aquí tienes el resumen de tu negocio al día de hoy.</p>
    </div>
    <div class="mt-3 mt-md-0">
        <a href="{{ route('pos.index') }}" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">
            <i class="fas fa-cash-register me-2"></i> Ir a Vender
        </a>
    </div>
</div>

<div class="row g-4 mb-5">
    <div class="col-md-4">
        <div class="card stat-card shadow-sm h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <p class="text-uppercase text-muted fw-bold small mb-1">Ingresos Hoy</p>
                        <h2 class="fw-bold text-dark mb-0">${{ number_format($salesToday, 2) }}</h2>
                    </div>
                    <div class="icon-box bg-gradient-primary shadow-sm">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
                <div>
                    @if($percentage >= 0)
                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill">
                            <i class="fas fa-arrow-up me-1"></i> {{ number_format($percentage, 0) }}%
                        </span>
                    @else
                        <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill">
                            <i class="fas fa-arrow-down me-1"></i> {{ number_format($percentage, 0) }}%
                        </span>
                    @endif
                    <span class="text-muted small ms-2">vs ayer</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card stat-card shadow-sm h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <p class="text-uppercase text-muted fw-bold small mb-1">Tickets</p>
                        <h2 class="fw-bold text-dark mb-0">{{ $ordersToday }}</h2>
                    </div>
                    <div class="icon-box bg-gradient-success shadow-sm">
                        <i class="fas fa-receipt"></i>
                    </div>
                </div>
                <div class="text-muted small">
                    <i class="fas fa-check-circle text-success me-1"></i> Órdenes procesadas hoy
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card stat-card shadow-sm h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <p class="text-uppercase text-muted fw-bold small mb-1">Stock Bajo</p>
                        <h2 class="fw-bold text-dark mb-0">{{ $lowStock }}</h2>
                    </div>
                    <div class="icon-box bg-gradient-warning shadow-sm">
                        <i class="fas fa-box-open"></i>
                    </div>
                </div>
                <div>
                    @if($lowStock > 0)
                        <span class="text-danger small fw-bold">
                            <i class="fas fa-exclamation-triangle me-1"></i> Requiere atención
                        </span>
                    @else
                        <span class="text-success small fw-bold">
                            <i class="fas fa-shield-alt me-1"></i> Inventario óptimo
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
            <div class="card-header bg-white py-4 px-4 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="fw-bold m-0 text-dark">Últimas Transacciones</h5>
                <a href="{{ route('sales.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                    Ver Historial Completo
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-modern mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Folio</th>
                            <th>Monto</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th class="text-end pe-4">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lastOrders as $order)
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-bold text-dark">{{ $order->folio }}</span>
                                </td>
                                <td>
                                    <span class="fw-bold text-dark">${{ number_format($order->total, 2) }}</span>
                                </td>
                                <td>
                                    @if($order->status == 'PAID')
                                        <span class="status-badge bg-success bg-opacity-10 text-success">
                                            <i class="fas fa-check-circle"></i> Pagado
                                        </span>
                                    @else
                                        <span class="status-badge bg-warning bg-opacity-10 text-warning">
                                            <i class="fas fa-clock"></i> Pendiente
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="text-dark fw-bold" style="font-size: 0.9rem;">{{ $order->created_at->format('d M') }}</span>
                                        <span class="text-muted small">{{ $order->created_at->format('h:i A') }}</span>
                                    </div>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('sales.index') }}" class="btn btn-light text-primary btn-sm rounded-circle shadow-sm" title="Ver Detalles">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="opacity-50">
                                        <i class="fas fa-folder-open fa-3x mb-3 text-secondary"></i>
                                        <p class="m-0">No hay ventas registradas hoy.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection