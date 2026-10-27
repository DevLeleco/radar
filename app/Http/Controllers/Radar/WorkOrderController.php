<?php

namespace App\Http\Controllers\Radar;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\WorkOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class WorkOrderController extends Controller
{
    public function index()
    {
        $workOrders = WorkOrder::with('chamado')->get();
        $availableTickets = Ticket::with(['usuario', 'cliente', 'unidade', 'equipamento', 'tecnico'])
            ->where('status', 2)
            ->whereNotNull('dt_fim')
            ->whereNotNull('solucao')
            ->whereBetween('satisfacao', [1, 3])
            ->whereNotNull('id_tecnico')
            ->whereDoesntHave('ordemServico')
            ->orderByDesc('id_chamado')
            ->get();

        return view('operations.work-orders', ['workOrders' => $workOrders, 'availableTickets' => $availableTickets]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'numero' => 'required|string|max:64|unique:os,numero',
            'id_chamado' => 'required|integer|exists:chamado,id_chamado',
        ]);

        DB::transaction(function () use ($data) {
            $ticket = Ticket::with(['usuario', 'cliente', 'unidade', 'equipamento', 'tecnico'])
                ->where('id_chamado', $data['id_chamado'])
                ->where('status', 2)
                ->whereNotNull('dt_fim')
                ->whereNotNull('solucao')
                ->whereBetween('satisfacao', [1, 3])
                ->whereNotNull('id_tecnico')
                ->lockForUpdate()
                ->firstOrFail();
            abort_if($ticket->ordemServico()->exists(), 409, 'Este chamado já possui uma ordem de serviço.');

            WorkOrder::create([
                'id_chamado' => $ticket->id_chamado,
                'numero' => $data['numero'],
                'dt_ini' => $ticket->dt_ini,
                'dt_fim' => $ticket->dt_fim,
                'usuario' => $ticket->usuario->name ?? 'Usuário removido',
                'cliente' => $ticket->cliente->cliente,
                'unidade' => $ticket->unidade->unidade,
                'equipamento' => $ticket->equipamento->equipamento,
                'tecnico' => $ticket->tecnico->nome ?? 'Técnico removido',
                'ocorrencia' => $ticket->ocorrencia,
                'solucao' => $ticket->solucao,
                'satisfacao' => $ticket->satisfacao,
            ]);
        });

        return redirect()->back()->with('Alert', 'Cadastro Realizado com Sucesso!');
    }

    public function show($workOrderId)
    {
        $data = WorkOrder::findOrFail($workOrderId);

        return response()->json($data);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'edit_id_os' => 'required|integer|exists:os,id_os',
            'edit_numero' => ['required', 'string', 'max:64', Rule::unique('os', 'numero')->ignore($request->input('edit_id_os'), 'id_os')],
        ]);
        $workOrder = WorkOrder::findOrFail($data['edit_id_os']);
        $workOrder->numero = $data['edit_numero'];
        $workOrder->save();

        return redirect()->back()->with('Alert', 'Alteração Realizada com Sucesso!');
    }

    public function destroy($workOrderId)
    {
        $workOrder = WorkOrder::findOrFail($workOrderId);
        $workOrder->delete();

        return redirect()->back()->with('Alert', 'Registro Deletado com Sucesso!');
    }
}
