<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // 1. Mostrar lista de categorías
    public function index()
    {
        // MEJORA: Agregamos "with('products')" para que el contador de items de la vista no sea lento
        $categories = Category::with('products')->get(); 
        return view('categories.index', compact('categories'));
    }

    // 2. Guardar una nueva categoría
    public function store(Request $request)
    {
       $request->validate([
            'name' => 'required|max:50|unique:categories,name',
            'type' => 'required' // Validamos que llegue 'dish' o 'product'
        ]);

        Category::create([
            'name' => $request->name,
            'type' => $request->type, // ESTO ES LO IMPORTANTE
            'active' => true
        ]);

        return redirect()->back()->with('success', '¡Categoría creada correctamente!');
    }

    // 3. Eliminar categoría
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return redirect()->back()->with('success', 'Categoría eliminada.');
    }

    // 4. Ver contenido
    public function show($id)
    {
        $category = Category::with('products')->findOrFail($id);
        return view('categories.show', compact('category'));
    }
}