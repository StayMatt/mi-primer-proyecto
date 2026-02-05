@extends('layouts.admin')

@section('title', 'Monitor de Cocina')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold"><i class="fas fa-fire text-danger me-2"></i> Monitor de Cocina</h3>
    <button onclick="location.reload()" class="btn btn-primary btn-sm rounded-pill">
        <i class="fas fa-sync-alt me-1"></i> Actualizar
    </button>
</div>

<div class="row g-3">
    @forelse($pendingOrders as $order)
        <div class="col-md-4 col-lg-3">
            <div class="card h-100 shadow-sm border-0 border-top border-4 border-warning">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold m-0">#{{ substr($order->folio, -4) }}</h5>
                    <small class="text-muted">{{ $order->created_at->format('h:i A') }}</small>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <small class="text-muted">Tiempo de espera:</small><br>
                        <span class="fw-bold text-danger">{{ $order->created_at->diffForHumans(null, true) }}</span>
                    </div>
                    <ul class="list-group list-group-flush">
                        @foreach($order->details as $detail)
                            <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                <span class="fw-bold" style="font-size: 1.1rem;">{{ $detail->quantity }}x</span>
                                <span>{{ $detail->product_name }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="card-footer bg-white border-0 pb-3">
                    <form action="{{ route('kitchen.ready', $order->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success w-100 fw-bold">
                            <i class="fas fa-check me-2"></i> LISTO
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <i class="fas fa-utensils fa-4x text-muted opacity-25 mb-3"></i>
            <h4 class="text-muted">No hay pedidos pendientes</h4>
        </div>
    @endforelse
</div>

<script>
    // Recargar página cada 30 segundos automáticamente
    setTimeout(function(){ location.reload(); }, 30000);
</script>
@endsection