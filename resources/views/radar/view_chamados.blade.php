@extends('adminlte::page')

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
  <form method="post"  action="{{route('rt.close_chamados')}}">
    @csrf
    @if(isset($chamados))
      @foreach($chamados as $chamado)
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <div>
                          <div class="card bg-light mb-3" style="max-width: 18rem">
                            
                                <input type="hidden" value="{{$chamado->id_chamado}}" name="id_chamado">
                                <div class="card-header">Chamado Nº {{$chamado->numero}}</div>
                                  <div class="card-body">
                                      <h5 class="card-title">Informações:</h5>
                                      <p class="card-text">Abertura: {{$chamado->dt_ini}}</p>
                                      <p >Usuário:  {{$chamado->name}}</p>
                                      <p >Cliente:  {{$chamado->cliente}}</p>
                                      <p >Unidade:  {{$chamado->unidade}}</p>
                                      <p >Equipamento:  {{$chamado->equipamento}}</p>
                                      <p >Ocorrência:  {{$chamado->ocorrencia}}</p>
                                      <p >Obs:  {{$chamado->obs_tecnico}}</p>
                                    
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
                      <textarea class="form-control" rows="4" name="observacao" required></textarea>
                      </div>
                      
                    </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <div>
                      <label for="exampleInputPassword1">Status</label>
                      <select class="form-control select"  name="status">
                        @if($chamado->status == 0)
                          <option value="0">Aberto</option>
                        @endif
                          <option value="1">Em Andamento</option>
                          <option value="2">Concluido</option>
                      </select>
                    </div>
                    <div>
                      <label for="exampleInputPassword1">Assinatura</label>
                      <textarea class="form-control" rows="7" name="assinatura" required></textarea>
                    </div>
                  </div>
                </div>                
            </div>
            <button  type="submit"  name='btn-close' class="btn btn-success"><i class="fas fa-check"></i></button > 
            <a href="{{route('rt.tec_chamados')}}" name='btn-close' class="btn btn-primary btn_close"><i class="fas fa-arrow-left"></i></a>   
        </div>
        @endforeach   
      @endif
  </form>
</div>
<!-- fim do card 1 -->







@stop
