@extends('operations.master.admin')

@section('title', 'Painel gerencial')

@section('content_header')
    <div class="client-dashboard-heading">
        <div>
            <span class="dashboard-eyebrow">Visão geral do cliente</span>
            <h1>Painel gerencial</h1>
            <p>Acompanhe chamados, unidades e equipamentos de {{ $client->cliente }}.</p>
        </div>
        <div class="client-dashboard-controls">
            <a class="btn btn-default" href="{{ route('client.dashboard') }}" aria-label="Atualizar indicadores e chamados recentes">
                <i class="fas fa-sync-alt mr-1" aria-hidden="true"></i> Atualizar
            </a>
            <a class="btn btn-primary" href="{{ route('tickets.index') }}#novo-chamado">
                <i class="fas fa-plus mr-1" aria-hidden="true"></i> Abrir chamado
            </a>
        </div>
    </div>
@stop

@section('content')
    <section class="client-dashboard" aria-label="Indicadores e atalhos de gerenciamento">
        <div class="dashboard-stats">
            <article class="dashboard-stat stat-open">
                <span class="stat-icon"><i class="fas fa-inbox" aria-hidden="true"></i></span>
                <span class="stat-label">Aguardando atendimento</span>
                <strong>{{ number_format($ticketStats['open'], 0, ',', '.') }}</strong>
                <span class="stat-note">Chamados abertos</span>
            </article>
            <article class="dashboard-stat stat-progress">
                <span class="stat-icon"><i class="fas fa-tools" aria-hidden="true"></i></span>
                <span class="stat-label">Em atendimento</span>
                <strong>{{ number_format($ticketStats['in_progress'], 0, ',', '.') }}</strong>
                <span class="stat-note">Com um técnico</span>
            </article>
            <article class="dashboard-stat stat-complete">
                <span class="stat-icon"><i class="fas fa-check-circle" aria-hidden="true"></i></span>
                <span class="stat-label">Concluídos</span>
                <strong>{{ number_format($ticketStats['completed'], 0, ',', '.') }}</strong>
                <span class="stat-note">No histórico</span>
            </article>
            <article class="dashboard-stat stat-units">
                <span class="stat-icon"><i class="fas fa-map-marker-alt" aria-hidden="true"></i></span>
                <span class="stat-label">Unidades</span>
                <strong>{{ number_format($unitCount, 0, ',', '.') }}</strong>
                <span class="stat-note">Cadastradas para o cliente</span>
            </article>
            <article class="dashboard-stat stat-equipment">
                <span class="stat-icon"><i class="fas fa-desktop" aria-hidden="true"></i></span>
                <span class="stat-label">Equipamentos</span>
                <strong>{{ number_format($equipmentCount, 0, ',', '.') }}</strong>
                <span class="stat-note">Identificáveis por QR code</span>
            </article>
        </div>

        <div class="dashboard-section-heading">
            <div>
                <span class="dashboard-eyebrow">Acesso rápido</span>
                <h2>Gerenciamento</h2>
            </div>
        </div>

        <div class="dashboard-actions">
            <a class="dashboard-action" href="{{ route('tickets.index') }}">
                <span class="action-icon action-icon-blue"><i class="fas fa-clipboard-list" aria-hidden="true"></i></span>
                <span><strong>Chamados</strong><small>Abrir, acompanhar ou consultar atendimentos</small></span>
                <i class="fas fa-chevron-right action-arrow" aria-hidden="true"></i>
            </a>
            <a class="dashboard-action" href="{{ route('admin.tickets.history') }}">
                <span class="action-icon action-icon-green"><i class="fas fa-history" aria-hidden="true"></i></span>
                <span><strong>Histórico de chamados</strong><small>Consultar atendimentos concluídos</small></span>
                <i class="fas fa-chevron-right action-arrow" aria-hidden="true"></i>
            </a>
            <a class="dashboard-action" href="{{ route('qr-codes.index') }}">
                <span class="action-icon action-icon-violet"><i class="fas fa-qrcode" aria-hidden="true"></i></span>
                <span><strong>QR codes</strong><small>Localizar equipamentos por unidade</small></span>
                <i class="fas fa-chevron-right action-arrow" aria-hidden="true"></i>
            </a>
        </div>

        <section class="card dashboard-recent-card" aria-labelledby="recent-tickets-title">
            <div class="card-header dashboard-recent-header">
                <div>
                    <h2 class="card-title" id="recent-tickets-title">Chamados recentes</h2>
                    <p>Atividade mais recente nas unidades do cliente.</p>
                </div>
                <a href="{{ route('tickets.index') }}">Ver chamados ativos <i class="fas fa-arrow-right ml-1" aria-hidden="true"></i></a>
            </div>
            <div class="table-responsive">
                <table class="table dashboard-ticket-table">
                    <thead>
                        <tr>
                            <th scope="col">Chamado</th>
                            <th scope="col">Unidade</th>
                            <th scope="col">Equipamento</th>
                            <th scope="col">Responsável</th>
                            <th scope="col">Abertura</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentTickets as $ticket)
                            <tr>
                                <td class="ticket-number">{{ $ticket->numero }}</td>
                                <td>{{ $ticket->unidade->unidade ?? '—' }}</td>
                                <td>{{ $ticket->equipamento->equipamento ?? '—' }}</td>
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
                                    <span>Quando houver uma solicitação, ela aparecerá aqui.</span>
                                    <a href="{{ route('tickets.index') }}">Abrir o primeiro chamado</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </section>
@stop
