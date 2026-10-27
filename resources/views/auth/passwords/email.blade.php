<!doctype html>
<html lang="pt-BR">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Redefinir senha | Radar</title></head>
<body style="font-family:sans-serif;max-width:26rem;margin:3rem auto;padding:0 1rem">
    <h1>Redefinir senha</h1>
    @if (session('status')) <p role="status">{{ session('status') }}</p> @endif
    @if ($errors->any()) <p role="alert">{{ $errors->first() }}</p> @endif
    <form method="post" action="{{ route('password.email') }}">
        @csrf
        <label for="email">E-mail</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email">
        <button type="submit">Enviar link de redefinição</button>
    </form>
    <p><a href="{{ route('login') }}">Voltar ao login</a></p>
</body>
</html>
