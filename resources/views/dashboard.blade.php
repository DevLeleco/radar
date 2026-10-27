@extends('operations.master.admin')

@section('title', 'Painel administrativo')

@section('content_header')
    <div class="client-dashboard-heading">
        <div>
            <span class="dashboard-eyebrow">Visão global da operação</span>
            <h1>Painel administrativo</h1>
            <p>Olá, {{ auth()->user()->name }}. Acompanhe e gerencie os dados do Radar.</p>
        </div>
        <a class="btn btn-default" href="{{ route('admin.dashboard') }}" aria-label="Atualizar painel administrativo">
            <i class="fas fa-sync-alt mr-1" aria-hidden="true"></i> Atualizar
        </a>
    </div>
@stop

@section('content')
    <section class="system-dashboard" aria-label="Resumo geral do Radar">
        <div class="dashboard-section-heading">
            <div>
                <span class="dashboard-eyebrow">Indicadores</span>
                <h2>Visão geral</h2>
            </div>
        </div>

        <div class="system-dashboard-stats">
            <a class="system-stat stat-clients" href="{{ route('admin.clients.index') }}">
                <span class="system-stat-icon"><i class="fas fa-building" aria-hidden="true"></i></span>
                <span class="system-stat-label">Clientes</span>
                <strong>{{ number_format($metrics['clients'], 0, ',', '.') }}</strong>
                <small>Contas atendidas</small>
            </a>
            <a class="system-stat stat-units" href="{{ route('admin.units.index') }}">
                <span class="system-stat-icon"><i class="fas fa-map-marker-alt" aria-hidden="true"></i></span>
                <span class="system-stat-label">Unidades</span>
                <strong>{{ number_format($metrics['units'], 0, ',', '.') }}</strong>
                <small>Locais cadastrados</small>
            </a>
            <a class="system-stat stat-equipment" href="{{ route('admin.equipment.index') }}">
                <span class="system-stat-icon"><i class="fas fa-desktop" aria-hidden="true"></i></span>
                <span class="system-stat-label">Equipamentos</span>
                <strong>{{ number_format($metrics['equipment'], 0, ',', '.') }}</strong>
                <small>Ativos registrados</small>
            </a>
            <a class="system-stat stat-users" href="{{ route('admin.users.index') }}">
                <span class="system-stat-icon"><i class="fas fa-users" aria-hidden="true"></i></span>
                <span class="system-stat-label">Usuários</span>
                <strong>{{ number_format($metrics['users'], 0, ',', '.') }}</strong>
                <small>Contas no sistema</small>
            </a>
            <a class="system-stat stat-technicians" href="{{ route('admin.technicians.index') }}">
                <span class="system-stat-icon"><i class="fas fa-user-cog" aria-hidden="true"></i></span>
                <span class="system-stat-label">Técnicos</span>
                <strong>{{ number_format($metrics['technicians'], 0, ',', '.') }}</strong>
                <small>Equipes cadastradas</small>
            </a>
            <a class="system-stat stat-open" href="{{ route('tickets.index') }}">
                <span class="system-stat-icon"><i class="fas fa-inbox" aria-hidden="true"></i></span>
                <span class="system-stat-label">Chamados abertos</span>
                <strong>{{ number_format($metrics['open_tickets'], 0, ',', '.') }}</strong>
                <small>Aguardando atendimento</small>
            </a>
            <a class="system-stat stat-progress" href="{{ route('admin.tickets.queue') }}">
                <span class="system-stat-icon"><i class="fas fa-tools" aria-hidden="true"></i></span>
                <span class="system-stat-label">Em atendimento</span>
                <strong>{{ number_format($metrics['in_progress_tickets'], 0, ',', '.') }}</strong>
                <small>Na fila técnica</small>
            </a>
            <a class="system-stat stat-orders" href="{{ route('admin.work-orders.index') }}">
                <span class="system-stat-icon"><i class="fas fa-file-alt" aria-hidden="true"></i></span>
                <span class="system-stat-label">Ordens de serviço</span>
                <strong>{{ number_format($metrics['work_orders'], 0, ',', '.') }}</strong>
                <small>Registros cadastrados</small>
            </a>
            <a class="system-stat stat-complete" href="{{ route('admin.tickets.history') }}">
                <span class="system-stat-icon"><i class="fas fa-check-circle" aria-hidden="true"></i></span>
                <span class="system-stat-label">Chamados concluídos</span>
                <strong>{{ number_format($metrics['completed_tickets'], 0, ',', '.') }}</strong>
                <small>No histórico</small>
            </a>
        </div>

        <div class="dashboard-section-heading system-shortcuts-heading">
            <div>
                <span class="dashboard-eyebrow">Acesso rápido</span>
                <h2>Gerenciamento</h2>
            </div>
        </div>

        <div class="system-dashboard-links">
            <a class="system-dashboard-link" href="{{ route('admin.clients.index') }}"><i class="fas fa-building" aria-hidden="true"></i><span><strong>Clientes</strong><small>Gerenciar contas e contratos</small></span><i class="fas fa-chevron-right link-arrow" aria-hidden="true"></i></a>
            <a class="system-dashboard-link" href="{{ route('admin.units.index') }}"><i class="fas fa-map-marker-alt" aria-hidden="true"></i><span><strong>Unidades</strong><small>Gerenciar locais de atendimento</small></span><i class="fas fa-chevron-right link-arrow" aria-hidden="true"></i></a>
            <a class="system-dashboard-link" href="{{ route('admin.equipment.index') }}"><i class="fas fa-desktop" aria-hidden="true"></i><span><strong>Equipamentos</strong><small>Manter inventário e QR codes</small></span><i class="fas fa-chevron-right link-arrow" aria-hidden="true"></i></a>
            <a class="system-dashboard-link" href="{{ route('admin.users.index') }}"><i class="fas fa-users" aria-hidden="true"></i><span><strong>Usuários</strong><small>Gerenciar administradores e clientes</small></span><i class="fas fa-chevron-right link-arrow" aria-hidden="true"></i></a>
            <a class="system-dashboard-link" href="{{ route('admin.technicians.index') }}"><i class="fas fa-user-cog" aria-hidden="true"></i><span><strong>Técnicos</strong><small>Gerenciar contas e equipes técnicas</small></span><i class="fas fa-chevron-right link-arrow" aria-hidden="true"></i></a>
            <a class="system-dashboard-link" href="{{ route('admin.work-orders.index') }}"><i class="fas fa-file-alt" aria-hidden="true"></i><span><strong>Ordens de serviço</strong><small>Consultar registros de serviço</small></span><i class="fas fa-chevron-right link-arrow" aria-hidden="true"></i></a>
            <a class="system-dashboard-link" href="{{ route('tickets.index') }}"><i class="fas fa-clipboard-list" aria-hidden="true"></i><span><strong>Chamados</strong><small>Acompanhar chamados em aberto</small></span><i class="fas fa-chevron-right link-arrow" aria-hidden="true"></i></a>
            <a class="system-dashboard-link" href="{{ route('admin.tickets.queue') }}"><i class="fas fa-tools" aria-hidden="true"></i><span><strong>Fila técnica</strong><small>Ver atendimentos em execução</small></span><i class="fas fa-chevron-right link-arrow" aria-hidden="true"></i></a>
            <a class="system-dashboard-link" href="{{ route('admin.tickets.history') }}"><i class="fas fa-history" aria-hidden="true"></i><span><strong>Histórico</strong><small>Consultar chamados concluídos</small></span><i class="fas fa-chevron-right link-arrow" aria-hidden="true"></i></a>
            <a class="system-dashboard-link" href="{{ route('qr-codes.index') }}"><i class="fas fa-qrcode" aria-hidden="true"></i><span><strong>QR codes</strong><small>Localizar equipamentos por unidade</small></span><i class="fas fa-chevron-right link-arrow" aria-hidden="true"></i></a>
        </div>

        <section class="card dashboard-recent-card system-recent-card" aria-labelledby="system-recent-tickets-title">
            <div class="card-header dashboard-recent-header">
                <div>
                    <h2 class="card-title" id="system-recent-tickets-title">Chamados recentes</h2>
                    <p>Últimas solicitações registradas em todos os clientes.</p>
                </div>
                <a href="{{ route('tickets.index') }}">Ver chamados abertos <i class="fas fa-arrow-right ml-1" aria-hidden="true"></i></a>
            </div>
            <div class="table-responsive">
                <table class="table dashboard-ticket-table">
                    <thead>
                        <tr>
                            <th scope="col">Chamado</th>
                            <th scope="col">Cliente</th>
                            <th scope="col">Unidade</th>
                            <th scope="col">Técnico</th>
                            <th scope="col">Abertura</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentTickets as $ticket)
                            <tr>
                                <td class="ticket-number">{{ $ticket->numero }}</td>
                                <td>{{ $ticket->cliente->cliente ?? '—' }}</td>
                                <td>{{ $ticket->unidade->unidade ?? '—' }}</td>
                                <td>{{ $ticket->tecnico->nome ?? 'Aguardando atribuição' }}</td>
                                <td>{{ $ticket->dt_ini ? \Carbon\Carbon::parse($ticket->dt_ini)->format('d/m/Y') : '—' }}</td>
                                <td>
                                    @if ((int) $ticket->status === 0)
                                        <span class="ticket-status status-open">Aberto</span>
                                    @elseif ((int) $ticket->status === 1)
                                        <span class="ticket-status status-progress">Em atendimento</span>
                                    @elseif ((int) $ticket->status === 2)
                                        <span class="ticket-status status-complete">Concluído</span>
                                    @else
                                        <span class="ticket-status status-unknown">Indefinido</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="dashboard-empty-state">
                                    <span class="empty-state-icon"><i class="fas fa-clipboard-check" aria-hidden="true"></i></span>
                                    <strong>Nenhum chamado registrado</strong>
                                    <span>Os chamados mais recentes aparecerão aqui.</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </section>
@stop
