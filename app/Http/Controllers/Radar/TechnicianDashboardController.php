<?php

namespace App\Http\Controllers\Radar;

use App\Http\Controllers\Controller;
use App\Models\Technician;
use App\Models\Ticket;

class TechnicianDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $technician = Technician::where('id_usuario', $user->id)->firstOrFail();

        $clientTickets = Ticket::where('id_cliente', $user->id_cliente);
        $availableCount = (clone $clientTickets)
            ->where('status', 0)
            ->whereNull('id_tecnico')
            ->count();

        $myTickets = (clone $clientTickets)->where('id_tecnico', $technician->id_tecnico);
        $inProgressCount = (clone $myTickets)->where('status', 1)->count();
        $completedCount = (clone $myTickets)->where('status', 2)->count();

        $activeTickets = (clone $myTickets)
            ->with(['unidade', 'equipamento'])
            ->where('status', 1)
            ->orderBy('dt_ini')
            ->orderBy('id_chamado')
            ->limit(6)
            ->get();

        $availableTickets = (clone $clientTickets)
            ->with(['unidade', 'equipamento'])
            ->where('status', 0)
            ->whereNull('id_tecnico')
            ->orderBy('dt_ini')
            ->orderBy('id_chamado')
            ->limit(5)
            ->get();

        $recentCompletedTickets = (clone $myTickets)
            ->with(['unidade', 'equipamento'])
            ->where('status', 2)
            ->orderByDesc('dt_fim')
            ->orderByDesc('id_chamado')
            ->limit(5)
            ->get();

        return view('operations.technician-dashboard', [
            'technician' => $technician,
            'availableCount' => $availableCount,
            'inProgressCount' => $inProgressCount,
            'completedCount' => $completedCount,
            'activeTickets' => $activeTickets,
            'availableTickets' => $availableTickets,
            'recentCompletedTickets' => $recentCompletedTickets,
        ]);
    }
}
