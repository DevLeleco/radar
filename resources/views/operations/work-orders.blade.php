@extends('operations.master.admin')

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
  swal.fire(@json(session('Alert')))
  @endif

  @if(session('Danger'))
  swal.fire(@json(session('Danger')))
  @endif

  @if(session('Reactivate'))
  swal.fire(@json(session('Reactivate')))
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
  <form method="post"  action="{{route('admin.work-orders.store')}}" >
    @csrf
        <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="numero">Nº da OS</label>
                  <input class="form-control" type="text" name="numero" id="numero" maxlength="64" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="id_chamado">Chamado concluído</label>
                  <select class="form-control" name="id_chamado" id="id_chamado" required>
                    <option value="">Selecione um chamado</option>
                    @foreach($availableTickets as $ticket)
                      <option value="{{ $ticket->id_chamado }}">
                        {{ $ticket->numero }} — {{ $ticket->cliente->cliente }} — {{ $ticket->equipamento->equipamento }}
                      </option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <p class="text-muted">Cliente, equipamento, técnico, ocorrência, solução e datas serão copiados do chamado selecionado.</p>
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
            <th>Chamado</th>
            <th>Usuário</th>
            <th>Cliente</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          @if(isset($workOrders))
            @foreach($workOrders as $workOrder) 
              <tr>
                  <td>{{$workOrder->numero}}</td>
                  <td>{{$workOrder->dt_ini}}</td>
                  <td>{{$workOrder->chamado->numero ?? 'Legado sem vínculo'}}</td>
                  <td>{{$workOrder->usuario}}</td>  
                  <td>{{$workOrder->cliente}}</td>
                  <td>
                    <a href="{{ route('admin.work-orders.show', $workOrder->id_os) }}"  data-toggle="modal" data-uid="1" name='btn-editar' class="btn btn-primary edit btn_id" style=""><i class="fas fa-pencil-alt"></i> </a> 
                    <form action="{{ route('admin.work-orders.destroy', $workOrder->id_os) }}" method="POST" style="display:inline">
                      @csrf @method('DELETE')
                      <button type="submit" name="btn-deletar" class="btn btn-danger btn_delete"><i class="fas fa-trash"></i></button>
                    </form>
                  </td> 
              </tr>
            @endforeach   
          @endif
        </tbody>
      </table>
    </div>
  </div>

  <form action="{{ route('admin.work-orders.update') }}" method="post">
    @csrf  
    <div id="edit" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Editar</h4>
          </div>
          <div class="modal-body">
            <label for="exampleInputEmail1">Nº da OS</label>
            <input class="form-control" type="text" name="edit_numero" id="edit_numero" maxlength="64" required>
            <input  type="hidden" class="form-control"  id="edit_id_os" name="edit_id_os">
            <p class="text-muted mt-2">Os dados de atendimento são mantidos conforme o chamado vinculado.</p>
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

            
            $('#edit').modal('show');
  
      });
    });
  
    $('.btn_delete').on("click", function(e){
      e.preventDefault(); //Evitar que o href do botão seja ativado, forçando passar pela função
  
      swal.fire({
        title: "Voce tem Certeza?",
        text: "Voce irá desativar este item",
        icon: 'error',
        showCancelButton: true,
        cancelButtonText:"Cancelar!",
        confirmButtonText:"Apagar!",
  
      }).then((result) => {
        if(result.isConfirmed){
          $(e.currentTarget).closest('form').trigger('submit');
        }
      });
  });
  </script>


@stop
