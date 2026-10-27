@extends('auth.layout')

@section('title', 'Recuperar acesso')

@section('content')
    <h1>Recuperar acesso</h1>
    <p class="auth-intro">Informe o e-mail da sua conta. Enviaremos instruções para criar uma nova senha.</p>

    @if (session('status'))
        <p class="auth-status" role="status">{{ session('status') }}</p>
    @endif
    @if ($errors->any())
        <p class="auth-error" role="alert">{{ $errors->first() }}</p>
    @endif

    <form method="post" action="{{ route('password.email') }}">
        @csrf
        <label for="email">E-mail</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email">
        <button type="submit">Enviar instruções</button>
    </form>
    <a class="auth-link" href="{{ route('login') }}">Voltar para entrar</a>
@endsection
