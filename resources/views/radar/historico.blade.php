@extends('adminlte::page')

<style>
  @import url('https://fonts.googleapis.com/css2?family=Open+Sans+Condensed:wght@300&display=swap');
</style>
<style>
  .modal .modal-dialog {width: 30%}
</style>

@section('content')

<script src=" {{ asset('vendor/jquery/jquery.js') }}"></script>


<div class="jumbotron py-1">
	<div class="container text-center">
		<h1 class="display-4">Historico de Chamados</h1>		
		<p class="lead">Cadastre uma Ordem de Serviço e tenha um histórico das ocorrências.</p>
	</div>
</div>


<!-- início do card 2 -->
<div class="card card-default" >
    <div class="card-header">
  
      <h3 class="card-title">Chamados Concluidos</h3>
    </div>
    <div class="bs-example card-body" data-example-id="striped-table" >
      <table class="table table-striped table-bordered table-sm" id="myTable">
        <thead>
          <tr>
            <th>Nº do Chamado</th>
            <th>Data de Abertura</th>
            <th>Cliente</th>
            <th>Unidade</th>
            <th>Status</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          @if(isset($chamados))
            @foreach($chamados as $chamado) 
              <tr>
                  <td>{{$chamado->numero}}</td>
                  <td>{{$chamado->dt_ini}}</td>
                  <td>{{$chamado->cliente}}</td>  
                  <td>{{$chamado->unidade}}</td>
                  <td>
                    @if($chamado->status == 2)
                        Concluido
                    @endif
                  </td>
                  <td>
                    <a href="{{ route('rt.ver_chamados', $chamado->id_chamado) }}"  data-toggle="modal" data-uid="1" name='btn-editar' class="btn btn-primary edit btn_id" style=""><i class="fas fa-eye"></i> </a> 
                  </td>                  
              </tr>
            @endforeach   
          @endif
        </tbody>
      </table>
    </div>
  </div>

  <form action="" method="post">
    @csrf  
    <div id="edit" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Visualizar</h4>
          </div>
          <div class="modal-body">
            <label for="exampleInputEmail1">Nº do Chamado</label>
            <input class="form-control" type="text" name="edit_numero" id="edit_numero" disabled>
            <label for="data_chamado">Data Abertura</label>
            <input placeholder="Select date" type="date" class="form-control" name="edit_dt_ini" id="edit_dt_ini" disabled>
            <label for="data_chamado">Data Fechamento</label>
            <input placeholder="Select date" type="date" class="form-control" name="edit_dt_fim" id="edit_dt_fim" disabled>
            <label for="exampleInputEmail1">Usuario</label>
            <input class="form-control" type="text" name="edit_usuario" id="edit_usuario" disabled>
            <label for="exampleInputPassword1">Cliente</label>
            <input class="form-control" type="text" name="edit_cliente"  id="edit_cliente" disabled>
            <label for="exampleInputPassword1">Unidade</label>
            <input class="form-control" type="text" name="edit_unidade" id="edit_unidade" disabled>
            <label for="exampleInputPassword1">Equipamento</label>
            <input class="form-control" type="text" name="edit_equipamento" id="edit_equipamento" disabled>
            <label for="exampleInputPassword1">Técnico</label>
            <input class="form-control" type="text" name="edit_tecnico" id="edit_tecnico" disabled>
            <label for="exampleInputPassword1">Ocorrência</label>
            <textarea class="form-control" rows="2" name="edit_ocorrencia" id="edit_ocorrencia" disabled></textarea>
            <label for="exampleInputPassword1">Solução</label>
            <textarea class="form-control" rows="2" name="edit_solucao" id="edit_solucao" disabled></textarea>
            {{-- <label for="exampleInputPassword1">Satisfação</label>
            <select class="form-control select"  name="edit_satisfacao" id="edit_satisfacao" disabled>
              <option value="0">Aguardando...</option>
              <option value="1">Satisfatório</option>
              <option value="2">Regular</option>
              <option value="3">Não Satisfatório</option>
            </select> --}}
              <input  type="hidden" class="form-control" name="edit_id_chamado" id="edit_id_chamado" >
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
          </div>
        </div>
      </div>
    </div>
  </form>

  <script>
    $(document).ready( function () {
        $('#myTable').DataTable({
          responsive: true,
          "language":{
          "info": "Mostrando Pagina _PAGE_ De _PAGES_",
          "search": "Pesquisar"
          },
          dom: 'lBfrtip',
           
        buttons: [
          {
            extend: 'excelHtml5',
            title: 'Unidade Radar|PROMPT'
          }
        ]
        });
    } );
  </script>
  <script> 
    $('.edit').on("click", function(e) {
      $.ajax({
        url: $(this).attr('href'),
        type: 'get',
        dataType: "json",
        
      }).done(function(data) {
            $("#edit_id_chamado").val(data.id_chamado);
            $("#edit_numero").val(data.numero);
            $("#edit_dt_ini").val(data.dt_ini);
            $("#edit_dt_fim").val(data.dt_fim);
            $("#edit_usuario").val(data.name);
            $("#edit_cliente").val(data.cliente);
            $("#edit_unidade").val(data.unidade);
            $("#edit_equipamento").val(data.equipamento);
            $("#edit_tecnico").val(data.nome);
            $("#edit_ocorrencia").val(data.ocorrencia);
            $("#edit_solucao").val(data.solucao);
            $("#edit_satisfacao").val(data.satisfacao);
            
            $('#edit').modal('show');
      });
    });
  
  </script>


@stop
