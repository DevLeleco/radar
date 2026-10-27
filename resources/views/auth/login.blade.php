<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Entrar | Radar</title>
    <style>
        body { font-family: sans-serif; margin: 3rem auto; max-width: 26rem; padding: 0 1rem; }
        label { display: block; margin: 1rem 0 .35rem; }
        input { box-sizing: border-box; padding: .6rem; width: 100%; }
        button { margin-top: 1rem; padding: .6rem 1rem; }
        .error { color: #a00; }
    </style>
</head>
<body>
    <h1>Entrar no Radar</h1>

    @if ($errors->any())
        <p class="error" role="alert">Não foi possível entrar com essas credenciais.</p>
    @endif

    <form method="post" action="{{ route('login') }}">
        @csrf
        <label for="email">E-mail</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">

        <label for="password">Senha</label>
        <input id="password" type="password" name="password" required autocomplete="current-password">

        <label><input type="checkbox" name="remember" value="1"> Manter conectado</label>
        <button type="submit">Entrar</button>
    </form>
    <p><a href="{{ route('password.request') }}">Esqueci minha senha</a></p>
</body>
</html>
