<?php

namespace App\Http\Controllers\Radar;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::all();

        return view('operations.clients', ['clients' => $clients]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'cliente' => 'required|string|max:255|unique:cliente,cliente',
            'cnpj_cpf' => 'nullable|string|max:32',
            'seguimento' => 'nullable|string|max:120',
            'endereco' => 'nullable|string|max:10000',
        ]);
        Client::create($data);

        return redirect()->back()->with('Alert', 'Cadastro Realizado com Sucesso!');
    }

    public function show($clientId)
    {
        $data = Client::findOrFail($clientId);

        return response()->json($data);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'edit_id_cliente' => 'required|integer|exists:cliente,id_cliente',
            'edit_cliente' => ['required', 'string', 'max:255', Rule::unique('cliente', 'cliente')->ignore($request->edit_id_cliente, 'id_cliente')],
            'edit_cnpj_cpf' => 'nullable|string|max:32',
            'edit_seguimento' => 'nullable|string|max:120',
            'edit_endereco' => 'nullable|string|max:10000',
        ]);
        $client = Client::findOrFail($data['edit_id_cliente']);
        $client->cliente = $data['edit_cliente'];
        $client->cnpj_cpf = $data['edit_cnpj_cpf'] ?? null;
        $client->seguimento = $data['edit_seguimento'] ?? null;
        $client->endereco = $data['edit_endereco'] ?? null;
        $client->save();

        return redirect()->back()->with('Alert', 'Alteração Realizada com Sucesso!');
    }

    public function destroy($clientId)
    {
        $client = Client::findOrFail($clientId);
        if ($client->unidades()->exists() || $client->usuarios()->exists() || $client->chamados()->exists()) {
            return redirect()->back()->with('Danger', 'Não é possível remover um cliente que possui unidades, usuários ou chamados associados.');
        }

        $client->delete();

        return redirect()->back()->with('Alert', 'Registro Deletado com Sucesso!');
    }
}
