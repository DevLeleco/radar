<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#10283b">
    <title>Radar | Gestão técnica</title>
    @vite('resources/sass/app.scss')
</head>
<body class="public-page">
    <nav class="public-nav" aria-label="Navegação principal">
        <a class="public-brand" href="{{ route('home') }}">
            <span class="brand-mark">R</span>
            <span>Radar</span>
        </a>
        <a class="btn btn-primary" href="{{ Auth::user()->perfil == 3 ? route('admin.tickets.queue') : route('tickets.index') }}">
            Acessar chamados
        </a>
    </nav>

    <main>
        <section class="public-hero">
            <span class="eyebrow">Operação em um só lugar</span>
            <h1>Olá, {{ Auth::user()->name }}.</h1>
            <p>Organize atendimentos, acompanhe equipamentos e mantenha cada etapa do serviço visível para sua equipe.</p>
            <a class="btn btn-primary" href="{{ Auth::user()->perfil == 3 ? route('admin.tickets.queue') : route('tickets.index') }}">
                {{ Auth::user()->perfil == 3 ? 'Abrir fila técnica' : 'Ver chamados' }}
            </a>
        </section>

        <section class="public-features" aria-label="Áreas do Radar">
            <article class="public-feature">
                <span class="feature-icon" aria-hidden="true">01</span>
                <h2>Chamados organizados</h2>
                <p>Registre ocorrências e acompanhe o andamento do atendimento em cada unidade.</p>
            </article>
            <article class="public-feature">
                <span class="feature-icon" aria-hidden="true">02</span>
                <h2>Ativos conectados</h2>
                <p>Consulte a estrutura de clientes, unidades e equipamentos em um só fluxo.</p>
            </article>
            <article class="public-feature">
                <span class="feature-icon" aria-hidden="true">03</span>
                <h2>Atendimento em campo</h2>
                <p>Use a fila técnica e os QR codes para chegar mais rápido ao equipamento certo.</p>
            </article>
        </section>
    </main>

    <footer class="public-footer">© {{ date('Y') }} Radar · Gestão técnica</footer>
</body>
</html>
