<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PesoPerguntaAvaliadorOculto extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('avaliador_oculto_perguntas', function (Blueprint $table) {
            $table->integer('peso')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('avaliador_oculto_perguntas', function (Blueprint $table) {
            $table->dropColumn('peso');
        });
    }
}
