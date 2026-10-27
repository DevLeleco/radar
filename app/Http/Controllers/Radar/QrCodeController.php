<?php

namespace App\Http\Controllers\Radar;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Equipment;
use App\Models\Unit;
use Illuminate\Http\Request;

class QrCodeController extends Controller
{
    public function index()
    {
        $clients = auth()->user()->perfil == 2
            ? Client::where('id_cliente', auth()->user()->id_cliente)->get()
            : Client::all();

        return view('operations.qr-code-lookup', ['clients' => $clients]);
    }

    public function showUnit(Request $request)
    {
        $data = $request->validate(['id_unidade' => 'required|integer|exists:unidade,id_unidade']);
        $unitId = $data['id_unidade'];

        $unitQuery = Unit::where('id_unidade', $unitId);
        if (auth()->user()->perfil == 2) {
            $unitQuery->where('id_cliente', auth()->user()->id_cliente);
        }
        abort_unless($unitQuery->exists(), 404);

        $equipments = Equipment::join('unidade', 'equipamento.id_unidade', '=', 'unidade.id_unidade')
            ->select('equipamento.id_equipamento', 'equipamento.equipamento')
            ->where('equipamento.id_unidade', $unitId)
            ->get();

        return view('operations.qr-codes', ['equipments' => $equipments]);
    }
}
