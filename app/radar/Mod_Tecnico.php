<?php

namespace App\radar;

use Illuminate\Database\Eloquent\Model;

class Mod_Tecnico extends Model
{
    protected $table = 'tecnico';

    protected $primaryKey = 'id_tecnico'; //É necessário especificar o nome da chave primária para o Laravel reconhecer, pois por padrão o nome é id

	protected $fillable = [
		'id_tecnico','id_usuario','descricao'
	];
}
