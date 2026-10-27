<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PROMPT</title>
    

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('style.css') }}">
   <script src="{{ asset('vendor/jquery/jquery.js') }}"></script>
   
   
</head>
<body id="top">
  <header>
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
      <a class="navbar-brand" href="{{ route('home') }}">Radar</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
  
          <li class="nav-item"><a class="nav-link" href="{{ Auth::check() ? (Auth::user()->perfil == 2 ? route('tickets.index') : (Auth::user()->perfil == 3 ? route('admin.tickets.queue') : route('admin.dashboard'))) : route('login') }}">Área restrita</a></li>
          @if (Auth::check())
            @if (in_array((int) Auth::user()->perfil, [1, 2], true))
              <li class="nav-item"><a class="nav-link" href="{{ route('tickets.index') }}">Chamados</a></li>
              <li class="nav-item"><a class="nav-link" href="{{ route('qr-codes.index') }}">QR code</a></li>
            @endif
            @if (Auth::user()->perfil == 3)
              <li class="nav-item"><a class="nav-link" href="{{ route('admin.tickets.queue') }}">Fila técnica</a></li>
            @endif
            @if (Auth::user()->perfil == 1)
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="cadastroDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Cadastros</a>
                <div class="dropdown-menu" aria-labelledby="cadastroDropdown">
                  <a class="dropdown-item" href="{{ route('admin.clients.index') }}">Clientes</a>
                  <a class="dropdown-item" href="{{ route('admin.units.index') }}">Unidades</a>
                  <a class="dropdown-item" href="{{ route('admin.equipment.index') }}">Equipamentos</a>
                  <a class="dropdown-item" href="{{ route('admin.users.index') }}">Usuários</a>
                  <a class="dropdown-item" href="{{ route('admin.technicians.index') }}">Técnicos</a>
                </div>
              </li>
              <li class="nav-item"><a class="nav-link" href="{{ route('admin.work-orders.index') }}">Ordens de serviço</a></li>
              <li class="nav-item"><a class="nav-link" href="{{ route('admin.tickets.queue') }}">Fila técnica</a></li>
            @endif
          @endif
        </ul> 
        <ul class="navbar-nav navbar-right">
          @if (Auth::check())
            <li class="nav-item">
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="nav-link btn btn-link" type="submit">Sair</button>
              </form>
            </li>
          @else
            <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
          @endif
        </ul>

      </div>
    </nav>
  </header>

<main role="main">

    @yield('content')


  <!-- FOOTER -->
  <footer class="container">
    <p class="float-right"><a href="#top">Voltar ao topo</a></p>
    <p>&copy; {{ date('Y') }} Radar</p>
  </footer>
</main>



  
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="{{ asset('vendor/jquery/jquery.form.min.js') }}"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  
  <script>$('#flash-overlay-modal').modal();</script>
  <script>$('div.alert').not('.alert-important').delay(5000).fadeOut(350);</script>  

</body>
</html>
