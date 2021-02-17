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
		<h1 class="display-4">Cadastro de Unidade!</h1>		
		<p class="lead">Insira a Unidade de sua preferência, lembre-se você pode cadastrar mais de uma unidade.</p>
	</div>
</div>

 <!-- início do card 1 -->
 <div class="card card-default">
    <form method="post" action="{{route('rt.add_unidade')}}" >
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                      <div>
                        <label for="exampleInputPassword1">Cliente</label>
                        <select class="form-control select" id="profissional_id" name="id_cliente" required>
                          <option selected="selected"></option>
                            @if(isset($clientes))
                              @foreach($clientes as $cliente)                                
                                <option value="{{$cliente->id_cliente}}">{{ $cliente->cliente }}</option>
                              @endforeach   
                            @endif
                        </select>
                      </div>
                        <div>
                            <label for="exampleInputEmail1">Unidade</label>
			                      <input class="form-control" type="text" name="unidade" required name="unidade">
                        </div>
                        <div>
                            <label for="exampleInputPassword1">Endereço</label>
			                <input class="form-control" type="text" name="endereco" required name="endereco">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <div>
                            <label for="exampleInputPassword1">Descrição</label>
			                <textarea class="form-control" id="exampleFormControlTextarea1" rows="7" name="descricao" required name="descricao"></textarea>
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
  
      <h3 class="card-title">Unidades Cadastradas</h3>
    </div>
    <div class="bs-example card-body" data-example-id="striped-table" >
      <table class="table table-striped table-bordered table-sm" id="myTable">
        <thead>
          <tr>
            <th>Unidade</th>
            <th>Cliente</th>
            <th>Endereço</th>
            <th>Descrição</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          @if(isset($unidades))
            @foreach($unidades as $unidade) 
              <tr>
                <td>{{$unidade->unidade}}</td>
                <td>{{$unidade->cliente}}</td>
                <td>{{$unidade->endereco}}</td>  
                <td>{{$unidade->descricao}}</td>
                <td>
                  <a href="{{ route('rt.ver_unidade', $unidade->id_unidade) }}"  data-toggle="modal" data-uid="1" name='btn-editar' class="btn btn-primary edit btn_id" style=""><i class="fas fa-pencil-alt"></i> </a> 
                  <a href="{{route('rt.delete_unidade', $unidade->id_unidade)}}" name='btn-deletar' class="btn btn-danger btn_delete"><i class="fas fa-trash"></i></a>      
                </td>                 
              </tr>
            @endforeach   
          @endif
        </tbody>
      </table>
    </div>
  </div>

  <form action="{{ route('rt.edit_unidade') }}" method="post">
    @csrf  
    <div id="edit" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Editar</h4>
          </div>
          <div class="modal-body">
              <label for="exampleInputPassword1">Unidade</label> 
              <input  type="text" class="form-control" name="edit_unidade" id="edit_unidade" >
              <label for="exampleInputPassword1">Cliente</label>
              <select class="form-control select" id="edit_id_cliente" name="edit_id_cliente" required>
                    @foreach($clientes as $cliente)                                
                      <option value="{{$cliente->id_cliente}}">{{$cliente->cliente}}</option>
                    @endforeach   
              </select>
              <label for="exampleInputPassword1">Endereço</label>
              <input  type="text" class="form-control" name="edit_endereco" id="edit_endereco">
              <label for="exampleInputPassword1">Descrição</label>
              <input  type="text" class="form-control" name="edit_descricao" id="edit_descricao">
              <input  type="hidden" class="form-control" name="edit_id_unidade" id="edit_id_unidade" >
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
            $("#edit_id_unidade").val(data.id_unidade);
            $("#edit_unidade").val(data.unidade);
            $("#edit_id_cliente").val(data.id_cliente);
            $("#edit_cliente").val(data.cliente);
            $("#edit_endereco").val(data.endereco);
            $("#edit_descricao").val(data.descricao);
            
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
