<?php

namespace App\Http\Controllers\radar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\radar\Mod_Cliente;
use App\radar\Mod_Unidade;
use App\User;

class Con_Cliente extends Controller
{
    public function form_cliente()
    {
         $clientes = Mod_Cliente::all(); 
         return view('radar.cliente', ['clientes' => $clientes]);
    }

    public function add_cliente(Request $request)
    {
        $cliente = new Mod_Cliente();

		$cliente->cliente = $request->cliente;
		$cliente->cnpj_cpf = $request->cnpj_cpf;
        $cliente->seguimento = $request->seguimento;
        $cliente->endereco = $request->endereco;

        $vercliente = Mod_Cliente::where('cliente', $request->cliente)->first();
        
        if (empty(!$vercliente)){       
            return redirect()->back()->with('Alert', 'Este Cliente já existe');
        } 
        else {            
            $cliente->Save();                                                   //Inserção no banco de dados
            return redirect()->back()->with('Alert', 'Cadastro Realizado com Sucesso!');
         }
    }

    public function ver_cliente($id_cliente)
    {
        $data = Mod_Cliente::where('id_cliente', $id_cliente)->first();
	   	return response()->json($data);
    }

    public function edit_cliente(Request $request)
    {
        $vercliente = Mod_Cliente::where('cliente', $request->edit_cliente)->first();
        
        if(empty(!$vercliente)){
            if ($vercliente->id_cliente != $request->edit_id_cliente ){
               return redirect()->back()->with('Alert', 'Este Cliente já existe');
            }       
        }
        
        $cliente = Mod_Cliente::find($request->edit_id_cliente);
        $cliente->cliente = $request->edit_cliente;
        $cliente->cnpj_cpf = $request->edit_cnpj_cpf;
        $cliente->seguimento = $request->edit_seguimento;
        $cliente->endereco = $request->edit_endereco;
        $cliente->Save();
        return redirect()->back()->with('Alert', 'Alteração Realizada com Sucesso!');
    }

    public function delete_cliente($id_cliente) //checar se usuario exite antes de deletar
    {
        $cliente = Mod_Cliente::find($id_cliente);

        $verunidade = Mod_Unidade::join('cliente', 'unidade.id_cliente', '=' ,'cliente.id_cliente')
                                        ->where('cliente.id_cliente', $id_cliente)
                                        ->first();

        $verusuario = User::join('cliente', 'users.id_cliente', '=' ,'cliente.id_cliente')
                                        ->where('cliente.id_cliente', $id_cliente)
                                        ->first();

        if (empty(!$verunidade)){       
            return redirect()->back()->with('Alert', 'Não é possivel Deletar, Existem Unidades Vinculadas a este Cliente');
        }
        elseif(empty(!$verusuario)){
            return redirect()->back()->with('Alert', 'Não é possivel Deletar, Existem Usuarios Vinculadas a este Cliente');
        }  
        else {            
            $cliente->delete();
            return redirect()->back()->with('Alert', 'Registro Deletado com Sucesso!');
        }
    }
}
