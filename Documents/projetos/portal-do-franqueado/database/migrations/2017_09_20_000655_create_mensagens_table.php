<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMensagensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mensagens', function (Blueprint $table) {
            $table->increments('id');
            $table->string('subject');
            $table->text('text');
            $table->integer('folder');
            $table->json('attachments')->nullable();
            $table->integer('to_id')->nullable()->unsigned();
            $table->integer('from_id')->unsigned();
            $table->integer('message_response_id')->nullable()->unsigned();
            $table->dateTime('read_in')->nullable();
            $table->timestamps();

            $table->foreign('to_id')->references('id')->on('users');
            $table->foreign('from_id')->references('id')->on('users');
            $table->foreign('message_response_id')->references('id')->on('mensagens');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mensagens');
    }
}
