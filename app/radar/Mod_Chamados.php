<?php

namespace App\radar;

use Illuminate\Database\Eloquent\Model;

class Mod_Chamados extends Model
{
    protected $table = 'chamado';

    protected $primaryKey = 'id_chamado'; //É necessário especificar o nome da chave primária para o Laravel reconhecer, pois por padrão o nome é id

	protected $fillable = [
		'id_chamado','numero','dt_ini','dt_fim','id_usuario','id_cliente','id_unidade','id_equipamento','id_tecnico','ocorrencia','solucao','status','assinatura','satisfacao'
	];
}
