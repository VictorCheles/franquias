<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableConsultoriaCampoResposta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('alter table consultoria_campo.respostas alter column resposta drop not null');
        DB::statement('alter table consultoria_campo.respostas alter column resposta set default null');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('alter table consultoria_campo.respostas alter column resposta set not null');
    }
}
