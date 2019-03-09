<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePresupuestosPresupuestoDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('presupuestos__presupuestodetalles', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            // Your fields
            $table->integer('presupuesto_id')->unsigned()->index();
            $table->foreign('presupuesto_id')->references('id')->on('presupuestos__presupuestos')->onDelete('cascade');

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
        Schema::dropIfExists('presupuestos__presupuestodetalles');
    }
}
