<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVitrinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vitrines', function (Blueprint $table) {
            $table->increments('id');
            $table->string('titulo');
            $table->string('link')->nullable();
            $table->boolean('status')->default(true);
            $table->string('imagem');
            $table->integer('ordem')->default(1);
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
        Schema::drop('vitrines');
    }
}
