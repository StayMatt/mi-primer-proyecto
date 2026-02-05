@extends('layouts.admin')

@section('title', 'Finanzas')

@section('content')
<div class="card shadow-sm border-0 rounded-4">
    <div class="card-header bg-white py-4 px-4 d-flex justify-content-between align-items-center">
        <div>
            <h5 class="fw-bold text-dark mb-0">Historial de Ventas</h5>
            <small class="text-muted">Registro completo de transacciones</small>
        </div>
        <div>
            <button class="btn btn-outline-success btn-sm me-2"><i class="fas fa-file-excel me-2"></i>Exportar Excel</button>
            <button class="btn btn-outline-danger btn-sm"><i class="fas fa-file-pdf me-2"></i>PDF</button>
        </div>
    </div>
    
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4">Folio</th>
                        <th>Fecha</th>
                        <th>Método</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td class="ps-4 fw-bold">#{{ $order->folio }}</td>
                            <td>
                                {{ $order->created_at->format('d/m/Y') }} 
                                <small class="text-muted ms-1">{{ $order->created_at->format('h:i A') }}</small>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    <i class="fas fa-money-bill me-1"></i> {{ $order->payment_type ?? 'CASH' }}
                                </span>
                            </td>
                            <td class="fw-bold text-success fs-6">${{ number_format($order->total, 2) }}</td>
                            <td><span class="badge bg-success bg-opacity-10 text-success">Pagado</span></td>
                            <td class="text-end pe-4">
                                <button class="btn btn-sm btn-primary rounded-circle" onclick="showDetails({{ $order->id }})" title="Ver Detalles">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">No hay ventas registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-3 border-top">
            {{ $orders->links() }}
        </div>
    </div>
</div>

<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Detalle de Venta</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-between mb-3 bg-light p-3 rounded">
                    <div>
                        <small class="text-muted d-block">FOLIO</small>
                        <span class="fw-bold" id="modal-folio">...</span>
                    </div>
                    <div class="text-end">
                        <small class="text-muted d-block">TOTAL</small>
                        <span class="fw-bold text-success fs-5" id="modal-total">...</span>
                    </div>
                </div>
                
                <h6 class="text-muted small fw-bold border-bottom pb-2">PRODUCTOS VENDIDOS</h6>
                <ul class="list-group list-group-flush" id="modal-items">
                    </ul>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    function showDetails(id) {
        // Cargar datos con AJAX
        fetch(`/finanzas/${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('modal-folio').innerText = data.folio;
                document.getElementById('modal-total').innerText = '$' + parseFloat(data.total).toFixed(2);
                
                let list = document.getElementById('modal-items');
                list.innerHTML = '';
                
                data.details.forEach(item => {
                    list.innerHTML += `
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div>
                                <span class="fw-bold">${item.quantity}x</span> ${item.product_name}
                            </div>
                            <span class="text-muted">$${(item.price * item.quantity).toFixed(2)}</span>
                        </li>
                    `;
                });
                
                new bootstrap.Modal(document.getElementById('detailModal')).show();
            });
    }
</script>
@endsection