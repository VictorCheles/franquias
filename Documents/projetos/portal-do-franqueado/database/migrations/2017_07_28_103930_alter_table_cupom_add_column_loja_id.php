<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableCupomAddColumnLojaId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cupons', function (Blueprint $table) {
            $table->integer('loja_id')->nullable()->unsigned();
            $table->foreign('loja_id', 'fk_cupon_loja')->references('id')->on('lojas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cupons', function (Blueprint $table) {
            $table->dropForeign('fk_cupon_loja');
            $table->dropColumn('loja_id');
        });
    }
}
