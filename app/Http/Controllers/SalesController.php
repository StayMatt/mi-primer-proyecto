<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function index()
    {
        // Listar ventas ordenadas por la más reciente
        $orders = Order::orderBy('created_at', 'desc')->paginate(10);
        return view('sales.index', compact('orders'));
    }

    public function show($id)
    {
        // Ver detalle de una venta específica
        $order = Order::with('details')->findOrFail($id);
        return response()->json($order); // Lo devolveremos como JSON para mostrarlo en un Modal rápido
    }
}