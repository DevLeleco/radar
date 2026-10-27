<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    protected $table = 'os';

    protected $primaryKey = 'id_os'; //É necessário especificar o nome da chave primária para o Laravel reconhecer, pois por padrão o nome é id

    protected $fillable = [
        'id_os', 'id_chamado', 'numero', 'dt_ini', 'dt_fim', 'usuario', 'cliente', 'unidade', 'equipamento', 'tecnico', 'ocorrencia', 'solucao', 'satisfacao',
    ];

    public function chamado()
    {
        return $this->belongsTo(Ticket::class, 'id_chamado', 'id_chamado');
    }
}
