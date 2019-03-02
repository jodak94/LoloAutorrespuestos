<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditColumnsFromVentas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('ventas__ventas', function (Blueprint $table) {
          $table->dropForeign('ventas__ventas_cliente_id_foreign');
          $table->dropColumn('cliente_id');

          $table->integer('datos_id')->unsigned()->index();
          $table->foreign('datos_id')->references('id')->on('clientes__datosfacturacions');

          $table->string('tipo_factura');
          $table->double('monto_pagado');
          $table->double('total_iva');
          $table->string('precio_total_letras');
          $table->integer('plazo_credito')->nullable();

          $table->renameColumn('precio_total', 'monto_total');
          $table->string('razon_social');
          $table->string('ruc');
          $table->string('direccion');
          $table->string('telefono');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
