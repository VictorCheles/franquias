<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ConsultoriaDeCampoAlterTableVisitaAddColumnRelatoFinal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('consultoria_campo')->table('visitas', function (Blueprint $table) {
            $table->text('relato_final')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('consultoria_campo')->table('visitas', function (Blueprint $table) {
            $table->dropColumn('relato_final');
        });
    }
}
