<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnImagesVisitasTecnicasAvaliacoes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('visitas_tecnicas_avaliacoes', function (Blueprint $table) {
            $table->json('imagens')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('visitas_tecnicas_avaliacoes', function (Blueprint $table) {
            $table->dropColumn('imagens');
        });
    }
}
