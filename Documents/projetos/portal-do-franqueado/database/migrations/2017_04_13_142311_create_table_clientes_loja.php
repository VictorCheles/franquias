<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableClientesLoja extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes_loja', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome', 255);
            $table->string('email', 255)->unique();
            $table->string('telefone', 45);
            $table->integer('estabelecimento_id')->unsigned();
            $table->foreign('estabelecimento_id')->references('id')->on('clientes_loja_estabelecimentos');
            $table->integer('loja_id')->unsigned();
            $table->foreign('loja_id')->references('id')->on('lojas');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('clientes_loja');
    }
}
