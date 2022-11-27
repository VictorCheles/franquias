<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VisitaTecnicaItemNotificacao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visita_tecnica_item_notificacao', function (Blueprint $table) {
            $table->integer('visita_tecnica_id')->unsigned();
            $table->foreign('visita_tecnica_id')->references('id')->on('visitas_tecnicas');

            $table->integer('item_notificacao_id')->unsigned();
            $table->foreign('item_notificacao_id')->references('id')->on('item_notificacao');

            $table->decimal('item_notificacao_valor', 10, 4);
            $table->integer('item_notificacao_quantidade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('visita_tecnica_item_notificacao');
    }
}
