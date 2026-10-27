<?php

namespace App\Http\Controllers\Radar;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Equipment;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EquipmentController extends Controller
{
    public function index()
    {
        $equipments = Equipment::join('unidade', 'equipamento.id_unidade', '=', 'unidade.id_unidade')
            ->join('cliente', 'unidade.id_cliente', '=', 'cliente.id_cliente')
            ->select('equipamento.id_equipamento', 'equipamento.id_unidade', 'equipamento.equipamento', 'unidade.unidade',
                'unidade.id_unidade', 'equipamento.descricao', 'cliente.cliente')
            ->orderBy('unidade.id_cliente')
            ->get();

        $clients = Client::all();

        return view('operations.equipment', ['clients' => $clients, 'equipments' => $equipments]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_unidade' => 'required|integer|exists:unidade,id_unidade',
            'equipamento' => 'required|string|max:255|unique:equipamento,equipamento',
            'descricao' => 'nullable|string|max:10000',
        ]);
        Equipment::create($data);

        return redirect()->back()->with('Alert', 'Cadastro Realizado com Sucesso!');
    }

    public function show($equipmentId)
    {
        $data = Equipment::join('unidade', 'equipamento.id_unidade', '=', 'unidade.id_unidade')
            ->select('equipamento.id_equipamento', 'equipamento.equipamento', 'equipamento.descricao', 'unidade.id_unidade', 'unidade.unidade')
            ->where('id_equipamento', $equipmentId)
            ->firstOrFail();

        return response()->json($data);
    }

    public function unitsByClient(Request $request)
    {
        $data = $request->validate(['id' => 'required|integer|exists:cliente,id_cliente']);
        $query = Unit::where('id_cliente', $data['id']);
        if (auth()->user()->perfil == 2) {
            $query->where('id_cliente', auth()->user()->id_cliente);
        }
        $units = $query->get();
        $output = [];
        foreach ($units as $unit) {
            $output[$unit->id_unidade] = $unit->unidade;
        }

        return $output;
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'edit_id_equipamento' => 'required|integer|exists:equipamento,id_equipamento',
            'edit_equipamento' => ['required', 'string', 'max:255', Rule::unique('equipamento', 'equipamento')->ignore($request->edit_id_equipamento, 'id_equipamento')],
            'edit_id_unidade' => 'required|integer|exists:unidade,id_unidade',
            'edit_descricao' => 'nullable|string|max:10000',
        ]);
        $equipment = Equipment::findOrFail($data['edit_id_equipamento']);
        $equipment->equipamento = $data['edit_equipamento'];
        $equipment->id_unidade = $data['edit_id_unidade'];
        $equipment->descricao = $data['edit_descricao'] ?? null;
        $equipment->save();

        return redirect()->back()->with('Alert', 'Alteração Realizada com Sucesso!');
    }

    public function destroy($equipmentId)
    {
        $equipment = Equipment::findOrFail($equipmentId);
        if ($equipment->chamados()->exists()) {
            return redirect()->back()->with('Danger', 'Não é possível remover um equipamento associado a chamados.');
        }
        $equipment->delete();

        return redirect()->back()->with('Alert', 'Registro Deletado com Sucesso!');

    }
}
