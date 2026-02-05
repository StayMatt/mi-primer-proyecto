<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockEntry extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'quantity', 'cost', 'notes'];

    // Relación: Una entrada pertenece a un producto
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}