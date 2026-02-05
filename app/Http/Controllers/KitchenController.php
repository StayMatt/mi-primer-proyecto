<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class KitchenController extends Controller
{
    public function index()
    {
        // Buscamos órdenes PAGADAS (PAID) que NO estén completadas
        // Las ordenamos por hora (las viejas primero)
        $pendingOrders = Order::with('details')
                              ->where('status', 'PAID') 
                              ->orderBy('created_at', 'asc')
                              ->get();

        return view('kitchen.index', compact('pendingOrders'));
    }

    // Esta función sirve para que el cocinero marque "Listo"
    public function markAsReady($id)
    {
        $order = Order::findOrFail($id);
        
        // Cambiamos el estado a COMPLETED para que desaparezca de la pantalla
        // NOTA: Si tu base de datos usa ENUM y da error, avísame.
        $order->status = 'COMPLETED'; 
        $order->save();

        return redirect()->back()->with('success', 'Orden completada');
    }
}