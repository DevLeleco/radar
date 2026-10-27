<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    protected $table = 'equipamento';

    protected $primaryKey = 'id_equipamento'; //É necessário especificar o nome da chave primária para o Laravel reconhecer, pois por padrão o nome é id

    protected $fillable = [
        'id_equipamento', 'equipamento', 'id_unidade', 'descricao',
    ];

    public function unidade()
    {
        return $this->belongsTo(Unit::class, 'id_unidade', 'id_unidade');
    }

    public function chamados()
    {
        return $this->hasMany(Ticket::class, 'id_equipamento', 'id_equipamento');
    }
}
