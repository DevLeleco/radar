<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#10283b">
    <title>@yield('title', 'Acesso') | Radar</title>
    @vite('resources/sass/app.scss')
</head>
<body class="auth-page">
    <main class="auth-card">
        <a class="auth-brand text-decoration-none text-reset" href="{{ route('login') }}">
            <span class="brand-mark">R</span>
            <span><strong>Radar</strong><small>Gestão técnica</small></span>
        </a>
        @yield('content')
    </main>
</body>
</html>
