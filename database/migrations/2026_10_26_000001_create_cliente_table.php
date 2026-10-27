<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClienteTable extends Migration
{
    public function up()
    {
        Schema::create('cliente', function (Blueprint $table) {
            $table->bigIncrements('id_cliente');
            $table->string('cliente')->unique();
            $table->string('cnpj_cpf', 32)->nullable();
            $table->string('seguimento', 120)->nullable();
            $table->text('endereco')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cliente');
    }
}
