<?php

namespace App\Http\Controllers\Radar;

use App\Http\Controllers\Controller;
use App\Models\Technician;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class TechnicianController extends Controller
{
    public function index()
    {
        $technicians = Technician::leftJoin('users', 'tecnico.id_usuario', '=', 'users.id')
            ->leftJoin('cliente', 'users.id_cliente', '=', 'cliente.id_cliente')
            ->select('tecnico.*', 'users.name as nome_usuario', 'users.email', 'cliente.cliente as cliente_nome')
            ->orderBy('tecnico.nome')
            ->get();
        $clients = \App\Models\Client::orderBy('cliente')->get(['id_cliente', 'cliente']);

        return view('operations.technicians', ['technicians' => $technicians, 'clients' => $clients]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'id_cliente' => 'required|integer|exists:cliente,id_cliente',
            'password' => 'required|string|min:12|max:255|confirmed',
            'descricao' => 'nullable|string|max:5000',
        ]);
        DB::transaction(function () use ($data) {
            $user = new User();
            $user->forceFill([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'id_cliente' => $data['id_cliente'],
                'perfil' => User::ROLE_TECHNICIAN,
            ]);
            $user->save();

            Technician::create([
                'id_usuario' => $user->id,
                'nome' => $user->name,
                'descricao' => $data['descricao'] ?? null,
            ]);
        });

        return redirect()->back()->with('Alert', 'Cadastro Realizado com Sucesso!');
    }

    public function destroy($technicianId)
    {
        $technician = Technician::findOrFail($technicianId);
        abort_if($technician->chamados()->exists(), 409, 'Não é possível remover um técnico associado a chamados.');
        DB::transaction(function () use ($technician) {
            $user = $technician->usuario;
            $technician->delete();
            if ($user && (int) $user->perfil === User::ROLE_TECHNICIAN) {
                $user->delete();
            }
        });

        return redirect()->back()->with('Alert', 'Registro Deletado com Sucesso!');
    }
}
