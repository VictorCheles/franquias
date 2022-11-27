<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableUsersLojas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_lojas', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id', 'fk_users_lojas_user')->references('id')->on('users');
            $table->integer('loja_id')->unsigned();
            $table->foreign('loja_id', 'fk_users_lojas_loja')->references('id')->on('lojas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users_lojas', function (Blueprint $table) {
            Schema::drop('users_lojas');
        });
    }
}
