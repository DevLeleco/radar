<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOsTable extends Migration
{
    public function up()
    {
        Schema::create('os', function (Blueprint $table) {
            $table->bigIncrements('id_os');
            $table->string('numero', 64)->unique();
            $table->date('dt_ini');
            $table->date('dt_fim')->nullable();
            $table->string('usuario');
            $table->string('cliente');
            $table->string('unidade');
            $table->string('equipamento')->nullable();
            $table->string('tecnico');
            $table->text('ocorrencia');
            $table->text('solucao');
            $table->unsignedTinyInteger('satisfacao')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('os');
    }
}
