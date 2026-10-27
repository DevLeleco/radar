<?php

namespace App\Http\Controllers\Radar;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Equipment;
use App\Models\Ticket;
use App\Models\Unit;

class ClientDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $client = Client::where('id_cliente', $user->id_cliente)->firstOrFail();

        $ticketStats = Ticket::where('id_cliente', $client->id_cliente)
            ->selectRaw('COUNT(*) AS total, SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) AS open, SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) AS in_progress, SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) AS completed')
            ->first();

        $unitCount = Unit::where('id_cliente', $client->id_cliente)->count();
        $equipmentCount = Equipment::join('unidade', 'equipamento.id_unidade', '=', 'unidade.id_unidade')
            ->where('unidade.id_cliente', $client->id_cliente)
            ->count('equipamento.id_equipamento');

        $recentTickets = Ticket::with(['unidade', 'equipamento', 'tecnico'])
            ->where('id_cliente', $client->id_cliente)
            ->orderByDesc('dt_ini')
            ->orderByDesc('id_chamado')
            ->limit(8)
            ->get();

        return view('operations.client-dashboard', [
            'client' => $client,
            'ticketStats' => [
                'total' => (int) ($ticketStats->total ?? 0),
                'open' => (int) ($ticketStats->open ?? 0),
                'in_progress' => (int) ($ticketStats->in_progress ?? 0),
                'completed' => (int) ($ticketStats->completed ?? 0),
            ],
            'unitCount' => $unitCount,
            'equipmentCount' => $equipmentCount,
            'recentTickets' => $recentTickets,
        ]);
    }
}
