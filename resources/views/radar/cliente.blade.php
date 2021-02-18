@extends('adminlte::page')

<style>
  @import url('https://fonts.googleapis.com/css2?family=Open+Sans+Condensed:wght@300&display=swap');
</style>
<style>
  .modal .modal-dialog {width: 30%}
</style>

@section('content')

<script
  src="https://code.jquery.com/jquery-3.5.1.js"
  integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc="
  crossorigin="anonymous"></script>
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
		<h1 class="display-4">Cadastro de Cliente!</h1>		
		<p class="lead">Insira a Unidade de sua preferência, lembre-se você pode cadastrar mais de uma unidade.</p>
	</div>
</div>

 <!-- início do card 1 -->
 <div class="card card-default">
    <form method="post"  action="{{route('rt.add_cliente')}}" >
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <div>
                            <label for="exampleInputEmail1">Nome do Cliente</label>
			                      <input class="form-control" type="text" name="cliente" required name="unidade">
                        </div>
                        <div>
                            <label for="exampleInputPassword1">CNPJ/CPF</label>
			                    <input class="form-control" type="text" name="cnpj_cpf" required name="endereco">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <div>
                          <label for="exampleInputPassword1">Seguimento</label>
			                    <input class="form-control" type="text" name="seguimento" required name="endereco">
                        </div>
                        <div>
                          <label for="exampleInputPassword1">Endereço</label>
			                    <input class="form-control" type="text" name="endereco" required name="endereco">
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
  
      <h3 class="card-title">Clientes Cadastrados</h3>
      
    </div>
    <div class="bs-example card-body" data-example-id="striped-table" >
      <table class="table table-striped table-bordered table-sm" id="myTable">
        <thead>
          <tr>
            <th>Nome</th>
            <th>CNPJ/CPF</th>
            <th>Seguimento</th>
            <th>Endereço</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          @if(isset($clientes))
            @foreach($clientes as $cliente) 
            <tr>
                <td>{{$cliente->cliente}}</td>
                <td>{{$cliente->cnpj_cpf}}</td> 
                <td>{{$cliente->seguimento}}</td>  
                <td>{{$cliente->endereco}}</td>
                <td>
                  <a href="{{route('rt.ver_cliente', $cliente->id_cliente) }}" data-toggle="modal" data-uid="1" name='btn-editar' class="btn btn-primary edit btn_id" style=""><i class="fas fa-pencil-alt"></i> </a> 
                  <a href="{{route('rt.delete_cliente', $cliente->id_cliente)}}" name='btn-deletar' class="btn btn-danger btn_delete"><i class="fas fa-trash"></i></a>      
                </td>
              </tr>
            @endforeach   
          @endif
        </tbody>
      </table>
    </div>
  </div>
 
  <form action="{{ route('rt.edit_cliente') }}" method="post">
    @csrf  
    <div id="edit" class="modal" role="dialog" style="">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Editar</h4>
          </div>
          <div class="modal-body">
              <label for="exampleInputPassword1">Cliente</label> 
              <input  type="text" class="form-control" name="edit_cliente" id="edit_cliente" >
              <label for="exampleInputPassword1">CNPJ/CPF</label>
              <input  type="text" class="form-control" name="edit_cnpj_cpf" id="edit_cnpj_cpf">
              <label for="exampleInputPassword1">Seguimento</label>
              <input  type="text" class="form-control" name="edit_seguimento" id="edit_seguimento">
              <label for="exampleInputPassword1">Endereço</label>
              <input  type="text" class="form-control" name="edit_endereco" id="edit_endereco">
              <input  type="hidden" class="form-control" name="edit_id_cliente" id="edit_id_cliente" >
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
          $("#edit_id_cliente").val(data.id_cliente);
          $("#edit_cliente").val(data.cliente);
          $("#edit_cnpj_cpf").val(data.cnpj_cpf);
          $("#edit_seguimento").val(data.seguimento);
          $("#edit_endereco").val(data.endereco);
          
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
