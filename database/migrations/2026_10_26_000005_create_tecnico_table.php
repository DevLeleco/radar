<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTecnicoTable extends Migration
{
    public function up()
    {
        Schema::create('tecnico', function (Blueprint $table) {
            $table->bigIncrements('id_tecnico');
            $table->unsignedBigInteger('id_usuario')->nullable()->unique();
            $table->string('nome')->unique();
            $table->text('descricao')->nullable();
            $table->timestamps();
            $table->foreign('id_usuario')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tecnico');
    }
}
