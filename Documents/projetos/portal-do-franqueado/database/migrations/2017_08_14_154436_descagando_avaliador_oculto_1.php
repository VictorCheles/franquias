<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DescagandoAvaliadorOculto1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE avaliador_oculto_usuarios_formularios ALTER COLUMN data_termino TYPE timestamp without time zone');
        DB::statement('update avaliador_oculto_usuarios_formularios set respondido_em = now() where observacoes is not null and finalizou = false');
        DB::statement('update avaliador_oculto_usuarios_formularios set respondido_em = data_termino where finalizou = true and respondido_em is null');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
