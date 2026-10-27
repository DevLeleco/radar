@extends('operations.master.layout')

@section('content')
  <section class="jumbotron text-center mt-5">
    <div class="container">
      <h1 class="jumbotron-heading">Radar</h1>
      <p class="lead text-muted">Gestão de clientes, unidades e equipamentos, com abertura e acompanhamento de chamados de manutenção.</p>
      @if (Auth::check())
        <a class="btn btn-primary my-2" href="{{ Auth::user()->perfil == 3 ? route('admin.tickets.queue') : route('tickets.index') }}">Acessar chamados</a>
      @else
        <a class="btn btn-primary my-2" href="{{ route('login') }}">Entrar</a>
      @endif
    </div>
  </section>

  <section class="container py-4" aria-label="Funcionalidades do Radar">
    <div class="row">
      <div class="col-md-4">
        <h2>Chamados</h2>
        <p>Registre ocorrências e acompanhe o andamento do atendimento.</p>
      </div>
      <div class="col-md-4">
        <h2>Ativos e unidades</h2>
        <p>Mantenha a estrutura de clientes, unidades e equipamentos organizada.</p>
      </div>
      <div class="col-md-4">
        <h2>Consulta por QR code</h2>
        <p>Consulte o equipamento identificado e inicie um chamado a partir do código.</p>
      </div>
    </div>
  </section>
@endsection
