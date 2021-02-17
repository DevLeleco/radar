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
		<h1 class="display-4">Consulta QR CODE</h1>		
		<p class="lead">Cadastre um Equipamento informando qual Unidade ele pertençe.</p>
	</div>
</div>

 <!-- início do card 1 -->
 <div class="card card-default">
    <form method="post"  action="{{route('rt.view_qr')}}" >
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                      <div>
                        <label for="exampleInputPassword1">Cliente</label>
                        <select class="form-control select" id="cliente" name="clite" required>
                          <option selected="selected"></option>
                          @if(isset($clientes))
                            @foreach($clientes as $cliente) 
                              <option value="{{$cliente->id_cliente}}">{{$cliente->cliente}}</option>
                            @endforeach   
                          @endif
                        </select>
                      </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                      <div>
                        <label for="exampleInputPassword1">Unidade</label>
                        <select class="form-control select" id="id_unidade" required name="id_unidade"></select> 
                              </option>
                      </div>
                    </div>
                </div>               
            </div>
            <button type="submit" class="btn btn-primary" value="Salvar">Consultar</button>  
        </div>
    </form>
</div>
<!-- fim do card 1 -->


<script>
  $(function(){
      $('#cliente').change(function(){
         $("#id_unidade option").remove();
         var id = $(this).val(); //não pega o valor
       
         $.ajax({
            url : '{{ route( 'rt.select_unidade' ) }}',
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
            },
            error: function()
           {
               //handle errors
               alert('error...');
           }
         });
      });
  });
</script>

@stop
