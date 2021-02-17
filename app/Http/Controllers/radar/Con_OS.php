<?php

namespace App\Http\Controllers\radar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\radar\Mod_OS;

class Con_OS extends Controller
{
    public function form_os()
    {
         $oss = Mod_OS::all();
         return view('radar.os',['oss' => $oss]);
    }

    public function add_os(Request $request)
    {
        $os = new Mod_OS();

		$os->numero = $request->numero;
		$os->dt_ini = $request->dt_ini;
        $os->dt_fim = $request->dt_fim;
        $os->usuario = $request->usuario;
        $os->cliente = $request->cliente;
        $os->unidade = $request->unidade;
		$os->equipamento = $request->equipamento;
        $os->tecnico = $request->tecnico;
        $os->ocorrencia = $request->ocorrencia;
        $os->solucao = $request->solucao;
        $os->satisfacao = $request->satisfacao;

        $veros = Mod_OS::where('numero', $request->numero)->first();
        
        if (empty(!$veros)){       
            return redirect()->back()->with('Alert', 'Já existe uma OS com este Numero');
        }
        else{            
            $os->Save();                                                   //Inserção no banco de dados
            return redirect()->back()->with('Alert', 'Cadastro Realizado com Sucesso!');
        }
    }

    public function ver_os($id_os)
    {
        $data = Mod_OS::where('id_os', $id_os)->first();
	   	return response()->json($data);
    }

    public function edit_os(Request $request)
    {
        $veros = Mod_OS::where('numero', $request->edit_numero)->first();
        
        if(empty(!$veros)){
            
            if ($veros->id_os != $request->edit_id_os){
               return redirect()->back()->with('Alert', 'Já existe uma OS com este Numero');
            }       
          }
        
        $os = Mod_OS::find($request->edit_id_os);
        $os->numero = $request->edit_numero;
		$os->dt_ini = $request->edit_dt_ini;
        $os->dt_fim = $request->edit_dt_fim;
        $os->usuario = $request->edit_usuario;
        $os->cliente = $request->edit_cliente;
        $os->unidade = $request->edit_unidade;
		$os->equipamento = $request->edit_equipamento;
        $os->tecnico = $request->edit_tecnico;
        $os->ocorrencia = $request->edit_ocorrencia;
        $os->solucao = $request->edit_solucao;
        $os->satisfacao = $request->edit_satisfacao;
        $os->Save();
        return redirect()->back()->with('Alert', 'Alteração Realizada com Sucesso!');
    }

    public function delete_os($id_os)
    {
        $os = Mod_OS::find($id_os);
        $os->delete();
        return redirect()->back()->with('Alert', 'Registro Deletado com Sucesso!');
    }
}