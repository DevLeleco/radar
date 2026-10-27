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
		<h1 class="display-4">Abertura de Chamados</h1>		
		<p class="lead">Cadastre uma Ordem de Serviço e tenha um histórico das ocorrências.</p>
	</div>
</div>

 <!-- início do card 1 -->
 <div class="card card-default">
    <form method="post"  action="{{route('tickets.store')}}" >
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                      <div>
                        <label for="exampleInputPassword1">Cliente</label>
                        <select class="form-control select" id="cliente" name="id_cliente" required>
                          <option selected="selected"></option>
                            @if(isset($clients))
                              @foreach($clients as $client)                                
                                <option value="{{$client->id_cliente}}" @selected(isset($qrSelection) && (int) $qrSelection->id_cliente === (int) $client->id_cliente)>{{$client->cliente}}</option>
                              @endforeach   
                            @endif
                        </select>
                      </div>
                      <div>
                        <label for="exampleInputPassword1">Unidade</label>
                        <select class="form-control select" id="id_unidade" name="id_unidade" required></select>
                      </option>
                      </div>
                      <div>
                        <label for="exampleInputPassword1">Equipamento</label>
                        <select class="form-control select" id="id_equipamento" name="id_equipamento" required></select>
                      </option>
                      </div>

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <div>
                            <label for="exampleInputPassword1">Ocorrência</label>
			                      <textarea class="form-control"  rows="7" name="ocorrencia" required></textarea>
                        </div>
                    </div>
                </div>               
            </div>
            <button type="submit" class="btn btn-primary" value="Salvar">Cadastrar</button>  
        </div>
    </form>
</div>
<!-- fim do card 1 -->

<!-- início do card 2 -->
<div class="card card-default" >
    <div class="card-header">
  
      <h3 class="card-title">Chamados Cadastrados</h3>
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
          @if(isset($tickets))
            @foreach($tickets as $ticket) 
              <tr>
                  <td>{{$ticket->numero}}</td>
                  <td>{{$ticket->dt_ini}}</td>
                  <td>{{$ticket->cliente}}</td>  
                  <td>{{$ticket->unidade}}</td>
                  <td>
                    @if($ticket->status == 0)
                      Aberto
                      @elseif($ticket->status == 1)
                        Em Andamento
                      @else
                        Concluido
                    @endif
                  </td>
                  <td>
                    <a href="{{ route('tickets.show', $ticket->id_chamado) }}"  data-toggle="modal" data-uid="1" name='btn-editar' class="btn btn-primary edit btn_id" style=""><i class="fas fa-pencil-alt"></i> </a> 
                    <form action="{{ route('tickets.destroy', $ticket->id_chamado) }}" method="POST" style="display:inline">
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

  <form action="{{ route('admin.tickets.notes.update') }}" method="post">
    @csrf
    <div id="edit" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Editar</h4>
          </div>
          <div class="modal-body">
              <label for="exampleInputPassword1">Cliente</label>
              <input  type="text" class="form-control" name="edit_cliente" id="edit_cliente" disabled>
              <label for="exampleInputPassword1">Unidade</label> 
              <input  type="text" class="form-control" name="edit_unidade" id="edit_unidade" disabled >
              <label for="exampleInputPassword1">Equipamento</label>
              <input  type="text" class="form-control" name="edit_equipamento" id="edit_equipamento" disabled>
              <label for="exampleInputPassword1">Ocorrência</label>
			        <textarea class="form-control"  rows="4" name="edit_ocorrencia" id="edit_ocorrencia" disabled></textarea>
              <label for="exampleInputPassword1">Observação</label>
              <textarea class="form-control" rows="4" name="edit_observacao" id="edit_observacao" @if(Auth::user()->perfil == 2) disabled @endif></textarea>
              <input  type="hidden" class="form-control" name="edit_id_chamado" id="edit_id_chamado" >
          </div>
          <div class="modal-footer">
            @if (Auth::user()->perfil == 1)
              <button type="submit" class="btn btn-primary">Salvar</button>
            @endif
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
            $("#edit_cliente").val(data.cliente);
            $("#edit_unidade").val(data.unidade);
            $("#edit_equipamento").val(data.equipamento);
            $("#edit_ocorrencia").val(data.ocorrencia);
            $("#edit_observacao").val(data.obs_tecnico);
            $("#edit_id_chamado").val(data.id_chamado);
            
            $('#edit').modal('show');
  
      });
    });
  
    $('.btn_delete').on("click", function(e){
      e.preventDefault(); //Evitar que o href do botão seja ativado, forçando passar pela função
  
      swal.fire({
        title: "Voce tem Certeza?",
        text: "Voce irá cancelar este chamado",
        icon: 'error',
        showCancelButton: true,
        cancelButtonText:"Sair!",
        confirmButtonText:"Cancelar!",
  
      }).then((result) => {
        if(result.isConfirmed){
          $(e.currentTarget).closest('form').trigger('submit');
        }
      });
  });
  </script>
  <script>
    $(function(){
        var qrSelection = @json(isset($qrSelection) ? [
          'cliente' => (string) $qrSelection->id_cliente,
          'unidade' => (string) $qrSelection->id_unidade,
          'equipamento' => (string) $qrSelection->id_equipamento,
        ] : null);

        $('#cliente').change(function(){
           $("#id_unidade option").remove();
           $("#id_equipamento option").remove();
           var id = $(this).val(); 
         
           $.ajax({
              url : '{{ route( 'lookups.units.by-client' ) }}',
              data: {
                "_token": "{{ csrf_token() }}",
                "id": id
                },
              type: 'post',
              dataType: 'json',
              success: function( result )
              {
                   $.each( result, function(k, v) {
                        $('#id_unidade').append($('<option>', {value:k, text:v}));
                   });
                   if (qrSelection && String(id) === qrSelection.cliente) {
                     $('#id_unidade').val(qrSelection.unidade).trigger('change');
                   }
              },
              error: function()
             {
                 //handle errors
                 alert('error...');
             }
           });
        });
        $('#id_unidade').on('change',function(){
           $("#id_equipamento option").remove();
           var id = $(this).val(); 
         
           $.ajax({
              url : '{{ route( 'lookups.equipment.by-unit' ) }}',
              data: {
                "_token": "{{ csrf_token() }}",
                "id": id
                },
              type: 'post',
              dataType: 'json',
              success: function( result )
              {
                   $.each( result, function(k, v) {
                        $('#id_equipamento').append($('<option>', {value:k, text:v}));
                   });
                   if (qrSelection && String(id) === qrSelection.unidade) {
                     $('#id_equipamento').val(qrSelection.equipamento);
                   }
              },
              error: function()
             {
                 //handle errors
                 alert('error...');
             }
           });
        });

        if (qrSelection) {
          $('#cliente').trigger('change');
        }
    });
  </script>


@stop
