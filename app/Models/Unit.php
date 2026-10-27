<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $table = 'unidade';

    protected $primaryKey = 'id_unidade'; //É necessário especificar o nome da chave primária para o Laravel reconhecer, pois por padrão o nome é id

    protected $fillable = [
        'id_unidade', 'unidade', 'id_cliente', 'endereco', 'descricao',
    ];

    public function cliente()
    {
        return $this->belongsTo(Client::class, 'id_cliente', 'id_cliente');
    }

    public function equipamentos()
    {
        return $this->hasMany(Equipment::class, 'id_unidade', 'id_unidade');
    }

    public function chamados()
    {
        return $this->hasMany(Ticket::class, 'id_unidade', 'id_unidade');
    }
}
