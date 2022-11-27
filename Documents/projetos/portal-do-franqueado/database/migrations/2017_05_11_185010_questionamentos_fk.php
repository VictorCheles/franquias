<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class QuestionamentosFk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('questionamentos', function (Blueprint $table) {
            $table->foreign('questionamento_id', 'questionamentos_questionamento_fk')->references('id')->on('questionamentos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('questionamentos', function (Blueprint $table) {
            $table->dropForeign('questionamentos_questionamento_fk');
        });
    }
}
