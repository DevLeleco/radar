<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PROMPT</title>
    

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="http://localhost/radar-new/public/style.css">
   <script src="{{ asset('vendor/jquery/jquery.js') }}"></script>
   
</head>
<body>
  <header>
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
      <a class="navbar-brand" href="#">PROMPT</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item"><a class="nav-link" href="{{ route('radar.home') }}">Home</a></li>
  
          <li class="nav-item"><a class="nav-link" href="{{route('rt.dashboard')}}">Área Restrita</a></li>
  
          <li class="nav-item"><a class="nav-link" href="">Gerar O.S</a></li>
  
          <li class="nav-item dropdown"><a class="nav-link dropdown-toggle"  id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Cadastro</a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="">Unidade</a>
              <a class="dropdown-item" href="">Profissional</a>
              <a class="dropdown-item" href="">Equipamento</a>
            </div>
          </li>
  
          <li class="nav-item dropdown {{ (Route::current()->getName() === 'admin.consulta_os' ? 'active' : '')}} "><a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Consulta</a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="">O.S</a>
              <a class="dropdown-item" href="">Q.R</a>
            </div>
          </li>
        </ul> 
        <ul class="navbar-nav navbar-right">
          <li class="nav-item"><a class="nav-link" href=""><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
        </ul>

      </div>
    </nav>
  </header>

<main role="main">

    @yield('content')


  <!-- FOOTER -->
  <footer class="container">
    <p class="float-right"><a href="#">Back to top</a></p>
    <p>&copy; {{date('Y')}} Company, Inc. &middot; <a href="#">Privacy</a> &middot; <a href="#">Terms</a></p>
  </footer>
</main>



  
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="{{ asset('vendor/jquery/jquery.form.min.js') }}"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  
  <script>$('#flash-overlay-modal').modal();</script>
  <script>$('div.alert').not('.alert-important').delay(5000).fadeOut(350);</script>  

</body>
</html>