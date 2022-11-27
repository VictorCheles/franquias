<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NovasColunasAvaliadorOcultoUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('avaliador_oculto_users', function (Blueprint $table) {
            $table->string('telefone')->nullable();
            $table->string('uf', 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('avaliador_oculto_users', function (Blueprint $table) {
            $table->dropColumn('telefone');
            $table->dropColumn('uf');
        });
    }
}
