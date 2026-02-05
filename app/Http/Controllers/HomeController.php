<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        // 1. DINERO: Ventas de hoy vs ayer
        $salesToday = Order::whereDate('created_at', $today)->sum('total');
        $salesYesterday = Order::whereDate('created_at', $yesterday)->sum('total');

        // Cálculo de porcentaje (Evitamos división por cero)
        $percentage = 0;
        if ($salesYesterday > 0) {
            $percentage = (($salesToday - $salesYesterday) / $salesYesterday) * 100;
        } elseif ($salesToday > 0) {
            $percentage = 100;
        }

        // 2. ACTIVIDAD: Cuántos tickets se imprimieron hoy
        $ordersToday = Order::whereDate('created_at', $today)->count();

        // 3. ALERTA: Productos que se están agotando (Solo tipo 'product')
        $lowStock = Product::where('type', 'product')
                           ->whereColumn('stock', '<=', 'min_stock')
                           ->count();

        // 4. TABLA: Las últimas 5 ventas para el resumen
        $lastOrders = Order::latest()->take(5)->get();

        return view('home', compact('salesToday', 'percentage', 'ordersToday', 'lowStock', 'lastOrders'));
    }
}