<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->foreignId('category_id')->constrained();
        
        $table->string('name');
        $table->string('type')->default('product'); // <--- ¡ESTA LÍNEA ES LA CLAVE!
        
        $table->decimal('price', 10, 2);
        $table->decimal('cost', 10, 2)->default(0);
        $table->integer('stock')->nullable();
        $table->integer('min_stock')->default(5);
        $table->string('image')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
