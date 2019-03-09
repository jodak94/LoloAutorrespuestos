<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDatosClientes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('presupuestos__presupuestos', function (Blueprint $table) {
            $table->renameColumn('cliente','nombre_cliente');
            $table->string('telefono_cliente')->nullable();
            $table->string('email_cliente')->nullable();
            $table->string('direccion_cliente')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('', function (Blueprint $table) {

        });
    }
}
