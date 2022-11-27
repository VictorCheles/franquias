<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVisitaTecnicasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visitas_tecnicas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('loja_id')->unsigned();
            $table->foreign('loja_id')->references('id')->on('lojas')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('user_id')->nullable()->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('agendado_por')->nullable()->unsigned();
            $table->foreign('agendado_por')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->date('data_inicio')->nullable();
            $table->date('data_termino')->nullable();
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
        Schema::drop('visitas_tecnicas');
    }
}
