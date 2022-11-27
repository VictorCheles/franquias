<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ComunicadosSetorId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('comunicados', function (Blueprint $table) {
            $table->integer('setor_id')->nullable()->unsigned();
            $table->foreign('setor_id')->references('id')->on('setores');
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
            $table->dropColumn('setor_id');
        });
    }
}
