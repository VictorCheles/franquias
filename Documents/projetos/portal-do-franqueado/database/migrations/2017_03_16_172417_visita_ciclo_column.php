<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VisitaCicloColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('visitas_tecnicas', function (Blueprint $table) {
            $table->integer('ciclo_id')->nullable()->unsigned();
            $table->foreign('ciclo_id', 'fk_visita_cliclo')->references('id')->on('ciclos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('visitas_tecnicas', function (Blueprint $table) {
            $table->dropForeign('fk_visita_cliclo');
            $table->dropColumn('ciclo_id');
        });
    }
}
