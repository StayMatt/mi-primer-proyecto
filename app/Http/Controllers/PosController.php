<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Necesario para transacciones

class PosController extends Controller
{
    public function index()
    {
        $categories = Category::with('products')->where('active', true)->get();
        return view('pos.index', compact('categories'));
    }

    // --- AQUÍ ESTÁ LA LÓGICA DE VENTA Y STOCK ---
    public function store(Request $request)
    {
        try {
            DB::beginTransaction(); // Iniciamos operación segura

            // 1. Crear la Cabecera del Pedido
            $order = Order::create([
                'folio' => 'ORD-' . strtoupper(uniqid()), // Genera un folio único
                'total' => $request->total,
                'status' => 'PAID', // Asumimos pagado por ahora
                'payment_type' => 'CASH'
            ]);

            // 2. Procesar cada producto del carrito
            foreach ($request->cart as $item) {
                $product = Product::findOrFail($item['id']);

                // A) Guardar el detalle de venta
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $item['qty'],
                    'price' => $product->price
                ]);

                // B) DESCONTAR STOCK (Solo si es Producto de Barra)
                if ($product->type == 'product') {
                    // Validamos que no quede negativo (opcional)
                    $newStock = $product->stock - $item['qty'];
                    
                    // Actualizamos el stock
                    $product->update(['stock' => $newStock]);
                }
                // Si es 'dish' (Comida), no hacemos nada con el stock.
            }

            DB::commit(); // Confirmamos los cambios en la BD
            return response()->json(['success' => true, 'message' => 'Venta registrada']);

        } catch (\Exception $e) {
            DB::rollBack(); // Si algo falla, deshacemos todo
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
}