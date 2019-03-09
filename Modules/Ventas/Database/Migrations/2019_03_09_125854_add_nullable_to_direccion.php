<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNullableToDireccion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ventas__ventas', function (Blueprint $table) {
          $table->string('direccion')->nullable()->change();
          $table->string('telefono')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ventas__ventas', function (Blueprint $table) {
          $table->string('direccion')->nullable(false)->change();
          $table->string('telefono')->nullable(false)->change();
        });
    }
}
