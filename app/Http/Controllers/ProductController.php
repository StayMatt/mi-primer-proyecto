<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // --- VISTA 1: BARRA (Solo Gaseosas) ---
    public function indexInventory()
    {
        // Trae SOLO lo que en la base de datos dice 'product'
        $products = Product::where('type', 'product')->orderBy('created_at', 'desc')->get();
        // Trae SOLO categorías de tipo 'product'
        $categories = Category::where('type', 'product')->where('active', true)->get();

        return view('products.index', compact('products', 'categories'))
                ->with('viewType', 'product')
                ->with('title', 'Inventario de Barra');
    }

    // --- VISTA 2: COCINA (Solo Platos) ---
    public function indexKitchen()
    {
        // Trae SOLO lo que en la base de datos dice 'dish'
        $products = Product::where('type', 'dish')->orderBy('created_at', 'desc')->get();
        // Trae SOLO categorías de tipo 'dish'
        $categories = Category::where('type', 'dish')->where('active', true)->get();

        return view('products.index', compact('products', 'categories'))
                ->with('viewType', 'dish')
                ->with('title', 'Menú de Cocina');
    }

    // --- GUARDAR (La Categoría Manda) ---
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'category_id' => 'required', // Obligatorio elegir categoría
            'price' => 'required|numeric',
            'image' => 'nullable|image|max:2048',
        ]);

        // 1. Buscamos la categoría que eligió el usuario
        $category = Category::findOrFail($request->category_id);
        
        // 2. OBTENEMOS EL TIPO DE LA CATEGORÍA (Aquí está el truco)
        // Si la categoría es "Hamburguesas" (dish), el producto SERÁ dish.
        $realType = $category->type; 

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        Product::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'type' => $realType, // Usamos el tipo real de la categoría
            'price' => $request->price,
            'cost' => $request->cost ?? 0,
            
            // Si es Cocina, Stock es NULL. Si es Barra, guardamos el stock.
            'stock' => $realType == 'dish' ? null : $request->stock,
            'min_stock' => $request->min_stock ?? 5,
            'image' => $imagePath,
        ]);

        return redirect()->back()->with('success', '¡Registrado correctamente!');
    }

    // --- ACTUALIZAR ---
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $request->validate(['name' => 'required', 'price' => 'required|numeric']);

        $data = $request->except(['image', 'stock']);

        // Solo actualizamos stock si es producto de Barra
        if ($product->type == 'product') {
            $data['stock'] = $request->stock;
            $data['min_stock'] = $request->min_stock;
        }

        if ($request->hasFile('image')) {
            if ($product->image) Storage::disk('public')->delete($product->image);
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);
        return redirect()->back()->with('success', 'Actualizado correctamente.');
    }

    // --- ELIMINAR ---
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        if ($product->image) Storage::disk('public')->delete($product->image);
        $product->delete();
        return redirect()->back()->with('success', 'Eliminado.');
    }
}