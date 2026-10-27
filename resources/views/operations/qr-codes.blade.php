@extends('operations.master.admin')

<style>
  @import url('https://fonts.googleapis.com/css2?family=Open+Sans+Condensed:wght@300&display=swap');
</style>
<style>
  .modal .modal-dialog {width: 30%}
</style>

@section('content')

<script src=" {{ asset('vendor/jquery/jquery.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/qr/qrcode.js') }}"></script>


<div class="jumbotron py-1">
	<div class="container text-center">
		<h1 class="display-4">QR Codes</h1>		
		{{-- <p class="lead">Cadastre uma Ordem de Serviço e tenha um histórico das ocorrências.</p> --}}
	</div>
</div>


<!-- início do card 2 -->
<div class="card card-default" >
    <div class="card-header">
  
      <h3 class="card-title">Chamados Concluidos</h3>
    </div>
    <div class="bs-example card-body" data-example-id="striped-table" >
      <table class="table table-striped table-bordered table-sm" id="myTable">
        <thead>
          <tr>
            <th>Equipamentos</th>
            <th>QR Code</th>
          </tr>
        </thead>
        <tbody>
          
          @if(isset($equipments))
          @php
              $cont = 0;
          @endphp
            @foreach($equipments as $equipment) 
            
              <tr>
                  <td>{{$equipment->equipamento}}</td>
                  <td ><input id="text{{$cont}}" type="hidden" value="{{ route('tickets.from-qr.create', $equipment->id_equipamento) }}" style="width:80%" /><div id="qrcode{{$cont}}" style="width:100px; height:100px;"></div></td>
              </tr>
              @php
                $cont = $cont + 1;
              @endphp
            @endforeach   
            <input type="hidden" value="{{ $cont }}" id="cont_js">
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
            title: 'Unidade Radar|PROMPT'
          }
        ]
        });
    } );
  </script>

  <script type="text/javascript">

  var cont_js = $('#cont_js').val(); // varivel para o for

  for(var i = 0; i < cont_js; i++){
      var qrcode = new QRCode(document.getElementById("qrcode" + i), { // aqui
      width : 100,
      height : 100
    });
    
    var elText = document.getElementById("text" + i);
    qrcode.makeCode(elText.value);
  }
    </script>


@stop
