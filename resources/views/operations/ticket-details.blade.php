@extends('operations.master.admin')

<style>
  @import url('https://fonts.googleapis.com/css2?family=Open+Sans+Condensed:wght@300&display=swap');
</style>
<style>
  p{
    margin: 0 !important; 
    padding: 1 !important; 
  }
</style>
<style>
  .modal .modal-dialog {width: 30%}
</style>

@section('content')

<script src=" {{ asset('vendor/jquery/jquery.js') }}"></script>


<div class="jumbotron py-1">
	<div class="container text-center">
		<h1 class="display-4">Tratando Chamado</h1>		
		<p class="lead">Cadastre uma Ordem de Serviço e tenha um histórico das ocorrências.</p>
	</div>
</div>

<!-- início do card 1 -->
<div class="card card-default">
  <form method="post"  action="{{route('admin.tickets.complete')}}">
    @csrf
    @if(isset($tickets))
      @foreach($tickets as $ticket)
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <div>
                          <div class="card bg-light mb-3" style="max-width: 18rem">
                            
                                <input type="hidden" value="{{$ticket->id_chamado}}" name="id_chamado">
                                <div class="card-header">Chamado Nº {{$ticket->numero}}</div>
                                  <div class="card-body">
                                      <h5 class="card-title">Informações:</h5>
                                      <p class="card-text">Abertura: {{$ticket->dt_ini}}</p>
                                      <p>Usuário: {{ $ticket->name ?? 'Conta removida' }}</p>
                                      <p >Cliente:  {{$ticket->cliente}}</p>
                                      <p >Unidade:  {{$ticket->unidade}}</p>
                                      <p >Equipamento:  {{$ticket->equipamento}}</p>
                                      <p >Ocorrência:  {{$ticket->ocorrencia}}</p>
                                      <p >Obs:  {{$ticket->obs_tecnico}}</p>
                                    
                                  </div>
                              </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                      <div>
                        <label for="exampleInputPassword1">Solução</label>
                        <textarea class="form-control" rows="4" name="solucao" required></textarea>
                      </div>
                      <div>
                        <label for="exampleInputPassword1">Observação</label>
                      <textarea class="form-control" rows="4" name="observacao" maxlength="5000"></textarea>
                      </div>
                      
                    </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <div>
                      <label for="exampleInputPassword1">Status</label>
                      <input type="hidden" name="status" value="2">
                    </div>
                    <div>
                      <label for="exampleInputPassword1">Assinatura</label>
                      <textarea class="form-control" rows="7" name="assinatura" maxlength="5000" required></textarea>
                    </div>
                  </div>
                </div>                
            </div>
            <button  type="submit"  name='btn-close' class="btn btn-success"><i class="fas fa-check"></i></button > 
            <a href="{{route('admin.tickets.queue')}}" name='btn-close' class="btn btn-primary btn_close"><i class="fas fa-arrow-left"></i></a>   
        </div>
        @endforeach   
      @endif
  </form>
</div>
<!-- fim do card 1 -->







@stop
