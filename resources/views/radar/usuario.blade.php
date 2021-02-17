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
		<h1 class="display-4">Cadastro de Usuário!</h1>		
		<p class="lead">Insira a Unidade de sua preferência, lembre-se você pode cadastrar mais de uma unidade.</p>
	</div>
</div>

 <!-- início do card 1 -->
<div class="card card-default">
  <form method="post"  action="{{route('rt.add_usuario')}}" >
    @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <div>
                            <label for="exampleInputEmail1">Nome do Usuário</label>
			                      <input class="form-control" type="text" name="usuario" required>
                        </div>
                        <div>
                            <label for="exampleInputPassword1">E-mail</label>
			                    <input class="form-control" type="text" name="email" required>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                      <div>
                        <label for="exampleInputPassword1">Cliente</label>
                        <select class="form-control select"  name="id_cliente" required>
                          <option selected="selected"></option>
                            @if(isset($clientes))
                              @foreach($clientes as $cliente)                                
                                <option value="{{$cliente->id_cliente}}">{{ $cliente->cliente }}</option>
                              @endforeach   
                            @endif
                        </select>
                      </div>
                      <div>
                        <label for="exampleInputPassword1">Perfil</label>
                        <select class="form-control select"  name="perfil" required>
                          <option selected="selected"></option>
                                <option value="1">Administrador</option>
                                <option value="2">Cliente</option>
                        </select>
                      </div>
                    </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <div>
                      <label for="exampleInputPassword1">Senha</label> <span toggle="#password-field" class="fa fa-fw fa-eye field_icon toggle-password"></span>
                      <input class="form-control" type="password" name="password" id="pass_log_id" required>
                      
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
  
      <h3 class="card-title">Clientes Cadastrados</h3>
    </div>
    <div class="bs-example card-body" data-example-id="striped-table" >
      <table class="table table-striped table-bordered table-sm" id="myTable">
        <thead>
          <tr>
            <th>Usuário</th>
            <th>E-mail</th>
            <th>Cliente</th>
            <th>Perfil</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          @if(isset($usuarios))
            @foreach($usuarios as $usuario) 
            <tr>
                <td>{{$usuario->name}}</td>
                <td>{{$usuario->email}}</td>
                <td>{{$usuario->cliente}}</td>  
                <td>
                  @if($usuario->perfil == 1)
                    Administrador
                  @else
                    Cliente
                  @endif
                </td>
                <td>
                  <a href="{{ route('rt.ver_usuario', $usuario->id) }}"  data-toggle="modal" data-uid="1" name='btn-editar' class="btn btn-primary edit btn_id" style=""><i class="fas fa-pencil-alt"></i> </a> 
                  <a href="{{route('rt.delete_usuario', $usuario->id)}}" name='btn-deletar' class="btn btn-danger btn_delete"><i class="fas fa-trash"></i></a>
                 <a href="{{route('rt.reset_usuario', $usuario->id)}}" name='btn-deletar' class="btn btn-warning reset btn_id"><i class="fas fa-key alt"></i></a>      
                </td>
              </tr>
            @endforeach   
          @endif
        </tbody>
      </table>
    </div>
  </div>


  
  <form action="{{ route('rt.edit_usuario') }}" method="post">
    @csrf  
    <div id="edit" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Editar</h4>
          </div>
          <div class="modal-body">
              <label for="exampleInputPassword1">Usuário</label> 
              <input  type="text" class="form-control" name="edit_usuario" id="edit_usuario" >
              <label for="exampleInputPassword1">Email</label>
              <input  type="text" class="form-control" name="edit_email" id="edit_email">
              <label for="exampleInputPassword1">Cliente</label>
                <select class="form-control select" id="edit_id_cliente" name="edit_id_cliente" required>
                  @foreach($clientes as $cliente)                                
                    <option value="{{$cliente->id_cliente}}">{{$cliente->cliente}}</option>
                  @endforeach   
                </select>
              <label for="exampleInputPassword1">Perfil</label>
              <select class="form-control select" name="edit_perfil" id="edit_perfil" required>
                  <option value="1">Adminisrador</option>
                  <option value="2">Cliente</option>
              </select>
              <input  type="hidden" class="form-control" name="id" id="id" >
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
        title: 'Ação BS-Inventario'
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
          $("#id").val(data.id);
          $("#edit_usuario").val(data.name);
          $("#edit_cliente").val(data.cliente);
          $("#edit_email").val(data.email);
          $("#edit_id_cliente").val(data.id_cliente);
          $("#edit_perfil").val(data.perfil);
          
          $('#edit').modal('show');

    });
  });

  $('.btn_delete').on("click", function(e){
    e.preventDefault(); //Evitar que o href do botão seja ativado, forçando passar pela função
    const url = $(this).attr('href');

    swal.fire({
      title: "Voce tem Certeza?",
      text: "Voce irá deletar este item",
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

$("body").on('click','.toggle-password', function() {
  $(this).toggleClass("fa-eye fa-eye-slash");
  var input = $("#pass_log_id");
  if (input.attr("type") === "password") {
      input.attr("type", "text");
  } else {
      input.attr("type", "password");
  }
})
</script>

@stop
