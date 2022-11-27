<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModuloMetasAtividadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('metas')->create('atividades', function (Blueprint $table) {
            $table->increments('id');
            $table->text('descricao')->nullable();
            $table->decimal('valor');
            $table->integer('meta_id')->unsigned();
            $table->timestamps();

            $table->foreign('meta_id')->references('id')->on('metas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('metas')->drop('atividades');
    }
}
