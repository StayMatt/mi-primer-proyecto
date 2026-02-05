<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Permitimos que estos campos se llenen masivamente
protected $fillable = ['name', 'description', 'active', 'type'];
    // Relación: Una Categoría TIENE MUCHOS Productos
    public function products()
    {
        return $this->hasMany(Product::class);
    }

}
