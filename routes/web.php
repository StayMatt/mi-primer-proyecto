<?php

use Illuminate\Support\Facades\Route;
// --- IMPORTACIONES ---
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\KitchenController;
use App\Http\Controllers\StockEntryController;
use App\Http\Controllers\InventoryController;
/*
|--------------------------------------------------------------------------
| Web Routes (CORREGIDO)
|--------------------------------------------------------------------------
*/

// 1. DASHBOARD
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

// 2. CATÁLOGOS (Gestión de Productos)
Route::resource('categorias', CategoryController::class)->names('categories');

// --- AQUÍ ESTABA EL ERROR: Cambiamos las URLs para que no choquen ---
Route::get('/catalogo/barra', [ProductController::class, 'indexInventory'])->name('products.inventory');
Route::get('/catalogo/cocina', [ProductController::class, 'indexKitchen'])->name('products.kitchen');

// Acciones de Guardar/Editar/Borrar Productos
Route::post('/productos', [ProductController::class, 'store'])->name('products.store');
Route::put('/productos/{id}', [ProductController::class, 'update'])->name('products.update');
Route::delete('/productos/{id}', [ProductController::class, 'destroy'])->name('products.destroy');


// 3. PUNTO DE VENTA (POS)
Route::get('/vender', [PosController::class, 'index'])->name('pos.index');
Route::post('/vender/guardar', [PosController::class, 'store'])->name('pos.store');


// 4. FINANZAS / HISTORIAL
Route::get('/finanzas', [SalesController::class, 'index'])->name('sales.index');
Route::get('/finanzas/{id}', [SalesController::class, 'show'])->name('sales.show');


// 5. COMPRAS / REABASTECIMIENTO
Route::get('/compras', [StockEntryController::class, 'index'])->name('stock.index');
Route::post('/compras', [StockEntryController::class, 'store'])->name('stock.store');


// 6. MONITOR DE COCINA (KDS)
// Dejamos la URL '/cocina' para el monitor, que es lo más fácil de escribir para el cocinero
Route::get('/cocina', [KitchenController::class, 'index'])->name('kitchen.index');
Route::post('/cocina/{id}/listo', [KitchenController::class, 'markAsReady'])->name('kitchen.ready');


// 7. INVENTARIO ACTUAL 
// --- RUTAS DE INVENTARIO ---
Route::get('/inventario/actual', [InventoryController::class, 'index'])->name('inventory.index');