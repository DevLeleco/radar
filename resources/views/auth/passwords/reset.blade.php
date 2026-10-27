<!doctype html>
<html lang="pt-BR">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Nova senha | Radar</title></head>
<body style="font-family:sans-serif;max-width:26rem;margin:3rem auto;padding:0 1rem">
    <h1>Definir nova senha</h1>
    @if ($errors->any()) <p role="alert">{{ $errors->first() }}</p> @endif
    <form method="post" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <label for="email">E-mail</label>
        <input id="email" type="email" name="email" value="{{ old('email', $email) }}" required autocomplete="email">
        <label for="password">Nova senha</label>
        <input id="password" type="password" name="password" required autocomplete="new-password">
        <label for="password_confirmation">Confirme a nova senha</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
        <button type="submit">Redefinir senha</button>
    </form>
</body>
</html>
