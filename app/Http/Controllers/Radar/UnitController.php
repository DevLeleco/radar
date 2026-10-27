<?php

namespace App\Http\Controllers\Radar;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Equipment;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::join('cliente', 'unidade.id_cliente', '=', 'cliente.id_cliente')
            ->select('unidade.id_cliente', 'cliente.id_cliente', 'cliente.cliente',
                'unidade.id_unidade', 'unidade.unidade', 'unidade.endereco', 'unidade.descricao')
            ->orderBy('unidade.id_unidade')
            ->get();

        $clients = Client::all();

        return view('operations.units', ['units' => $units, 'clients' => $clients]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'unidade' => 'required|string|max:255|unique:unidade,unidade',
            'id_cliente' => 'required|integer|exists:cliente,id_cliente',
            'endereco' => 'nullable|string|max:10000',
            'descricao' => 'nullable|string|max:10000',
        ]);
        Unit::create($data);

        return redirect()->back()->with('Alert', 'Cadastro Realizado com Sucesso!');
    }

    public function equipmentByUnit(Request $request)
    {
        $data = $request->validate(['id' => 'required|integer|exists:unidade,id_unidade']);
        $query = Equipment::join('unidade', 'equipamento.id_unidade', '=', 'unidade.id_unidade')
            ->where('equipamento.id_unidade', $data['id']);
        if (auth()->user()->perfil == 2) {
            $query->where('unidade.id_cliente', auth()->user()->id_cliente);
        }
        $equipments = $query->get(['equipamento.id_equipamento', 'equipamento.equipamento']);
        $output = [];
        foreach ($equipments as $equipment) {
            $output[$equipment->id_equipamento] = $equipment->equipamento;
        }

        return $output;
    }

    public function show($unitId)
    {
        $data = Unit::join('cliente', 'unidade.id_cliente', '=', 'cliente.id_cliente')
            ->select('unidade.id_cliente', 'cliente.id_cliente', 'cliente.cliente',
                'unidade.id_unidade', 'unidade.unidade', 'unidade.endereco', 'unidade.descricao')
            ->where('id_unidade', $unitId)
            ->firstOrFail();

        return response()->json($data);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'edit_id_unidade' => 'required|integer|exists:unidade,id_unidade',
            'edit_unidade' => ['required', 'string', 'max:255', Rule::unique('unidade', 'unidade')->ignore($request->edit_id_unidade, 'id_unidade')],
            'edit_id_cliente' => 'required|integer|exists:cliente,id_cliente',
            'edit_endereco' => 'nullable|string|max:10000',
            'edit_descricao' => 'nullable|string|max:10000',
        ]);
        $unit = Unit::findOrFail($data['edit_id_unidade']);
        $unit->unidade = $data['edit_unidade'];
        $unit->id_cliente = $data['edit_id_cliente'];
        $unit->endereco = $data['edit_endereco'] ?? null;
        $unit->descricao = $data['edit_descricao'] ?? null;
        $unit->save();

        return redirect()->back()->with('Alert', 'Alteração Realizada com Sucesso!');
    }

    public function destroy($unitId)
    {
        $unit = Unit::findOrFail($unitId);
        if ($unit->equipamentos()->exists() || $unit->chamados()->exists()) {
            return redirect()->back()->with('Danger', 'Não é possível remover uma unidade associada a equipamentos ou chamados.');
        }

        $unit->delete();

        return redirect()->back()->with('Alert', 'Registro Deletado com Sucesso!');
    }
}
