<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questionamentos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('comunicado_id')->unsigned();
            $table->foreign('comunicado_id')->references('id')->on('comunicados')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('questionamento_id')->nullable()->unsigned();
            $table->text('texto');
            $table->json('anexos')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('questionamentos');
    }
}
