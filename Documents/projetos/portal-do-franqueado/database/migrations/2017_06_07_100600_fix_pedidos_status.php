<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixPedidosStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\Models\Pedido::whereStatus(\App\Models\Pedido::STATUS_CONCLUIDO)->get()->each(function (\App\Models\Pedido $pedido) {
            $pedido->recebido_em = \Carbon\Carbon::now();
            $pedido->recebido_por_id = \App\User::find(1)->id;
            $pedido->save();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \App\Models\Pedido::whereStatus(\App\Models\Pedido::STATUS_RECEBIDO)->get()->each(function (\App\Models\Pedido $pedido) {
            $pedido->recebido_em = null;
            $pedido->recebido_por_id = null;
            $pedido->status = \App\Models\Pedido::STATUS_CONCLUIDO;
            $pedido->save();
        });
    }
}
