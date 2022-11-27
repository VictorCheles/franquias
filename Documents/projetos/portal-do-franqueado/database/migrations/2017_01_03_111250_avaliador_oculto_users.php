<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AvaliadorOcultoUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('avaliador_oculto_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome');
            $table->string('cpf', 20)->unique();
            $table->string('rg', 20)->nullable();
            $table->date('data_nascimento')->nullable();
            $table->integer('escolaridade')->nullable();
            $table->integer('banco_id')->nullable()->unsigned();
            $table->foreign('banco_id')->references('id')->on('bancos')->onDelete('cascade')->onUpdate('set null');
            $table->string('agencia')->nullable();
            $table->string('conta_corrente')->nullable();
            $table->string('cidade')->nullable();
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->integer('aceite')->default(0);
            $table->string('foto')->nullable();
            $table->date('data_aceite')->nullable()->default(null);

            $table->rememberToken();
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
        Schema::drop('avaliador_oculto_users');
    }
}
