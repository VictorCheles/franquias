<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnRelatoFinalVisitaTecnica extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('visitas_tecnicas', function (Blueprint $table) {
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
        Schema::table('visitas_tecnicas', function (Blueprint $table) {
            $table->dropColumn('relato_final');
        });
    }
}
