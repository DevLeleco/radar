@extends('auth.layout')

@section('title', 'Entrar')

@section('content')
    <h1>Boas-vindas</h1>
    <p class="auth-intro">Entre na sua conta para continuar.</p>

    @if ($errors->any())
        <p class="auth-error" role="alert">Não foi possível entrar com essas credenciais. Confira os dados e tente novamente.</p>
    @endif

    <form method="post" action="{{ route('login') }}">
        @csrf
        <label for="email">E-mail</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">

        <label for="password">Senha</label>
        <input id="password" type="password" name="password" required autocomplete="current-password">

        <div class="remember-row">
            <input id="remember" type="checkbox" name="remember" value="1">
            <label for="remember">Manter conectado</label>
        </div>
        <button type="submit">Entrar no Radar</button>
    </form>
    <a class="auth-link" href="{{ route('password.request') }}">Esqueci minha senha</a>
@endsection
