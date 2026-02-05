<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('order_details', function (Blueprint $table) {
        $table->id();
        $table->foreignId('order_id')->constrained()->onDelete('cascade'); // Relación con el pedido
        $table->foreignId('product_id')->constrained(); // Relación con el producto
        
        $table->string('product_name'); // Guardamos el nombre por si luego cambia en el catálogo
        $table->integer('quantity');
        $table->decimal('price', 10, 2); // Precio al momento de la venta
        
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
        Schema::dropIfExists('order_details');
    }
}
