<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVentasVentaDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ventas__ventadetalles', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            // Your fields
            $table->integer('venta_id')->unsigned()->index();
            $table->foreign('venta_id')->references('id')->on('ventas__ventas')->onDelete('cascade');

            $table->integer('producto_id')->unsigned()->index()->nullable();
            $table->foreign('producto_id')->references('id')->on('productos__productos');

            $table->integer('cantidad');
            $table->bigInteger('precio_unitario');
            $table->bigInteger('precio_subtotal');
            $table->string('descripcion')->nullable();

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
        Schema::dropIfExists('ventas__ventadetalles');
    }
}
