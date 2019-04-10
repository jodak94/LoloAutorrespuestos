<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CodigoUniqueAndCosto extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
      Schema::table('productos__productos', function (Blueprint $table) {
        $table->double('costo');
        $table->double('descuento')->nullable();
        $table->string('codigo')->unique()->change();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('productos__productos', function (Blueprint $table) {
            $table->dropColumn('costo');
            $table->dropColumn('descuento');
            $table->dropUnique('productos__productos_codigo_unique');
        });
    }
}
