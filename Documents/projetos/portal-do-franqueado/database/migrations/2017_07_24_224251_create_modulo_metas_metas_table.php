<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModuloMetasMetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('metas')->create('metas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('titulo', 100);
            $table->dateTime('inicio');
            $table->dateTime('fim');
            $table->decimal('valor');
            $table->integer('metrica');
            $table->integer('loja_id')->unsigned();
            $table->timestamps();

            $table->foreign('loja_id')->references('id')->on('lojas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('metas')->drop('metas');
    }
}
