<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MigrateCupomLojaId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\Models\Cupom::where('user_id', '!=', null)->get()->each(function (\App\Models\Cupom $cupom) {
            $cupom->loja_id = $cupom->user->loja_id;
            $cupom->save();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \App\Models\Cupom::where('user_id', '!=', null)->get()->each(function (\App\Models\Cupom $cupom) {
            $cupom->loja_id = null;
            $cupom->save();
        });
    }
}
