<?php

namespace App\Http\Controllers\radar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\radar\Mod_Unidade;
use App\radar\Mod_Cliente;
use App\radar\Mod_Equipamento;

class Con_Unidade extends Controller
{
    public function form_unidade()
    {
         $unidades = Mod_Unidade::join('cliente', 'unidade.id_cliente', '=' ,'cliente.id_cliente')
                                ->select('unidade.id_cliente','cliente.id_cliente','cliente.cliente',
                                'unidade.id_unidade','unidade.unidade','unidade.endereco','unidade.descricao')
                                ->orderBy('unidade.id_unidade')
                                ->get();


         $clientes = Mod_Cliente::all(); // join??
         return view('radar.unidade', ['unidades' => $unidades, 'clientes' => $clientes]);
    }

    public function add_unidade(Request $request)
    {
        $unidade = new Mod_Unidade();
        $unidade->unidade = $request->unidade;
        $unidade->id_cliente = $request->id_cliente;
		$unidade->endereco = $request->endereco;
        $unidade->descricao = $request->descricao;

        $verunidade = Mod_Unidade::where('unidade', $request->unidade)->first();
        
        if (empty(!$verunidade)){       
            return redirect()->back()->with('Alert', 'Esta Unidade já existe');
         }
         else{            
            $unidade->Save();                                                   //Inserção no banco de dados
            return redirect()->back()->with('Alert', 'Cadastro Realizado com Sucesso!');
         }
    }

    public function select_equipamento(Request $request)
    {
        $equipamentos = Mod_Equipamento::where('id_unidade', $request->get('id') )->get();
        $output = [];
        foreach( $equipamentos as $equipamento )
        {
           $output[$equipamento->id_equipamento] = $equipamento->equipamento;
        }
        return $output;
    }

    public function ver_unidade($id_unidade)
    {
        $data = Mod_Unidade::join('cliente', 'unidade.id_cliente', '=' ,'cliente.id_cliente')
                                ->select('unidade.id_cliente','cliente.id_cliente','cliente.cliente',
                                'unidade.id_unidade','unidade.unidade','unidade.endereco','unidade.descricao')
                                ->where('id_unidade', $id_unidade)
                                ->first();
                                
	   	return response()->json($data);
    }

    public function edit_unidade(Request $request)
    {
        $verunidade = Mod_Unidade::where('unidade', $request->edit_unidade)->first();
        if(empty(!$verunidade)){            
            if ($verunidade->id_unidade != $request->edit_id_unidade ){
               return redirect()->back()->with('Alert', 'Esta Unidade já existe');
            }       
          }
        
        $unidade = Mod_Unidade::find($request->edit_id_unidade);
        $unidade->unidade = $request->edit_unidade;
        $unidade->id_cliente = $request->edit_id_cliente;
		$unidade->endereco = $request->edit_endereco;
        $unidade->descricao = $request->edit_descricao;
        $unidade->Save();
        return redirect()->back()->with('Alert', 'Alteração Realizada com Sucesso!');
    }

    public function delete_unidade($id_unidade)
    {
        $unidade = Mod_Unidade::find($id_unidade);
        $verequipamento = Mod_Equipamento::join('unidade', 'unidade.id_unidade', '=' ,'equipamento.id_unidade')
                                        ->where('equipamento.id_unidade', $id_unidade)
                                        ->first();
        
        if (empty(!$verequipamento)){       
            return redirect()->back()->with('Alert', 'Não é possivel Deletar, Existem Equipamentos Vinculados a esta Unidade');
         }
         else{            
            $unidade->delete();
            return redirect()->back()->with('Alert', 'Registro Deletado com Sucesso!');
         }
    }

    
    



}
