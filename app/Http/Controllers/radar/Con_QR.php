<?php

namespace App\Http\Controllers\radar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\radar\Mod_Cliente;
use App\radar\Mod_Equipamento;

class Con_QR extends Controller
{
    public function form_qr()
    {
         $clientes = Mod_Cliente::all(); 
         return view('radar.view_qr', ['clientes' => $clientes]);
    }

    public function view_qr(Request $request)
	{
        $view_id_unidade = $request->id_unidade;

        $equipamentos = Mod_Equipamento::join('unidade','equipamento.id_unidade', '=' ,'unidade.id_unidade')
						->select('equipamento.id_equipamento','equipamento.equipamento')
                        ->where('equipamento.id_unidade', $view_id_unidade )
						->get();

       
		return view('radar.qr',['equipamentos' => $equipamentos]);			
	}




}
