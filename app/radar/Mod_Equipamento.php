<?php

namespace App\radar;

use Illuminate\Database\Eloquent\Model;

class Mod_Equipamento extends Model
{
    protected $table = 'equipamento';

    protected $primaryKey = 'id_equipamento'; //É necessário especificar o nome da chave primária para o Laravel reconhecer, pois por padrão o nome é id

	protected $fillable = [
		'id_equipamento','equipamento','id_unidade','descricao'
	];
}

