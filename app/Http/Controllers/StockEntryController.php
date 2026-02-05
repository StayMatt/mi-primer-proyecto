<?php

namespace App\Http\Controllers;

use App\Models\StockEntry;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockEntryController extends Controller
{
    // 1. Mostrar el formulario y el historial reciente
    public function index()
    {
        // CORREGIDO: Quitamos "->where('active', true)" para que no de error
        $products = Product::where('type', 'product')->get();
        
        // Traemos las últimas 10 compras
        $entries = StockEntry::with('product')->latest()->take(10)->get();

        return view('stock.index', compact('products', 'entries'));
    }

    // 2. Guardar la compra y Actualizar Stock
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'cost' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction(); // Para que sea seguro

            // A) Crear el registro de "Entrada" (El historial)
            StockEntry::create([
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'cost' => $request->cost,
                'notes' => $request->notes
            ]);

            // B) ACTUALIZAR EL PRODUCTO (La Magia)
            $product = Product::findOrFail($request->product_id);
            
            // 1. Sumamos al stock actual
            $product->stock += $request->quantity;
            
            // 2. Actualizamos el costo referencia (Para saber a cuánto compramos la última vez)
            // Solo actualizamos si viene un costo (por seguridad)
            if ($request->has('cost')) {
                $product->cost = $request->cost;
            }
            
            $product->save();

            DB::commit();
            return redirect()->back()->with('success', '¡Stock actualizado correctamente!');

        } catch (\Exception $e) {
            DB::rollBack();
            // Es buena idea ver el error real si pasa algo
            return redirect()->back()->with('error', 'Error al registrar: ' . $e->getMessage());
        }
    }
}