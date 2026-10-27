<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Technician extends Model
{
    protected $table = 'tecnico';

    protected $primaryKey = 'id_tecnico'; //É necessário especificar o nome da chave primária para o Laravel reconhecer, pois por padrão o nome é id

    protected $fillable = [
        'id_tecnico', 'id_usuario', 'nome', 'descricao',
    ];

    public function chamados()
    {
        return $this->hasMany(Ticket::class, 'id_tecnico', 'id_tecnico');
    }

    public function usuario()
    {
        return $this->belongsTo(\App\Models\User::class, 'id_usuario', 'id');
    }
}
