<?php

namespace App\radar;

use Illuminate\Database\Eloquent\Model;

class Mod_Unidade extends Model
{
    protected $table = 'unidade';

    protected $primaryKey = 'id_unidade'; //É necessário especificar o nome da chave primária para o Laravel reconhecer, pois por padrão o nome é id

	protected $fillable = [
		'id_unidade','unidade','id_cliente','endereco','descricao'
	];
}
