<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificacaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notificacoes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('destinatario')->unsigned();;
            $table->foreign('destinatario')->references('id')->on('users');
            $table->string('mensagem');
            $table->boolean('status')->default(false);
            $table->integer('tipo');
            $table->json('atributos')->nullable();
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
        Schema::drop('notificacoes');
    }
}
