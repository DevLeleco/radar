<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LinkWorkOrdersToTickets extends Migration
{
    public function up()
    {
        Schema::table('os', function (Blueprint $table) {
            $table->unsignedBigInteger('id_chamado')->nullable()->unique('os_id_chamado_unique');
            $table->foreign('id_chamado', 'os_chamado_foreign')
                ->references('id_chamado')->on('chamado')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::table('os', function (Blueprint $table) {
            $table->dropForeign('os_chamado_foreign');
            $table->dropUnique('os_id_chamado_unique');
            $table->dropColumn('id_chamado');
        });
    }
}
