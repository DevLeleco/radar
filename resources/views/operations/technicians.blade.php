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
		<h1 class="display-4">Cadastro do Técnico!</h1>		
		<p class="lead">Insira um Profissional com sua respectiva atribuição.</p>
	</div>
</div>

 <!-- início do card 1 -->
 <div class="card card-default">
    <form method="post"  action="{{route('admin.technicians.store')}}" >
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                      <div>
                        <label for="technician-name">Nome</label>
                        <input class="form-control" id="technician-name" type="text" name="name" required maxlength="255">
                      </div>
                      <div>
                        <label for="technician-email">E-mail</label>
                        <input class="form-control" id="technician-email" type="email" name="email" required maxlength="255">
                      </div>
                      <div>
                        <label for="technician-client">Cliente</label>
                        <select class="form-control select" id="technician-client" name="id_cliente" required>
                          <option value="">Selecione um cliente</option>
                          @foreach($clients as $client)
                            <option value="{{ $client->id_cliente }}">{{ $client->cliente }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                      <div>
                        <label for="technician-password">Senha inicial</label>
                        <input class="form-control" id="technician-password" type="password" name="password" required minlength="12" autocomplete="new-password">
                      </div>
                      <div>
                        <label for="technician-password-confirmation">Confirme a senha</label>
                        <input class="form-control" id="technician-password-confirmation" type="password" name="password_confirmation" required minlength="12" autocomplete="new-password">
                      </div>
                      <div>
                        <label for="technician-description">Descrição</label>
                        <input class="form-control" id="technician-description" type="text" name="descricao" maxlength="5000">
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
            <th>E-mail</th>
            <th>Cliente</th>
            <th>Descrição</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          @if(isset($technicians))
            @foreach($technicians as $technician) 
            <tr>
                <td>{{$technician->nome}}</td>
                <td>{{$technician->email}}</td>
                <td>{{$technician->cliente_nome}}</td>
                <td>{{$technician->descricao}}</td>
                <td>
                  <form action="{{ route('admin.technicians.destroy', $technician->id_tecnico) }}" method="POST" style="display:inline">
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
    
        swal.fire({
          title: "Voce tem Certeza?",
          text: "Voce irá deletar este item",
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
