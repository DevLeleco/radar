@extends('operations.master.admin')

@section('title', 'Meu painel')

@section('content_header')
    <div class="client-dashboard-heading">
        <div>
            <span class="dashboard-eyebrow">Área técnica</span>
            <h1>Olá, {{ $technician->nome }}</h1>
            <p>Veja seus atendimentos, acompanhe o que já concluiu e encontre chamados disponíveis.</p>
        </div>
        <a class="btn btn-primary" href="{{ route('admin.tickets.queue') }}">
            <i class="fas fa-clipboard-list mr-1" aria-hidden="true"></i> Abrir fila técnica
        </a>
    </div>
@stop

@section('content')
    <section class="client-dashboard technician-dashboard" aria-label="Resumo da atividade técnica">
        <div class="dashboard-stats technician-stats">
            <article class="dashboard-stat stat-progress">
                <span class="stat-icon"><i class="fas fa-tools" aria-hidden="true"></i></span>
                <span class="stat-label">Meus atendimentos</span>
                <strong>{{ number_format($inProgressCount, 0, ',', '.') }}</strong>
                <span class="stat-note">Em andamento comigo</span>
            </article>
            <article class="dashboard-stat stat-open">
                <span class="stat-icon"><i class="fas fa-inbox" aria-hidden="true"></i></span>
                <span class="stat-label">Disponíveis na fila</span>
                <strong>{{ number_format($availableCount, 0, ',', '.') }}</strong>
                <span class="stat-note">Abertos no meu cliente</span>
            </article>
            <article class="dashboard-stat stat-complete">
                <span class="stat-icon"><i class="fas fa-check-circle" aria-hidden="true"></i></span>
                <span class="stat-label">Concluídos por mim</span>
                <strong>{{ number_format($completedCount, 0, ',', '.') }}</strong>
                <span class="stat-note">Atendimentos finalizados</span>
            </article>
        </div>

        <section class="card dashboard-recent-card" aria-labelledby="my-active-tickets-title">
            <div class="card-header dashboard-recent-header">
                <div>
                    <h2 class="card-title" id="my-active-tickets-title">Meus atendimentos em andamento</h2>
                    <p>Chamados atribuídos a você que aguardam conclusão.</p>
                </div>
                <a href="{{ route('admin.tickets.queue') }}">Abrir fila técnica <i class="fas fa-arrow-right ml-1" aria-hidden="true"></i></a>
            </div>
            <div class="table-responsive">
                <table class="table dashboard-ticket-table">
                    <thead>
                        <tr>
                            <th scope="col">Chamado</th>
                            <th scope="col">Unidade</th>
                            <th scope="col">Equipamento</th>
                            <th scope="col">Abertura</th>
                            <th scope="col">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($activeTickets as $ticket)
                            <tr>
                                <td class="ticket-number">{{ $ticket->numero }}</td>
                                <td>{{ $ticket->unidade->unidade ?? '—' }}</td>
                                <td>{{ $ticket->equipamento->equipamento ?? '—' }}</td>
                                <td>{{ $ticket->dt_ini ? \Carbon\Carbon::parse($ticket->dt_ini)->format('d/m/Y') : '—' }}</td>
                                <td>
                                    <a class="btn btn-sm btn-primary" href="{{ route('admin.tickets.work-order.show', $ticket->id_chamado) }}">
                                        Continuar atendimento
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="dashboard-empty-state">
                                    <span class="empty-state-icon"><i class="fas fa-clipboard-check" aria-hidden="true"></i></span>
                                    <strong>Você não tem atendimentos em andamento</strong>
                                    <span>Consulte a fila para encontrar chamados disponíveis no seu cliente.</span>
                                    <a href="{{ route('admin.tickets.queue') }}">Ver fila técnica</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <div class="technician-dashboard-columns">
            <section class="card dashboard-recent-card" aria-labelledby="available-tickets-title">
                <div class="card-header dashboard-recent-header">
                    <div>
                        <h2 class="card-title" id="available-tickets-title">Chamados disponíveis</h2>
                        <p>Chamados abertos ainda sem técnico.</p>
                    </div>
                    <a href="{{ route('admin.tickets.queue') }}">Ver fila</a>
                </div>
                <div class="technician-ticket-list">
                    @forelse ($availableTickets as $ticket)
                        <article class="technician-ticket-item">
                            <div class="technician-ticket-info">
                                <strong>{{ $ticket->numero }}</strong>
                                <span>{{ $ticket->unidade->unidade ?? 'Unidade não informada' }} · {{ $ticket->equipamento->equipamento ?? 'Equipamento não informado' }}</span>
                                <small>Aberto em {{ $ticket->dt_ini ? \Carbon\Carbon::parse($ticket->dt_ini)->format('d/m/Y') : '—' }}</small>
                            </div>
                            <form method="POST" action="{{ route('admin.tickets.start') }}">
                                @csrf
                                <input type="hidden" name="id_chamado" value="{{ $ticket->id_chamado }}">
                                <button type="submit" class="btn btn-sm btn-default">Assumir</button>
                            </form>
                        </article>
                    @empty
                        <div class="technician-list-empty">Não há chamados abertos sem técnico no momento.</div>
                    @endforelse
                </div>
            </section>

            <section class="card dashboard-recent-card" aria-labelledby="completed-tickets-title">
                <div class="card-header dashboard-recent-header">
                    <div>
                        <h2 class="card-title" id="completed-tickets-title">Concluídos recentemente</h2>
                        <p>Seus últimos atendimentos finalizados.</p>
                    </div>
                </div>
                <div class="technician-ticket-list">
                    @forelse ($recentCompletedTickets as $ticket)
                        <article class="technician-ticket-item technician-completed-item">
                            <div class="technician-ticket-info">
                                <strong>{{ $ticket->numero }}</strong>
                                <span>{{ $ticket->unidade->unidade ?? 'Unidade não informada' }} · {{ $ticket->equipamento->equipamento ?? 'Equipamento não informado' }}</span>
                                <small>Concluído em {{ $ticket->dt_fim ? \Carbon\Carbon::parse($ticket->dt_fim)->format('d/m/Y') : '—' }}</small>
                            </div>
                            <span class="ticket-status status-complete">Concluído</span>
                        </article>
                    @empty
                        <div class="technician-list-empty">Seus chamados concluídos aparecerão aqui.</div>
                    @endforelse
                </div>
            </section>
        </div>
    </section>
@stop
