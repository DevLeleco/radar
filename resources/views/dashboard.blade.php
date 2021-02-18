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
		<h1 class="display-4">Painel</h1>		
		<p class="lead">Cadastre um Equipamento informando qual Unidade ele pertençe.</p>
	</div>
</div>

 <!-- início do card 1 -->
 <div class="card card-default">
   
</div>
<!-- fim do card 1 -->




@stop

