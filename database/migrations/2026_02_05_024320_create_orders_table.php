<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->string('folio')->unique(); // <--- ¡ESTA LÍNEA ES LA QUE TE FALTA!
        $table->decimal('total', 10, 2);
        $table->enum('status', ['PENDING', 'PAID', 'CANCELLED'])->default('PENDING');
        $table->enum('payment_type', ['CASH', 'CARD', 'YAPE'])->nullable();
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
        Schema::dropIfExists('orders');
    }
}
