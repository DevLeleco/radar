<?php

namespace App\radar;

use Illuminate\Database\Eloquent\Model;

class Mod_OS extends Model
{
    protected $table = 'os';

    protected $primaryKey = 'id_os'; //É necessário especificar o nome da chave primária para o Laravel reconhecer, pois por padrão o nome é id

	protected $fillable = [
		'id_os','numero','dt_ini','dt_fim','usuario','cliente','unidade','equipamento','tecnico','ocorrencia','solucao','satisfacao'
	];
}
