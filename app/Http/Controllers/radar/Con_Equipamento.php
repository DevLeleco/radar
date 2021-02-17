<?php

namespace App\Http\Controllers\radar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\radar\Mod_Equipamento;
use App\radar\Mod_Unidade;
use App\radar\Mod_Cliente;

class Con_Equipamento extends Controller
{
    public function form_equipamento()
    {
         $equipamentos = Mod_Equipamento::join('unidade', 'equipamento.id_unidade', '=' ,'unidade.id_unidade')
                                ->join('cliente', 'unidade.id_cliente', '=' ,'cliente.id_cliente')
                                ->select('equipamento.id_equipamento','equipamento.id_unidade','equipamento.equipamento','unidade.unidade',
                                'unidade.id_unidade','equipamento.descricao', 'cliente.cliente')
                                ->orderBy('unidade.id_cliente')
                                ->get();

        $clientes = Mod_Cliente::all();
        return view('radar.equipamento', ['clientes' => $clientes, 'equipamentos' => $equipamentos]);
    }

    public function add_equipamento(Request $request)
    {
        $equipamento = new Mod_Equipamento();

        $equipamento->id_unidade = $request->id_unidade;
        $equipamento->equipamento = $request->equipamento;
        $equipamento->descricao = $request->descricao;

        $verequipamento = Mod_Equipamento::where('equipamento', $request->equipamento)->first();
        
        if (empty(!$verequipamento))
        {       
            return redirect()->back()->with('Alert', 'Este Equipamento já existe');
        }
        else
        {            
            $equipamento->Save();                                                   //Inserção no banco de dados
            return redirect()->back()->with('Alert', 'Cadastro Realizado com Sucesso!');
        }
    }

    public function ver_equipamento($id_equipamento)
    {
        //$data = Mod_Unidade::where('id_unidade', $id_unidade)->first(); //join??
            $data = Mod_Equipamento::join('unidade', 'equipamento.id_unidade', '=' ,'unidade.id_unidade')
                                ->select('equipamento.id_equipamento','equipamento.equipamento','equipamento.descricao','unidade.id_unidade','unidade.unidade')
                                ->where('id_equipamento', $id_equipamento)
                                ->first();
                                
	   	return response()->json($data);
    }
    public function select_unidade(Request $request)
    {
        $unidades = Mod_Unidade::where('id_cliente', $request->get('id') )->get();
        $output = [];
        foreach( $unidades as $unidade )
        {
           $output[$unidade->id_unidade] = $unidade->unidade;
        }
        return $output;
    }

    public function edit_equipamento(Request $request)
    {
        $verequipamento = Mod_Equipamento::where('equipamento', $request->edit_equipamento)->first();
        if(empty(!$verequipamento)){            
            if ($verequipamento->id_equipamento != $request->edit_id_equipamento ){
               return redirect()->back()->with('Alert', 'Este Equipamento já existe');
            }       
          }
        
        $equipamento = Mod_Equipamento::find($request->edit_id_equipamento);
        $equipamento->equipamento = $request->edit_equipamento;
        $equipamento->id_unidade = $request->edit_id_unidade;
        $equipamento->descricao = $request->edit_descricao;
        $equipamento->Save();
        return redirect()->back()->with('Alert', 'Alteração Realizada com Sucesso!');
    }

    public function delete_equipamento($id_equipamento)
    {
        $equipamento = Mod_Equipamento::find($id_equipamento);
        $equipamento->delete();
        return redirect()->back()->with('Alert', 'Registro Deletado com Sucesso!');

    }
}