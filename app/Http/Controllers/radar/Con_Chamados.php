<?php

namespace App\Http\Controllers\radar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\radar\Mod_Chamados;
use App\radar\Mod_Cliente;

class Con_Chamados extends Controller
{
    public function form_chamados() 
    {
        $usuario = 1;                      // Será o id do usuario logado
        $clientes = Mod_Cliente::join('users', 'cliente.id_cliente', '=', 'users.id_cliente')
                                ->select('cliente.id_cliente','cliente.cliente')
                                ->where('users.id', '=', $usuario)
                                ->get();

        $chamados = Mod_Chamados::join('cliente', 'chamado.id_cliente', '=', 'cliente.id_cliente')
                                ->join('unidade', 'chamado.id_unidade', '=', 'unidade.id_unidade')
                                ->select('chamado.id_chamado','cliente.cliente', 'unidade.unidade','chamado.numero','chamado.dt_ini','chamado.status')
                                ->where('chamado.id_usuario', '=', $usuario)
                                ->where('chamado.status', '<', 2)
                                ->orderBy('chamado.status')
                                ->get();

         return view('radar.chamados',['chamados' => $chamados, 'clientes' => $clientes]);
    }

    public function add_chamados(Request $request)
    {
        $usuario = 1;                       // Será o id do usuario logado

        // verifica o ultimo chamado registado, pelo id_cliente 
        $lastchamado = Mod_Chamados::where('id_cliente', $request->id_cliente)->orderBy('id_chamado','DESC')->first();
        
        if (empty($lastchamado)){
            /*quando retorna vazio(não ha chamados do cliente), então concatena o id_cliente ao valor 1,
             gerando o primeiro registro. */
            $newchamado = $request->id_cliente . 1;
        }
        else{ 
            // verifica a qtd de caractres do id_ciente, e subtrai do 'numero' do chamado que vem do bd
            $newchamado = substr($lastchamado->numero, strlen($request->id_cliente));
            
            /* concatena o id_cliente à soma do 'numero' do chamado(converte de str para int),
            preservando o prefixo que é o id_cliente mais o numero do proximo chamado */
            $newchamado = $lastchamado->id_cliente . (intval($newchamado) + 1);
        }

        $chamado = new Mod_Chamados();
        $chamado->numero = $newchamado; 
        $chamado->dt_ini = date("Y-m-d");
        $chamado->id_usuario = $usuario;    // Será o id do usuario logado
        $chamado->id_cliente = $request->id_cliente;
        $chamado->id_unidade = $request->id_unidade;
        $chamado->id_equipamento = $request->id_equipamento;
        $chamado->ocorrencia = $request->ocorrencia;
        $chamado->status = 0;
        $chamado->satisfacao = 0;
        $chamado->Save();
                                                        
        return redirect()->back()->with('Alert', 'Cadastro Realizado com Sucesso!');
    }

    public function ver_chamados($id_chamado)//checar o nome do usuari0, conflito nos relacionamentos.
    {
        
        $data = Mod_Chamados::join('cliente', 'chamado.id_cliente', '=' ,'cliente.id_cliente')
                            ->join('unidade', 'chamado.id_unidade', '=', 'unidade.id_unidade')
                            ->join('equipamento', 'chamado.id_equipamento', '=', 'equipamento.id_equipamento')
                            ->join('users','chamado.id_usuario','=','users.id')
                            //->join('tecnico', 'chamado.id_tecnico', '=','tecnico.id_tecnico')
                            ->select('chamado.id_chamado','cliente.cliente','unidade.unidade','equipamento.equipamento','chamado.ocorrencia','chamado.obs_cliente','chamado.obs_tecnico','chamado.numero','chamado.dt_ini','users.name')
                            ->where('chamado.id_chamado', $id_chamado)
                            ->first();
                               
	   	return response()->json($data);
    }

    public function edit_chamados(Request $request)
    {
        $chamado = Mod_Chamados::find($request->edit_id_chamado);
        $chamado->obs_tecnico = $request->edit_observacao;
        $chamado->Save();
        return redirect()->back()->with('Alert', 'Alteração Realizada com Sucesso!');
    }

    public function historico_chamados()
    {
        
        $chamados = Mod_Chamados::join('cliente', 'chamado.id_cliente', '=', 'cliente.id_cliente')
                                ->join('unidade', 'chamado.id_unidade', '=', 'unidade.id_unidade')
                                ->join('tecnico', 'chamado.id_tecnico', '=','tecnico.id_tecnico')
                                ->select('chamado.id_chamado','cliente.cliente', 'unidade.unidade','chamado.numero','chamado.dt_ini','chamado.status')
                                ->where('chamado.status', '=', 2)
                                ->orderBy('chamado.dt_ini')
                                ->get();

         return view('radar.historico',['chamados' => $chamados]);
    }

    public function tec_chamados()
    {
        $chamados = Mod_Chamados::join('cliente', 'chamado.id_cliente', '=', 'cliente.id_cliente')
                                ->join('unidade', 'chamado.id_unidade', '=', 'unidade.id_unidade')
                                ->select('chamado.id_chamado','cliente.cliente', 'unidade.unidade','chamado.numero','chamado.dt_ini','chamado.status')
                                ->where('chamado.status', '<', 2)
                                ->orderBy('chamado.dt_ini')
                                ->get();

         return view('radar.tec_chamados',['chamados' => $chamados]);
    }

    public function view_chamados($id_chamado)
    {
        $chamados = Mod_Chamados::join('cliente', 'chamado.id_cliente', '=' ,'cliente.id_cliente')
                                ->join('unidade', 'chamado.id_unidade', '=', 'unidade.id_unidade')
                                ->join('equipamento', 'chamado.id_equipamento', '=', 'equipamento.id_equipamento')
                                ->join('users','chamado.id_usuario','=','users.id')
                                //->join('tecnico', 'chamado.id_tecnico', '=','tecnico.id_tecnico')
                                ->select('chamado.id_chamado','cliente.cliente','unidade.unidade','equipamento.equipamento','chamado.ocorrencia','chamado.obs_tecnico','chamado.numero','chamado.dt_ini','chamado.status','users.name')
                                ->where('chamado.id_chamado', $id_chamado)
                                ->get();
                                

        return view('radar.view_chamados', ['chamados' => $chamados]);
    }

    public function close_chamados(Request $request)
    {
        $chamado = Mod_Chamados::find($request->id_chamado);
        $chamado->id_tecnico = 8;
        $chamado->solucao = $request->solucao;
        $chamado->status = $request->status;
        $chamado->obs_tecnico = $request->observacao;
        $chamado->Save();
        return redirect()->route('rt.tec_chamados');
    }

    public function delete_chamados($id_chamado)
    {
        $chamado = Mod_Chamados::find($id_chamado);

        if (!empty($chamado->id_tecnico)){
            return redirect()->back()->with('Alert', 'Chamado em atendimento, por favor entre em contato com o Suporte, para o Cancelamento!');
        }
        $chamado->delete();
        return redirect()->back()->with('Alert', 'Chamado Cancelado com Sucesso!');
    }



}
// Falta tratar a "Obs" "Solução" e "Assinatura"