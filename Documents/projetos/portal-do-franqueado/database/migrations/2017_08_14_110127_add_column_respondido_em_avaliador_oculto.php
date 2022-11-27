<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnRespondidoEmAvaliadorOculto extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('avaliador_oculto_usuarios_formularios', function (Blueprint $table) {
            $table->dateTime('respondido_em')->nullable();
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
            $table->dropColumn('respondido_em');
        });
    }
}
