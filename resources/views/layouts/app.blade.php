<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Apuestas Deportivas') }} - @yield('title', 'Inicio')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { background-color: #0f172a; color: #e2e8f0; font-family: 'Segoe UI', sans-serif; }
        .navbar { background: linear-gradient(135deg, #1e293b, #0f172a); border-bottom: 1px solid #334155; display: flex; align-items: center; justify-content: space-between; padding: 12px 24px; }
        .navbar a { color: #94a3b8; transition: color .2s; text-decoration: none; }
        .navbar a:hover { color: #38bdf8; }
        .nav-left { display: flex; align-items: center; gap: 28px; }
        .nav-right { display: flex; align-items: center; gap: 20px; }
        .nav-logo { font-size: 1.2rem; font-weight: 700; color: #fff !important; white-space: nowrap; }
        .nav-link { font-size: .875rem; white-space: nowrap; }
        .card { background: #1e293b; border: 1px solid #334155; border-radius: 12px; }
        .btn-primary { background: #2563eb; color: #fff; border-radius: 8px; padding: 8px 20px; font-weight: 600; }
        .btn-primary:hover { background: #1d4ed8; }
        .btn-success { background: #16a34a; color: #fff; border-radius: 8px; padding: 8px 20px; font-weight: 600; }
        .btn-success:hover { background: #15803d; }
        .btn-danger { background: #dc2626; color: #fff; border-radius: 8px; padding: 8px 20px; font-weight: 600; }
        .badge-pendiente  { background: #334155; color: #94a3b8; }
        .badge-finalizado { background: #14532d; color: #4ade80; }
        .badge-en_juego   { background: #78350f; color: #fbbf24; }
        .badge { display:inline-block; padding: 2px 10px; border-radius: 99px; font-size: .75rem; font-weight: 600; }
        .alert-success { background: #14532d; border: 1px solid #16a34a; color: #4ade80; border-radius: 8px; padding: 12px 16px; }
        .alert-error   { background: #450a0a; border: 1px solid #dc2626; color: #f87171; border-radius: 8px; padding: 12px 16px; }
        .cuota-box { background: #0f172a; border: 1px solid #334155; border-radius: 8px; padding: 12px; text-align: center; cursor: pointer; transition: all .2s; }
        .cuota-box:hover, .cuota-box.selected { border-color: #2563eb; background: #1e3a5f; }
        .cuota-box .valor { font-size: 1.4rem; font-weight: 700; color: #38bdf8; }
        .cuota-box .label { font-size: .75rem; color: #64748b; margin-top: 2px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #0f172a; color: #64748b; font-size: .75rem; text-transform: uppercase; letter-spacing: .05em; padding: 10px 16px; text-align: left; }
        td { padding: 12px 16px; border-bottom: 1px solid #1e293b; }
        tr:hover td { background: #1e293b44; }
        input, select { background: #0f172a; border: 1px solid #334155; color: #e2e8f0; border-radius: 8px; padding: 8px 12px; width: 100%; }
        input:focus, select:focus { outline: none; border-color: #2563eb; }
        label { color: #94a3b8; font-size: .875rem; margin-bottom: 4px; display: block; }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="nav-left">
        <a href="{{ route('home') }}" class="nav-logo">⚽ Apuestas Deportivas</a>
        <a href="{{ route('partidos.index') }}" class="nav-link">Partidos</a>
        @auth
            <a href="{{ route('apuestas.index') }}" class="nav-link">Mis Apuestas</a>
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.partidos.index') }}" class="nav-link" style="color:#facc15">Panel Admin</a>
            @endif
        @endauth
    </div>
    <div class="nav-right">
        @auth
            <span style="color:#4ade80;font-weight:600;font-size:.875rem">💰 {{ number_format(auth()->user()->saldo, 2, ',', '.') }} €</span>
            <span style="color:#94a3b8;font-size:.875rem">{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}" style="margin:0">
                @csrf
                <button type="submit" style="background:none;border:none;color:#94a3b8;cursor:pointer;font-size:.875rem">Salir</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="nav-link">Iniciar sesión</a>
            <a href="{{ route('register') }}" class="btn-primary" style="font-size:.875rem">Registrarse</a>
        @endauth
    </div>
</nav>

<main style="max-width:1100px;margin:0 auto;padding:32px 16px">
    @if(session('success'))
        <div class="alert-success mb-6">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert-error mb-6">{{ session('error') }}</div>
    @endif

    @yield('content')
</main>

</body>
</html>
