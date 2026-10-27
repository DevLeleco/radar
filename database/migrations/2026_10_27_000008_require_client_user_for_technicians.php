<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RequireClientUserForTechnicians extends Migration
{
    public function up()
    {
        $invalidTechnicians = DB::table('tecnico')
            ->leftJoin('users', 'users.id', '=', 'tecnico.id_usuario')
            ->where(function ($query) {
                $query->whereNull('tecnico.id_usuario')
                    ->orWhereNull('users.id')
                    ->orWhereNull('users.perfil')
                    ->orWhere('users.perfil', '<>', 3)
                    ->orWhereNull('users.id_cliente');
            })
            ->count();

        if ($invalidTechnicians > 0) {
            throw new RuntimeException('Reconcile '.$invalidTechnicians.' technician records so each references a profile 3 user with a client before applying this migration.');
        }

        Schema::table('tecnico', function (Blueprint $table) {
            $table->dropForeign(['id_usuario']);
            $table->unsignedBigInteger('id_usuario')->nullable(false)->change();
            $table->foreign('id_usuario')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('tecnico', function (Blueprint $table) {
            $table->dropForeign(['id_usuario']);
            $table->unsignedBigInteger('id_usuario')->nullable()->change();
            $table->foreign('id_usuario')->references('id')->on('users')->onDelete('set null');
        });
    }
}
