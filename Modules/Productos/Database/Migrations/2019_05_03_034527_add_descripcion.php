<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDescripcion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('productos__productos', function (Blueprint $table) {
            $table->string('descripcion',2000)->nullable();
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
            $table->dropColumn('descripcion');
          });
    }
}
