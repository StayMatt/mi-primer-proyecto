<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'price',
        'cost',
        'stock',
        'min_stock',
        'image',
        'is_active',
        'type'
    ];

    /* ======================
       ATRIBUTOS CALCULADOS
    ======================= */

    protected $appends = [
        'status',
        'margin'
    ];

    public function getStatusAttribute()
    {
        $min = $this->min_stock ?? 5;

        if ($this->stock <= 0) {
            return 'out';
        }

        if ($this->stock <= $min) {
            return 'low';
        }

        return 'ok';
    }

    public function getMarginAttribute()
    {
        return $this->price - $this->cost;
    }

    /* ======================
       SCOPES DE INVENTARIO
    ======================= */

    public function scopeOutOfStock($query)
    {
        return $query->where('stock', '<=', 0);
    }

    public function scopeCritical($query)
    {
        return $query
            ->whereColumn('stock', '<=', 'min_stock')
            ->where('stock', '>', 0);
    }

    /* ======================
       RELACIONES
    ======================= */

    // Un Producto pertenece a una Categoría
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Un Producto tiene muchas salidas (ventas)
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
