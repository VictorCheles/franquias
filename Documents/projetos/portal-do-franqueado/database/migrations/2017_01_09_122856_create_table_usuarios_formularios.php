<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableUsuariosFormularios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('avaliador_oculto_usuarios_formularios', function (Blueprint $table) {
            $table->integer('user_id')->nullable()->unsigned();
            $table->foreign('user_id')->references('id')->on('avaliador_oculto_users')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('formulario_id')->nullable()->unsigned();
            $table->foreign('formulario_id')->references('id')->on('avaliador_oculto_formularios')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('loja_id')->nullable()->unsigned();
            $table->foreign('loja_id')->references('id')->on('lojas')->onUpdate('cascade')->onDelete('cascade');
            $table->text('observacoes')->nullable();
            $table->string('foto_comprovante')->nullable();
            $table->boolean('finalizou')->default(false);
            $table->date('data_termino')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('avaliador_oculto_usuarios_formularios');
    }
}
