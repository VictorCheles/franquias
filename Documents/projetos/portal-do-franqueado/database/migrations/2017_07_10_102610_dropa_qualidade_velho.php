<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropaQualidadeVelho extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tabelas = [
            'visita_tecnica_item_notificacao',
            'visitas_tecnicas_avaliacoes',
            'acoes_corretivas',
            'visitas_tecnicas',
            'ciclos',
            'avaliacoes',
            'avaliacoes_categorias',
            'avaliacoes_setores',
            'item_notificacao'
        ];

        foreach ($tabelas as $tabela) {
            DB::statement("drop table if exists {$tabela}");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
