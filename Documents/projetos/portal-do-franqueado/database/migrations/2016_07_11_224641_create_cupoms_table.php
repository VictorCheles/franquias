<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCupomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cupons', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('code', 20)->nullable();
            $table->string('cliente_email', 100);
            $table->foreign('cliente_email')->references('email')->on('clientes');
            $table->integer('promocao_id')->unsigned();
            $table->foreign('promocao_id')->references('id')->on('promocoes');
            $table->integer('validade_cupom');
            $table->boolean('status')->default(true);
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /** update promocoes set cupons_criados = 0, cupons_usados = 0;
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('cupons');
    }
}
