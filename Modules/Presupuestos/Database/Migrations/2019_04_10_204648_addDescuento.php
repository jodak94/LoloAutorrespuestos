<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDescuento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('presupuestos__presupuestodetalles', function (Blueprint $table) {
            $table->double('descuento');
          });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('presupuestos__presupuestodetalles', function (Blueprint $table) {
            $table->dropColumn('descuento');
          });
    }
}
