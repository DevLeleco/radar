<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClienteAndPerfilToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('id_cliente')->nullable()->after('id');
            $table->unsignedTinyInteger('perfil')->nullable()->after('id_cliente');
            $table->foreign('id_cliente')->references('id_cliente')->on('cliente')->onDelete('set null');
            $table->index('perfil');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['id_cliente']);
            $table->dropIndex(['perfil']);
            $table->dropColumn(['id_cliente', 'perfil']);
        });
    }
}
