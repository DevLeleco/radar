<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipamentoTable extends Migration
{
    public function up()
    {
        Schema::create('equipamento', function (Blueprint $table) {
            $table->bigIncrements('id_equipamento');
            $table->unsignedBigInteger('id_unidade');
            $table->string('equipamento')->unique();
            $table->text('descricao')->nullable();
            $table->timestamps();
            $table->index('id_unidade');
            $table->foreign('id_unidade')->references('id_unidade')->on('unidade')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('equipamento');
    }
}
