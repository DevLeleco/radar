<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'chamado';

    protected $primaryKey = 'id_chamado'; //É necessário especificar o nome da chave primária para o Laravel reconhecer, pois por padrão o nome é id

    protected $fillable = [
        'id_chamado', 'numero', 'dt_ini', 'dt_fim', 'id_usuario', 'id_cliente', 'id_unidade', 'id_equipamento', 'id_tecnico', 'ocorrencia', 'obs_cliente', 'obs_tecnico', 'solucao', 'status', 'assinatura', 'satisfacao',
    ];

    protected $casts = [
        'status' => 'integer',
        'satisfacao' => 'integer',
    ];

    public function usuario()
    {
        return $this->belongsTo(\App\Models\User::class, 'id_usuario', 'id');
    }

    public function cliente()
    {
        return $this->belongsTo(Client::class, 'id_cliente', 'id_cliente');
    }

    public function unidade()
    {
        return $this->belongsTo(Unit::class, 'id_unidade', 'id_unidade');
    }

    public function equipamento()
    {
        return $this->belongsTo(Equipment::class, 'id_equipamento', 'id_equipamento');
    }

    public function tecnico()
    {
        return $this->belongsTo(Technician::class, 'id_tecnico', 'id_tecnico');
    }

    public function ordemServico()
    {
        return $this->hasOne(WorkOrder::class, 'id_chamado', 'id_chamado');
    }
}
