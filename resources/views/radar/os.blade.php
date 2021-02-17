@extends('adminlte::page')

<style>
  @import url('https://fonts.googleapis.com/css2?family=Open+Sans+Condensed:wght@300&display=swap');
</style>
<style>
  .modal .modal-dialog {width: 30%}
</style>

@section('content')

<script src=" {{ asset('vendor/jquery/jquery.js') }}"></script>
<script src=" {{ asset('vendor/sweet/sweetalert2@10.js') }}"></script>

<script>
  @if(session('Alert'))
  swal.fire('{{ session('Alert') }}')
  @endif

  @if(session('Danger'))
  swal.fire('{{ session('Danger') }}')
  @endif

  @if(session('Reactivate'))
  swal.fire('{{ session('Reactivate') }}')
  @endif
</script>


<div class="jumbotron py-1">
	<div class="container text-center">
		<h1 class="display-4">Registro de O.S</h1>		
		<p class="lead">Cadastre uma Ordem de Serviço e tenha um histórico das ocorrências.</p>
	</div>
</div>

 <!-- início do card 1 -->
 <div class="card card-default">
  <form method="post"  action="{{route('rt.add_os')}}" >
    @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <div>
                          <label for="exampleInputEmail1">Nº da OS</label>
			                    <input class="form-control" type="text" name="numero" required>
                        </div>
                        <div>
                          <label for="data_chamado">Data Abertura</label>
                          <input placeholder="Select date" type="date" class="form-control" name="dt_ini" required>
                        </div>
                        <div>
                          <label for="data_chamado">Data Fechamento</label>
                          <input placeholder="Select date" type="date" class="form-control" name="dt_fim" required>
                        </div>
                        <div>
                          <label for="exampleInputEmail1">Usuario</label>
			                    <input class="form-control" type="text" name="usuario" required>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                      <div>
                        <label for="exampleInputPassword1">Cliente</label>
                        <input class="form-control" type="text" name="cliente" required>
                      </div>
                      <div>
                        <label for="exampleInputPassword1">Unidade</label>
                        <input class="form-control" type="text" name="unidade" required>
                      </div>
                      <div>
                        <label for="exampleInputPassword1">Equipamento</label>
                        <input class="form-control" type="text" name="equipamento" required>
                      </div>
                      <div>
                        <label for="exampleInputPassword1">Técnico</label>
                        <input class="form-control" type="text" name="tecnico" required>
                      </div>
                    </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <div>
                      <label for="exampleInputPassword1">Ocorrência</label>
                      <textarea class="form-control" rows="3" name="ocorrencia" required></textarea>
                    </div>
                    <div>
                      <label for="exampleInputPassword1">Solução</label>
                      <textarea class="form-control" rows="2" name="solucao" required></textarea>
                    </div>
                    <div>
                      <label for="exampleInputPassword1">Satisfação</label>
                      <select class="form-control select"  name="satisfacao" required>
                        <option selected="selected"></option>
                              <option value="1">Satisfatório</option>
                              <option value="2">Regular</option>
                              <option value="3">Não Satisfatório</option>
                      </select>
                    </div>
                  </div>
                </div>                
            </div>
            <button type="submit" class="btn btn-primary">Cadastrar</button>  
        </div>
  </form>
</div>
<!-- fim do card 1 -->

