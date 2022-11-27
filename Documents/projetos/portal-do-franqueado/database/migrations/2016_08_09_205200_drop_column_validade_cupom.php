<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnValidadeCupom extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('promocoes', function (Blueprint $table) {
            $table->dropColumn('validade_cupom')->nullable();
        });
        Schema::table('cupons', function (Blueprint $table) {
            $table->dropColumn('validade_cupom')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('promocoes', function (Blueprint $table) {
            $table->integer('validade_cupom')->nullable();
        });
        Schema::table('cupons', function (Blueprint $table) {
            $table->integer('validade_cupom')->nullable();
        });
    }
}
