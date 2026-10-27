<?php

namespace App\Http\Controllers\Radar;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Equipment;
use App\Models\Technician;
use App\Models\Ticket;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $clients = $user->perfil == 2
            ? Client::where('id_cliente', $user->id_cliente)->get(['id_cliente', 'cliente'])
            : Client::all(['id_cliente', 'cliente']);

        $query = Ticket::join('cliente', 'chamado.id_cliente', '=', 'cliente.id_cliente')
            ->join('unidade', function ($join) {
                $join->on('chamado.id_unidade', '=', 'unidade.id_unidade')
                    ->on('chamado.id_cliente', '=', 'unidade.id_cliente');
            })
            ->select('chamado.id_chamado', 'cliente.cliente', 'unidade.unidade', 'chamado.numero', 'chamado.dt_ini', 'chamado.status')
            ->where('chamado.status', '<', 2)
            ->orderBy('chamado.status');
        if ($user->perfil == 2) {
            $query->where('chamado.id_cliente', $user->id_cliente);
        }
        $tickets = $query->get();

        $qrSelection = null;
        if ($request->filled('qr_equipment_id')) {
            $qrSelection = Equipment::join('unidade', 'equipamento.id_unidade', '=', 'unidade.id_unidade')
                ->where('equipamento.id_equipamento', $request->query('qr_equipment_id'))
                ->select('equipamento.id_equipamento', 'unidade.id_unidade', 'unidade.id_cliente')
                ->firstOrFail();
            abort_unless($user->perfil != 2 || (int) $qrSelection->id_cliente === (int) $user->id_cliente, 404);
        }

        return view('operations.tickets', ['tickets' => $tickets, 'clients' => $clients, 'qrSelection' => $qrSelection]);
    }

    public function openFromQr($equipmentId)
    {
        $equipment = Equipment::join('unidade', 'equipamento.id_unidade', '=', 'unidade.id_unidade')
            ->where('equipamento.id_equipamento', $equipmentId)
            ->select('equipamento.id_equipamento', 'unidade.id_cliente')
            ->firstOrFail();
        $user = auth()->user();
        abort_unless($user->perfil != 2 || (int) $equipment->id_cliente === (int) $user->id_cliente, 404);

        return redirect()->route('tickets.index', ['qr_equipment_id' => $equipment->id_equipamento]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_cliente' => 'required|integer|exists:cliente,id_cliente',
            'id_unidade' => 'required|integer|exists:unidade,id_unidade',
            'id_equipamento' => 'required|integer|exists:equipamento,id_equipamento',
            'ocorrencia' => 'required|string|max:5000',
        ]);
        $user = auth()->user();
        abort_unless($user->perfil != 2 || (int) $data['id_cliente'] === (int) $user->id_cliente, 403);

        DB::transaction(function () use ($data, $user) {
            $client = Client::where('id_cliente', $data['id_cliente'])->lockForUpdate()->firstOrFail();
            $unit = Unit::where('id_unidade', $data['id_unidade'])->lockForUpdate()->firstOrFail();
            $equipment = Equipment::where('id_equipamento', $data['id_equipamento'])->lockForUpdate()->firstOrFail();
            abort_unless((int) $unit->id_cliente === (int) $client->id_cliente, 422);
            abort_unless((int) $equipment->id_unidade === (int) $unit->id_unidade, 422);
            $lastTicket = Ticket::where('id_cliente', $client->id_cliente)
                ->orderByDesc('id_chamado')
                ->lockForUpdate()
                ->first();

            $ticketPrefix = 'C'.$client->id_cliente.'-';
            $previousSequence = 0;
            if ($lastTicket && preg_match('/^C'.preg_quote((string) $client->id_cliente, '/').'-([0-9]+)$/', (string) $lastTicket->numero, $matches) === 1) {
                $previousSequence = (int) $matches[1];
            }
            if ($previousSequence === 0) {
                $previousSequence = Ticket::where('id_cliente', $client->id_cliente)->count();
            }
            $newTicketNumber = $ticketPrefix.str_pad((string) ($previousSequence + 1), 6, '0', STR_PAD_LEFT);

            $ticket = new Ticket();
            $ticket->numero = $newTicketNumber;
            $ticket->dt_ini = now()->toDateString();
            $ticket->id_usuario = $user->id;
            $ticket->id_cliente = $client->id_cliente;
            $ticket->id_unidade = $data['id_unidade'];
            $ticket->id_equipamento = $data['id_equipamento'];
            $ticket->ocorrencia = $data['ocorrencia'];
            $ticket->status = 0;
            $ticket->satisfacao = 0;
            $ticket->save();
        }, 3);

        return redirect()->back()->with('Alert', 'Cadastro Realizado com Sucesso!');
    }

    public function show($ticketId)//checar o nome do usuari0, conflito nos relacionamentos.
    {

        $query = Ticket::join('cliente', 'chamado.id_cliente', '=', 'cliente.id_cliente')
            ->join('unidade', function ($join) {
                $join->on('chamado.id_unidade', '=', 'unidade.id_unidade')
                    ->on('chamado.id_cliente', '=', 'unidade.id_cliente');
            })
            ->join('equipamento', function ($join) {
                $join->on('chamado.id_equipamento', '=', 'equipamento.id_equipamento')
                    ->on('chamado.id_unidade', '=', 'equipamento.id_unidade');
            })
            ->leftJoin('users', 'chamado.id_usuario', '=', 'users.id')
                            //->join('tecnico', 'chamado.id_tecnico', '=','tecnico.id_tecnico')
            ->select('chamado.id_chamado', 'cliente.cliente', 'unidade.unidade', 'equipamento.equipamento', 'chamado.ocorrencia', 'chamado.obs_cliente', 'chamado.obs_tecnico', 'chamado.numero', 'chamado.dt_ini', 'users.name')
            ->where('chamado.id_chamado', $ticketId);
        if (auth()->user()->perfil == 2) {
            $query->where('chamado.id_cliente', auth()->user()->id_cliente);
        }
        $data = $query->firstOrFail();

        return response()->json($data);
    }

    public function updateNotes(Request $request)
    {
        $data = $request->validate([
            'edit_id_chamado' => 'required|integer|exists:chamado,id_chamado',
            'edit_observacao' => 'nullable|string|max:5000',
        ]);
        $ticket = Ticket::findOrFail($data['edit_id_chamado']);
        $ticket->obs_tecnico = $data['edit_observacao'] ?? null;
        $ticket->save();

        return redirect()->back()->with('Alert', 'Alteração Realizada com Sucesso!');
    }

    public function history()
    {
        $query = Ticket::join('cliente', 'chamado.id_cliente', '=', 'cliente.id_cliente')
            ->join('unidade', function ($join) {
                $join->on('chamado.id_unidade', '=', 'unidade.id_unidade')
                    ->on('chamado.id_cliente', '=', 'unidade.id_cliente');
            })
            ->join('tecnico', 'chamado.id_tecnico', '=', 'tecnico.id_tecnico')
            ->select('chamado.id_chamado', 'cliente.cliente', 'unidade.unidade', 'chamado.numero', 'chamado.dt_ini', 'chamado.status')
            ->where('chamado.status', '=', 2)
            ->orderBy('chamado.dt_ini');
        if ((int) auth()->user()->perfil === User::ROLE_CLIENT_USER) {
            $query->where('chamado.id_cliente', auth()->user()->id_cliente);
        }
        $tickets = $query->get();

        return view('operations.ticket-history', ['tickets' => $tickets]);
    }

    public function queue()
    {
        $user = auth()->user();
        $query = Ticket::join('cliente', 'chamado.id_cliente', '=', 'cliente.id_cliente')
            ->join('unidade', function ($join) {
                $join->on('chamado.id_unidade', '=', 'unidade.id_unidade')
                    ->on('chamado.id_cliente', '=', 'unidade.id_cliente');
            })
            ->select('chamado.id_chamado', 'cliente.cliente', 'unidade.unidade', 'chamado.numero', 'chamado.dt_ini', 'chamado.status')
            ->where('chamado.status', '<', 2)
            ->orderBy('chamado.dt_ini');
        if ((int) $user->perfil === User::ROLE_TECHNICIAN) {
            $technician = Technician::where('id_usuario', $user->id)->firstOrFail();
            $query->where('chamado.id_cliente', $user->id_cliente)
                ->where(function ($ticketQuery) use ($technician) {
                    $ticketQuery->whereNull('chamado.id_tecnico')
                        ->orWhere('chamado.id_tecnico', $technician->id_tecnico);
                });
        }
        $tickets = $query->get();

        return view('operations.technician-queue', ['tickets' => $tickets]);
    }

    public function showForTechnician($ticketId)
    {
        $user = auth()->user();
        $technician = Technician::where('id_usuario', $user->id)->firstOrFail();
        $tickets = Ticket::join('cliente', 'chamado.id_cliente', '=', 'cliente.id_cliente')
            ->join('unidade', function ($join) {
                $join->on('chamado.id_unidade', '=', 'unidade.id_unidade')
                    ->on('chamado.id_cliente', '=', 'unidade.id_cliente');
            })
            ->join('equipamento', function ($join) {
                $join->on('chamado.id_equipamento', '=', 'equipamento.id_equipamento')
                    ->on('chamado.id_unidade', '=', 'equipamento.id_unidade');
            })
            ->leftJoin('users', 'chamado.id_usuario', '=', 'users.id')
                                //->join('tecnico', 'chamado.id_tecnico', '=','tecnico.id_tecnico')
            ->select('chamado.id_chamado', 'cliente.cliente', 'unidade.unidade', 'equipamento.equipamento', 'chamado.ocorrencia', 'chamado.obs_tecnico', 'chamado.numero', 'chamado.dt_ini', 'chamado.status', 'users.name')
            ->where('chamado.id_chamado', $ticketId)
            ->where('chamado.id_cliente', $user->id_cliente)
            ->where('chamado.id_tecnico', $technician->id_tecnico)
            ->where('chamado.status', 1)
            ->get();
        abort_if($tickets->isEmpty(), 404);

        return view('operations.ticket-details', ['tickets' => $tickets]);
    }

    public function complete(Request $request)
    {
        $data = $request->validate([
            'id_chamado' => 'required|integer|exists:chamado,id_chamado',
            'solucao' => 'required|string|max:5000',
            'status' => 'required|integer|in:2',
            'observacao' => 'nullable|string|max:5000',
            'assinatura' => 'required|string|max:5000',
        ]);
        $user = auth()->user();
        $technician = Technician::where('id_usuario', $user->id)->firstOrFail();
        $ticket = Ticket::where('id_chamado', $data['id_chamado'])
            ->where('id_cliente', $user->id_cliente)
            ->where('status', 1)
            ->where('id_tecnico', $technician->id_tecnico)
            ->firstOrFail();
        $ticket->solucao = $data['solucao'];
        $ticket->status = 2;
        $ticket->obs_tecnico = $data['observacao'] ?? null;
        $ticket->assinatura = $data['assinatura'];
        $ticket->dt_fim = now()->toDateString();
        $ticket->save();

        return redirect()->route('admin.tickets.queue');
    }

    public function start(Request $request)
    {
        $data = $request->validate(['id_chamado' => 'required|integer|exists:chamado,id_chamado']);
        $user = auth()->user();
        $technician = Technician::where('id_usuario', $user->id)->firstOrFail();
        $updated = Ticket::where('id_chamado', $data['id_chamado'])
            ->where('id_cliente', $user->id_cliente)
            ->where('status', 0)
            ->whereNull('id_tecnico')
            ->update(['id_tecnico' => $technician->id_tecnico, 'status' => 1, 'updated_at' => now()]);
        abort_unless($updated === 1, 409, 'O chamado já foi atribuído ou não está aberto.');

        return redirect()->route('admin.tickets.queue')->with('Alert', 'Atendimento iniciado.');
    }

    public function destroy($ticketId)
    {
        $query = Ticket::where('id_chamado', $ticketId);
        if (auth()->user()->perfil == 2) {
            $query->where('id_cliente', auth()->user()->id_cliente);
        }
        $ticket = $query->firstOrFail();

        if (! empty($ticket->id_tecnico)) {
            return redirect()->back()->with('Alert', 'Chamado em atendimento, por favor entre em contato com o Suporte, para o Cancelamento!');
        }
        $ticket->delete();

        return redirect()->back()->with('Alert', 'Chamado Cancelado com Sucesso!');
    }
}