<!-- início do card 2 -->
<div class="card card-default" >
    <div class="card-header">
  
      <h3 class="card-title">O.S Cadastradas</h3>
    </div>
    <div class="bs-example card-body" data-example-id="striped-table" >
      <table class="table table-striped table-bordered table-sm" id="myTable">
        <thead>
          <tr>
            <th>N° O.S</th>
            <th>Data Inicial</th>
            <th>Usuário</th>
            <th>Cliente</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          @if(isset($oss))
            @foreach($oss as $os) 
              <tr>
                  <td>{{$os->numero}}</td>
                  <td>{{$os->dt_ini}}</td>
                  <td>{{$os->usuario}}</td>  
                  <td>{{$os->cliente}}</td>
                  <td>
                    <a href="{{ route('rt.ver_os', $os->id_os) }}"  data-toggle="modal" data-uid="1" name='btn-editar' class="btn btn-primary edit btn_id" style=""><i class="fas fa-pencil-alt"></i> </a> 
                    <a href="{{route('rt.delete_os', $os->id_os)}}" name='btn-deletar' class="btn btn-danger btn_delete"><i class="fas fa-trash"></i></a>      
                  </td> 
              </tr>
            @endforeach   
          @endif
        </tbody>
      </table>
    </div>
  </div>

  <form action="{{ route('rt.edit_os') }}" method="post">
    @csrf  
    <div id="edit" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Editar</h4>
          </div>
          <div class="modal-body">
            <label for="exampleInputEmail1">Nº da OS</label>
            <input class="form-control" type="text" name="edit_numero" id="edit_numero" required>
            <label for="data_chamado">Data Abertura</label>
            <input placeholder="Select date" type="date" class="form-control" name="edit_dt_ini" id="edit_dt_ini" required>
            <label for="data_chamado">Data Fechamento</label>
            <input placeholder="Select date" type="date" class="form-control" name="edit_dt_fim" id="edit_dt_fim" required>
            <label for="exampleInputEmail1">Usuario</label>
            <input class="form-control" type="text" name="edit_usuario" id="edit_usuario"required>
            <label for="exampleInputPassword1">Cliente</label>
            <input class="form-control" type="text" name="edit_cliente"  id="edit_cliente"required>
            <label for="exampleInputPassword1">Unidade</label>
            <input class="form-control" type="text" name="edit_unidade" id="edit_unidade" required>
            <label for="exampleInputPassword1">Equipamento</label>
            <input class="form-control" type="text" name="edit_equipamento" id="edit_equipamento">
            <label for="exampleInputPassword1">Técnico</label>
            <input class="form-control" type="text" name="edit_tecnico" id="edit_tecnico" required>
            <label for="exampleInputPassword1">Ocorrência</label>
            <textarea class="form-control" rows="2" name="edit_ocorrencia" id="edit_ocorrencia" required></textarea>
            <label for="exampleInputPassword1">Solução</label>
            <textarea class="form-control" rows="2" name="edit_solucao" id="edit_solucao" required></textarea>
            <label for="exampleInputPassword1">Satisfação</label>
            <select class="form-control select"  name="edit_satisfacao" id="edit_satisfacao" required>
              <option value="1">Satisfatório</option>
              <option value="2">Regular</option>
              <option value="3">Não Satisfatório</option>
            </select>
            <input  type="hidden" class="form-control"  id="edit_id_os" name="edit_id_os">
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Salvar</button>
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
            $("#edit_id_os").val(data.id_os);
            $("#edit_numero").val(data.numero);
            $("#edit_dt_ini").val(data.dt_ini);
            $("#edit_dt_fim").val(data.dt_ini);
            $("#edit_usuario").val(data.usuario);
            $("#edit_cliente").val(data.cliente);
            $("#edit_unidade").val(data.unidade);
            $("#edit_equipamento").val(data.equipamento);
            $("#edit_tecnico").val(data.tecnico);
            $("#edit_ocorrencia").val(data.ocorrencia);
            $("#edit_solucao").val(data.solucao);
            $("#edit_satisfacao").val(data.satisfacao);

            
            $('#edit').modal('show');
  
      });
    });
  
    $('.btn_delete').on("click", function(e){
      e.preventDefault(); //Evitar que o href do botão seja ativado, forçando passar pela função
      const url = $(this).attr('href');
  
      swal.fire({
        title: "Voce tem Certeza?",
        text: "Voce irá desativar este item",
        icon: 'error',
        showCancelButton: true,
        cancelButtonText:"Cancelar!",
        confirmButtonText:"Apagar!",
  
      }).then((result) => {
        if(result.isConfirmed){
          window.location.href = url;
        }
      });
  });
  </script>


@stop
