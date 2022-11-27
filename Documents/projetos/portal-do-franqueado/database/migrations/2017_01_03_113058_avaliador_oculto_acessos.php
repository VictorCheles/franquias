<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AvaliadorOcultoAcessos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('avaliador_oculto_acessos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('avaliador_oculto_users')->onUpdate('cascade')->onDelete('cascade');
            $table->ipAddress('IP')->nullable();
            $table->string('browser')->nullable();
            $table->string('plataforma')->nullable();
            $table->string('user_agent')->nullable();

            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('avaliador_oculto_acessos');
    }
}
