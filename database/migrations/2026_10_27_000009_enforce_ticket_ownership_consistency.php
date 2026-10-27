<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class EnforceTicketOwnershipConsistency extends Migration
{
    public function up()
    {
        $unitMismatch = DB::table('chamado')
            ->join('unidade', 'chamado.id_unidade', '=', 'unidade.id_unidade')
            ->whereColumn('chamado.id_cliente', '<>', 'unidade.id_cliente')
            ->exists();
        $equipmentMismatch = DB::table('chamado')
            ->join('equipamento', 'chamado.id_equipamento', '=', 'equipamento.id_equipamento')
            ->whereColumn('chamado.id_unidade', '<>', 'equipamento.id_unidade')
            ->exists();

        if ($unitMismatch || $equipmentMismatch) {
            throw new RuntimeException('Não foi possível reforçar a integridade: existem chamados com cliente, unidade ou equipamento incompatíveis. Corrija os dados antes de executar esta migração.');
        }

        Schema::table('unidade', function (Blueprint $table) {
            $table->unique(['id_unidade', 'id_cliente'], 'unidade_id_cliente_unique');
        });
        Schema::table('equipamento', function (Blueprint $table) {
            $table->unique(['id_equipamento', 'id_unidade'], 'equipamento_id_unidade_unique');
        });
        Schema::table('chamado', function (Blueprint $table) {
            $table->foreign(['id_unidade', 'id_cliente'], 'chamado_unidade_cliente_foreign')
                ->references(['id_unidade', 'id_cliente'])->on('unidade')->onDelete('restrict');
            $table->foreign(['id_equipamento', 'id_unidade'], 'chamado_equipamento_unidade_foreign')
                ->references(['id_equipamento', 'id_unidade'])->on('equipamento')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::table('chamado', function (Blueprint $table) {
            $table->dropForeign('chamado_unidade_cliente_foreign');
            $table->dropForeign('chamado_equipamento_unidade_foreign');
        });
        Schema::table('equipamento', function (Blueprint $table) {
            $table->dropUnique('equipamento_id_unidade_unique');
        });
        Schema::table('unidade', function (Blueprint $table) {
            $table->dropUnique('unidade_id_cliente_unique');
        });
    }
}
