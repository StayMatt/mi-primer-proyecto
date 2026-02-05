<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index()
    {
        // 1. Cargamos productos con su categoría
        $products = Product::where('products.type', 'product')->with('category')->get();

        // 2. Lógica de Segmentación (Inteligencia de Inventario)
        // Agotados: Stock en 0 o menos
        $outOfStock = $products->filter(fn($p) => $p->stock <= 0);
        
        // Críticos: Stock mayor a 0 pero menor o igual al mínimo (Reorder Point)
        $criticalStock = $products->filter(fn($p) => $p->stock > 0 && $p->stock <= ($p->min_stock ?? 5));

        // 3. KPIs para el Dashboard
        $totalCost = $products->sum(fn($p) => $p->stock * ($p->cost ?? 0));
        
        // Datos para el gráfico de composición
        $categoriesData = Product::where('products.type', 'product')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('categories.name', DB::raw('sum(products.stock) as total_stock'))
            ->groupBy('categories.name')->get();

        return view('inventory.index', compact(
            'products', 'totalCost', 'outOfStock', 'criticalStock', 'categoriesData'
        ));
    }
}