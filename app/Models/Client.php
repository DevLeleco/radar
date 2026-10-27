<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'cliente';

    protected $primaryKey = 'id_cliente'; //É necessário especificar o nome da chave primária para o Laravel reconhecer, pois por padrão o nome é id

    protected $fillable = [
        'id_cliente', 'cliente', 'cnpj_cpf', 'seguimento', 'endereco',
    ];

    public function unidades()
    {
        return $this->hasMany(Unit::class, 'id_cliente', 'id_cliente');
    }

    public function usuarios()
    {
        return $this->hasMany(\App\Models\User::class, 'id_cliente', 'id_cliente');
    }

    public function chamados()
    {
        return $this->hasMany(Ticket::class, 'id_cliente', 'id_cliente');
    }
}
