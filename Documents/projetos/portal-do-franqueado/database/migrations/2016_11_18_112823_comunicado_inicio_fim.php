<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ComunicadoInicioFim extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('comunicados', function (Blueprint $table) {
            $table->dateTime('inicio')->nullable();
            $table->dateTime('fim')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comunicados', function (Blueprint $table) {
            $table->dropColumn('inicio');
            $table->dropColumn('fim');
        });
    }
}
