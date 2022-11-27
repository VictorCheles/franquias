<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MigrateUserLoja extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\User::all()->each(function (\App\User $user) {
            $user->lojas()->attach($user->loja_id);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \App\User::all()->each(function (\App\User $user) {
            $user->lojas()->detach();
        });
    }
}
