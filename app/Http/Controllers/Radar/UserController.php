<?php

namespace App\Http\Controllers\Radar;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Equipment;
use App\Models\Ticket;
use App\Models\Technician;
use App\Models\Unit;
use App\Models\User;
use App\Models\WorkOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::leftJoin('cliente', 'users.id_cliente', '=', 'cliente.id_cliente')
            ->select('users.id_cliente', 'cliente.id_cliente', 'users.id',
                'users.name', 'users.email', 'cliente.cliente', 'users.perfil')
            ->orderBy('users.perfil')
            ->get();

        $clients = Client::all();

        return view('operations.users', ['clients' => $clients, 'users' => $users]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'usuario' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'id_cliente' => 'nullable|required_if:perfil,2|integer|exists:cliente,id_cliente',
            'perfil' => 'required|integer|in:1,2',
            'password' => 'required|string|min:12|max:255|confirmed',
        ]);
        $user = new User();
        $user->name = $data['usuario'];
        $user->email = $data['email'];
        $user->id_cliente = $data['perfil'] == 2 ? $data['id_cliente'] : null;
        $user->perfil = $data['perfil'];
        $user->password = Hash::make($data['password']);
        $user->save();

        return redirect()->back()->with('Alert', 'Cadastro Realizado com Sucesso!');
    }

    public function show($id)
    {
        $data = User::findOrFail($id);

        return response()->json($data);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|integer|exists:users,id',
            'edit_usuario' => 'required|string|max:255',
            'edit_email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($request->id)],
            'edit_id_cliente' => 'nullable|required_if:edit_perfil,2|integer|exists:cliente,id_cliente',
            'edit_perfil' => 'required|integer|in:1,2',
        ]);
        $user = User::findOrFail($data['id']);
        abort_if((int) $user->perfil === User::ROLE_TECHNICIAN || Technician::where('id_usuario', $user->id)->exists(), 409, 'Contas de técnico devem ser gerenciadas na tela de técnicos.');
        if ($user->perfil == 1 && $data['edit_perfil'] != 1) {
            abort_if(User::where('perfil', 1)->count() <= 1, 409, 'Não é possível remover o último administrador.');
        }
        $user->name = $data['edit_usuario'];
        $user->id_cliente = $data['edit_perfil'] == 2 ? $data['edit_id_cliente'] : null;
        $user->perfil = $data['edit_perfil'];
        $user->email = $data['edit_email'];
        $user->save();

        return redirect()->back()->with('Alert', 'Alteração Realizada com Sucesso!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        abort_if((int) $user->id === (int) auth()->id(), 409, 'Não é possível excluir a própria conta autenticada.');
        abort_if(Technician::where('id_usuario', $user->id)->exists(), 409, 'Remova a associação de técnico antes de excluir o usuário.');
        if ($user->perfil == 1) {
            abort_if(User::where('perfil', 1)->count() <= 1, 409, 'Não é possível remover o último administrador.');
        }
        $user->delete();

        return redirect()->back()->with('Alert', 'Registro Deletado com Sucesso!');
    }

    public function sendPasswordReset($id, Request $request)
    {
        $user = User::findOrFail($id);
        $token = app('auth.password.broker')->createToken($user);
        $user->sendPasswordResetNotification($token);

        return redirect()->back()->with('Alert', 'Solicitação para redefinição de senha enviada ao usuário.');
    }

    public function dashboard()
    {
        $ticketStats = Ticket::query()
            ->selectRaw('COUNT(*) AS total, SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) AS open, SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) AS in_progress, SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) AS completed')
            ->first();

        $recentTickets = Ticket::with(['cliente', 'unidade', 'tecnico'])
            ->orderByDesc('id_chamado')
            ->limit(8)
            ->get();

        return view('dashboard', [
            'metrics' => [
                'clients' => Client::count(),
                'units' => Unit::count(),
                'equipment' => Equipment::count(),
                'users' => User::count(),
                'technicians' => Technician::count(),
                'open_tickets' => (int) ($ticketStats->open ?? 0),
                'in_progress_tickets' => (int) ($ticketStats->in_progress ?? 0),
                'completed_tickets' => (int) ($ticketStats->completed ?? 0),
                'work_orders' => WorkOrder::count(),
            ],
            'recentTickets' => $recentTickets,
        ]);
    }
}
