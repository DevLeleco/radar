<?php

namespace App\Http\Controllers\radar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\radar\Mod_Cliente;
use App\User;
use Illuminate\Support\Facades\Hash;

class Con_Usuario extends Controller
{
    public function form_usuario()
    {
         $usuarios = User::join('cliente', 'users.id_cliente', '=' ,'cliente.id_cliente')
                            ->select('users.id_cliente','cliente.id_cliente','users.id',
                                    'users.name','users.email','cliente.cliente','users.perfil')
                            ->orderBy('users.perfil')
                            ->get();

         $clientes = Mod_Cliente::all(); 
         return view('radar.usuario',['clientes' => $clientes, 'usuarios' => $usuarios]);
    }

    public function add_usuario(Request $request)
    {
        $usuario = new User();

		$usuario->name = $request->usuario;
		$usuario->email = $request->email;
        $usuario->id_cliente = $request->id_cliente;
        $usuario->perfil = $request->perfil;
        $usuario->password = Hash::make($request->password);

        $verusuario = User::where('name', $request->usuario)->first();
        
        if (empty(!$verusuario)){       
            return redirect()->back()->with('Alert', 'Este Usuário já existe');
         }
         else{            
            $usuario->Save();                                                   //Inserção no banco de dados
            return redirect()->back()->with('Alert', 'Cadastro Realizado com Sucesso!');
         }
    }

    public function ver_usuario($id)
    {
        $data = User::where('id', $id)->first();
	   	return response()->json($data);
    }

    public function edit_usuario(Request $request)
    {
        $verusuario = User::where('name', $request->edit_usuario)->first();
        
        if(empty(!$verusuario)){
            
            if ($verusuario->id != $request->id ){
               return redirect()->back()->with('Alert', 'Este Usuário já existe');
            }       
          }
        
        $usuario = User::find($request->id);
        $usuario->name = $request->edit_usuario;
        $usuario->id_cliente = $request->edit_id_cliente;
        $usuario->perfil = $request->edit_perfil;
        $usuario->email = $request->edit_email;
        $usuario->Save();
        return redirect()->back()->with('Alert', 'Alteração Realizada com Sucesso!');
    }

    public function delete_usuario($id)
    {
        $usuario = User::find($id);
        $usuario->delete();
        return redirect()->back()->with('Alert', 'Registro Deletado com Sucesso!');
    }

    public function reset_usuario($id, Request $request)
    {
        $reset = 'prompt@2021';

        $usuario = User::find($id);
        $usuario->password = Hash::make($reset);
        $usuario->Save();
        return redirect()->back()->with('Alert', 'Senha resetada com sucesso, nova senha "prompt@2021"');
    }

    public function dashboard()
    {
         return view('dashboard');
    }
}
