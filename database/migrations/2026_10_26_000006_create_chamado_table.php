<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChamadoTable extends Migration
{
    public function up()
    {
        Schema::create('chamado', function (Blueprint $table) {
            $table->bigIncrements('id_chamado');
            $table->string('numero', 64)->unique();
            $table->date('dt_ini');
            $table->date('dt_fim')->nullable();
            $table->unsignedBigInteger('id_usuario')->nullable();
            $table->unsignedBigInteger('id_cliente');
            $table->unsignedBigInteger('id_unidade');
            $table->unsignedBigInteger('id_equipamento');
            $table->unsignedBigInteger('id_tecnico')->nullable();
            $table->text('ocorrencia');
            $table->text('obs_cliente')->nullable();
            $table->text('obs_tecnico')->nullable();
            $table->text('solucao')->nullable();
            $table->unsignedTinyInteger('status')->default(0);
            $table->text('assinatura')->nullable();
            $table->unsignedTinyInteger('satisfacao')->default(0);
            $table->timestamps();

            $table->index(['id_cliente', 'status']);
            $table->index(['id_unidade', 'id_equipamento']);
            $table->index('id_tecnico');
            $table->foreign('id_usuario')->references('id')->on('users')->onDelete('set null');
            $table->foreign('id_cliente')->references('id_cliente')->on('cliente')->onDelete('restrict');
            $table->foreign('id_unidade')->references('id_unidade')->on('unidade')->onDelete('restrict');
            $table->foreign('id_equipamento')->references('id_equipamento')->on('equipamento')->onDelete('restrict');
            $table->foreign('id_tecnico')->references('id_tecnico')->on('tecnico')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('chamado');
    }
}
