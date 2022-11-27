<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AvaliadorLimparRespostas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::table('avaliador_oculto_respostas')->delete();
        \DB::table('avaliador_oculto_respostas_subjetivas')->delete();
        \DB::table('avaliador_oculto_usuarios_formularios')->update([
            'observacoes' => null,
            'foto_comprovante' => null,
            'finalizou' => false,
            'data_termino' => null,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::table('avaliador_oculto_respostas')->delete();
        \DB::table('avaliador_oculto_respostas_subjetivas')->delete();
    }
}
