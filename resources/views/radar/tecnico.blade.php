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
		<h1 class="display-4">Cadastro do Técnico!</h1>		
		<p class="lead">Insira um Profissional com sua respectiva atribuição.</p>
	</div>
</div>

 <!-- início do card 1 -->
 <div class="card card-default">
    <form method="post"  action="{{route('rt.add_tecnico')}}" >
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                      <div>
                        <label for="exampleInputPassword1">Técnico</label>
                        <select class="form-control select"  name="nome" required>
                          <option selected="selected"></option>
                            @if(isset($usuarios))
                              @foreach($usuarios as $usuario)                                
                                <option value="{{$usuario->name}}">{{$usuario->name}}</option>
                              @endforeach   
                            @endif
                        </select>
                      </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                      <div>
                        <label for="exampleInputPassword1">Descrição</label>
                        <input class="form-control" type="text" name="descricao" required>
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
  
      <h3 class="card-title">Profissionais Cadastrados</h3>
    </div>
    <div class="bs-example card-body" data-example-id="striped-table" >
      <table class="table table-striped table-bordered table-sm" id="myTable">
        <thead>
          <tr>
            <th>Técnico</th>
            <th>Descrição</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          @if(isset($tecnicos))
            @foreach($tecnicos as $tecnico) 
            <tr>
                <td>{{$tecnico->nome}}</td>
                <td>{{$tecnico->descricao}}</td>
                <td>
                  <a href="{{route('rt.delete_tecnico', $tecnico->id_tecnico)}}" name='btn-deletar' class="btn btn-danger btn_delete"><i class="fas fa-trash"></i></a>
                </td>
            </tr>
            @endforeach   
          @endif
        </tbody>
      </table>
    </div>
  </div>

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
</script>
@stop
