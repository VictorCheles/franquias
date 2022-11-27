<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromocaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promocoes', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('nome');
            $table->text('descricao');
            $table->text('regulamento');
            $table->string('texto_mobile');
            $table->date('inicio');
            $table->date('fim');
            $table->integer('ordem');
            $table->integer('validade_cupom');
            $table->integer('max_cupons_usados');
            $table->integer('cupons_usados')->default(0);
            $table->integer('cupons_criados')->default(0);
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('modificador_por')->unsigned()->nullable();
            $table->foreign('modificador_por')->references('id')->on('users');
            $table->string('imagem');
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
        Schema::drop('promocoes');
    }
}
