@extends('auth.layout')

@section('title', 'Nova senha')

@section('content')
    <h1>Crie uma nova senha</h1>
    <p class="auth-intro">Escolha uma senha segura para voltar ao Radar.</p>

    @if ($errors->any())
        <p class="auth-error" role="alert">{{ $errors->first() }}</p>
    @endif

    <form method="post" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <label for="email">E-mail</label>
        <input id="email" type="email" name="email" value="{{ old('email', $email) }}" required autocomplete="email">
        <label for="password">Nova senha</label>
        <input id="password" type="password" name="password" required autocomplete="new-password">
        <label for="password_confirmation">Confirme a nova senha</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
        <button type="submit">Salvar nova senha</button>
    </form>
    <a class="auth-link" href="{{ route('login') }}">Voltar para entrar</a>
@endsection
