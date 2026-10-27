<?php

use App\Models\Client;
use App\Models\Equipment;
use App\Models\Technician;
use App\Models\Ticket;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    private const CLIENTS = [
        ['name' => 'Demo Northwind IT', 'segment' => 'Technology'],
        ['name' => 'Demo Aurora Health', 'segment' => 'Healthcare'],
        ['name' => 'Demo Green Logistics', 'segment' => 'Logistics'],
        ['name' => 'Demo Atlas Education', 'segment' => 'Education'],
        ['name' => 'Demo South Retail', 'segment' => 'Retail'],
    ];

    private const EQUIPMENT_TYPES = [
        ['name' => 'Laptop', 'description' => 'Notebook corporativo para estação de trabalho.'],
        ['name' => 'Desktop', 'description' => 'Computador desktop para uso administrativo.'],
        ['name' => 'Monitor', 'description' => 'Monitor LED de uso corporativo.'],
        ['name' => 'Printer', 'description' => 'Impressora de rede para uso compartilhado.'],
        ['name' => 'Router', 'description' => 'Roteador de rede para conectividade local.'],
        ['name' => 'Network Switch', 'description' => 'Switch gerenciável para distribuição de rede.'],
        ['name' => 'Wireless Access Point', 'description' => 'Ponto de acesso sem fio corporativo.'],
        ['name' => 'Server', 'description' => 'Servidor para serviços internos da unidade.'],
        ['name' => 'UPS', 'description' => 'Nobreak para proteção e continuidade elétrica.'],
        ['name' => 'Scanner', 'description' => 'Scanner de documentos para uso corporativo.'],
    ];

    private const INCIDENTS = [
        'Equipamento não inicia após a última atualização.',
        'Desempenho abaixo do esperado durante o expediente.',
        'Falha intermitente de conectividade com a rede.',
        'Solicitação de diagnóstico preventivo do equipamento.',
        'Periférico não está sendo reconhecido pelo sistema.',
    ];

    public function run()
    {
        if (app()->environment('production')) {
            throw new RuntimeException('O seeder de demonstração não pode ser executado em produção.');
        }
        if (! app()->environment(['local', 'testing']) && filter_var(env('RADAR_ALLOW_DEMO_SEED', false), FILTER_VALIDATE_BOOLEAN) !== true) {
            throw new RuntimeException('Em ambientes compartilhados, configure RADAR_ALLOW_DEMO_SEED=true para autorizar o seeder de demonstração.');
        }

        $demoPassword = env('RADAR_DEMO_PASSWORD');
        if (! is_string($demoPassword) || strlen($demoPassword) < 12) {
            throw new RuntimeException('Configure RADAR_DEMO_PASSWORD com pelo menos 12 caracteres antes de executar o seeder de demonstração.');
        }

        DB::transaction(function () use ($demoPassword) {
            $this->ensureSystemAdmin($demoPassword);

            foreach (self::CLIENTS as $clientIndex => $clientData) {
                $client = Client::firstOrCreate(
                    ['cliente' => $clientData['name']],
                    [
                        'cnpj_cpf' => null,
                        'seguimento' => $clientData['segment'],
                        'endereco' => 'Endereço de demonstração '.($clientIndex + 1),
                    ]
                );

                $clientAdmin = $this->ensureUser(
                    sprintf('admin.client%02d@demo.radar.test', $clientIndex + 1),
                    sprintf('Demo Client %02d Administrator', $clientIndex + 1),
                    $client->id_cliente,
                    User::ROLE_CLIENT_USER,
                    $demoPassword
                );

                $technicians = [];
                for ($technicianIndex = 1; $technicianIndex <= 5; $technicianIndex++) {
                    $technicianName = sprintf('Demo Client %02d Technician %02d', $clientIndex + 1, $technicianIndex);
                    $technicianUser = $this->ensureUser(
                        sprintf('technician.client%02d.%02d@demo.radar.test', $clientIndex + 1, $technicianIndex),
                        $technicianName,
                        $client->id_cliente,
                        User::ROLE_TECHNICIAN,
                        $demoPassword
                    );

                    $technicians[] = $this->ensureTechnician(
                        $technicianUser,
                        $technicianName,
                        'Técnico de demonstração '.$technicianIndex.' do '.$client->cliente
                    );
                }

                $units = [];
                for ($unitIndex = 1; $unitIndex <= 2; $unitIndex++) {
                    $unitName = sprintf('Demo Client %02d Unit %02d', $clientIndex + 1, $unitIndex);
                    $unit = Unit::firstOrCreate(
                        ['unidade' => $unitName],
                        [
                            'id_cliente' => $client->id_cliente,
                            'endereco' => sprintf('Demo Avenue %d, Unit %d', $clientIndex + 10, $unitIndex),
                            'descricao' => 'Unidade utilizada para demonstrar o isolamento por cliente.',
                        ]
                    );
                    $this->assertOwnership($unit->id_cliente, $client->id_cliente, 'Unidade');
                    $units[] = $unit;
                }

                foreach (self::EQUIPMENT_TYPES as $equipmentIndex => $equipmentType) {
                    $assetNumber = $equipmentIndex + 1;
                    $assetTag = sprintf('DEMO-C%02d-ASSET-%03d', $clientIndex + 1, $assetNumber);
                    $unit = $units[intdiv($equipmentIndex, 5)];
                    $equipment = Equipment::firstOrCreate(
                        ['equipamento' => $assetTag],
                        [
                            'id_unidade' => $unit->id_unidade,
                            'descricao' => $equipmentType['name'].'. '.$equipmentType['description'],
                        ]
                    );
                    $this->assertOwnership($equipment->id_unidade, $unit->id_unidade, 'Equipamento');

                    $ticket = Ticket::where('id_equipamento', $equipment->id_equipamento)
                        ->where('id_cliente', $client->id_cliente)
                        ->first();
                    if ($ticket) {
                        $this->assertOwnership($ticket->id_equipamento, $equipment->id_equipamento, 'Chamado');
                        $this->assertOwnership($ticket->id_cliente, $client->id_cliente, 'Chamado');
                        continue;
                    }

                    $status = ($equipmentIndex % 3);
                    $assignedTechnician = $status === 0
                        ? null
                        : $technicians[intdiv($equipmentIndex, 2) % count($technicians)];
                    $startDate = now()->subDays(2 + $equipmentIndex);

                    Ticket::create([
                        'numero' => 'C'.$client->id_cliente.'-'.str_pad((string) $assetNumber, 6, '0', STR_PAD_LEFT),
                        'dt_ini' => $startDate->toDateString(),
                        'dt_fim' => $status === 2 ? $startDate->copy()->addDay()->toDateString() : null,
                        'id_usuario' => $clientAdmin->id,
                        'id_cliente' => $client->id_cliente,
                        'id_unidade' => $unit->id_unidade,
                        'id_equipamento' => $equipment->id_equipamento,
                        'id_tecnico' => $assignedTechnician ? $assignedTechnician->id_tecnico : null,
                        'ocorrencia' => self::INCIDENTS[$equipmentIndex % count(self::INCIDENTS)].' Ativo: '.$assetTag,
                        'obs_cliente' => 'Chamado criado por uma conta de cliente de demonstração.',
                        'obs_tecnico' => $status > 0 ? 'Atendimento de demonstração em andamento.' : null,
                        'solucao' => $status === 2 ? 'Equipamento diagnosticado e serviço concluído para demonstração.' : null,
                        'status' => $status,
                        'assinatura' => $status === 2 ? 'Assinatura de demonstração' : null,
                        'satisfacao' => $status === 2 ? (($equipmentIndex % 3) + 1) : 0,
                    ]);
                }
            }
        });
    }

    private function ensureSystemAdmin($demoPassword)
    {
        $systemAdmin = User::where('perfil', User::ROLE_SYSTEM_ADMIN)
            ->whereNull('id_cliente')
            ->first();
        if ($systemAdmin) {
            return $systemAdmin;
        }

        $email = env('RADAR_ADMIN_EMAIL') ?: 'system.admin@demo.radar.test';
        $existingUser = User::where('email', $email)->first();
        if ($existingUser) {
            $this->assertOwnership((int) $existingUser->perfil, User::ROLE_SYSTEM_ADMIN, 'Perfil do administrador');
            $this->assertOwnership($existingUser->id_cliente, null, 'Cliente do administrador');

            return $existingUser;
        }

        $adminPassword = env('RADAR_ADMIN_PASSWORD') ?: $demoPassword;
        if (! is_string($adminPassword) || strlen($adminPassword) < 12) {
            throw new RuntimeException('RADAR_ADMIN_PASSWORD deve ter pelo menos 12 caracteres.');
        }

        $systemAdmin = new User();
        $systemAdmin->forceFill([
            'name' => env('RADAR_ADMIN_NAME') ?: 'Demo System Administrator',
            'email' => $email,
            'password' => Hash::make($adminPassword),
            'id_cliente' => null,
            'perfil' => User::ROLE_SYSTEM_ADMIN,
        ]);
        $systemAdmin->save();

        return $systemAdmin;
    }

    private function ensureUser($email, $name, $clientId, $role, $password)
    {
        $user = User::where('email', $email)->first();
        if ($user) {
            $this->assertOwnership($user->id_cliente, $clientId, 'Usuário');
            $this->assertOwnership((int) $user->perfil, $role, 'Perfil do usuário');

            return $user;
        }

        $user = new User();
        $user->forceFill([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'id_cliente' => $clientId,
            'perfil' => $role,
        ]);
        $user->save();

        return $user;
    }

    private function ensureTechnician(User $user, $name, $description)
    {
        $technician = Technician::where('id_usuario', $user->id)->first();
        if ($technician) {
            return $technician;
        }

        return Technician::create([
            'id_usuario' => $user->id,
            'nome' => $name,
            'descricao' => $description,
        ]);
    }

    private function assertOwnership($actual, $expected, $recordType)
    {
        if ((string) $actual !== (string) $expected) {
            throw new RuntimeException($recordType.' de demonstração já existe associado a outro registro.');
        }
    }
}
