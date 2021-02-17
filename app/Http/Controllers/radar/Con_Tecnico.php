<?php

namespace App\Http\Controllers\radar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\radar\Mod_Tecnico;
use App\User;


class Con_Tecnico extends Controller
{
    public function form_tecnico()
    {
        $usuarios = User::all(); 
        $tecnicos = Mod_Tecnico::all();
        // $tecnicos = Mod_Tecnico::join('users', 'tecnico.id_usuario', '=' ,'users.id')
        //                     ->select('users.id','tecnico.id_tecnico','tecnico.nome',
        //                             'tecnico.descricao')
        //                     ->orderBy('tecnico.id_usuario')
        //                     ->get(); 

        return view('radar.tecnico',['tecnicos' => $tecnicos ,'usuarios' => $usuarios]);
    }

    public function add_tecnico(Request $request)
    {
        $tecnico = new Mod_Tecnico();

		$tecnico->nome = $request->nome;
        $tecnico->descricao = $request->descricao;
        

        $vertecnico = Mod_Tecnico::where('nome', $request->nome)->first();
        
        if (empty(!$vertecnico))
        {       
            return redirect()->back()->with('Alert', 'Este Técnico já existe');
        }
        else
        {            
            $tecnico->Save();                                                   //Inserção no banco de dados
            return redirect()->back()->with('Alert', 'Cadastro Realizado com Sucesso!');
        }
    }

    public function delete_tecnico($id_tecnico)
    {
        $tecnico = Mod_Tecnico::find($id_tecnico);
        $tecnico->delete();
        return redirect()->back()->with('Alert', 'Registro Deletado com Sucesso!');
    }
}