<?php

namespace App\radar;

use Illuminate\Database\Eloquent\Model;

class Mod_Cliente extends Model
{
    protected $table = 'cliente';

    protected $primaryKey = 'id_cliente'; //É necessário especificar o nome da chave primária para o Laravel reconhecer, pois por padrão o nome é id

	protected $fillable = [
		'id_cliente','cliente','cnpj_cpf','seguimento','endereco'
	];
}
