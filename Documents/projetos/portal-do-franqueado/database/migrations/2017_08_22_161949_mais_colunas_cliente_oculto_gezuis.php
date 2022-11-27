<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MaisColunasClienteOcultoGezuis extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('avaliador_oculto_usuarios_formularios', function (Blueprint $table) {
            $table->string('foto_loja')->nullable();
            $table->string('foto_consumo')->nullable();
            $table->dateTime('data_visita')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('avaliador_oculto_usuarios_formularios', function (Blueprint $table) {
            $table->dropColumn('foto_loja');
            $table->dropColumn('foto_consumo');
            $table->dropColumn('data_visita');
        });
    }
}
