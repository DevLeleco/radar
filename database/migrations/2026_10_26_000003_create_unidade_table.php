<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnidadeTable extends Migration
{
    public function up()
    {
        Schema::create('unidade', function (Blueprint $table) {
            $table->bigIncrements('id_unidade');
            $table->unsignedBigInteger('id_cliente');
            $table->string('unidade')->unique();
            $table->text('endereco')->nullable();
            $table->text('descricao')->nullable();
            $table->timestamps();
            $table->index('id_cliente');
            $table->foreign('id_cliente')->references('id_cliente')->on('cliente')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('unidade');
    }
}
