<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background: #0f172a; font-family: 'Figtree', sans-serif; color: #e2e8f0;
               min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .guest-card { background: #1e293b; border: 1px solid #334155; border-radius: 16px;
                      padding: 40px; width: 100%; max-width: 420px; margin: 20px; }
        .guest-logo { text-align: center; margin-bottom: 28px; font-size: 1.5rem;
                      font-weight: 800; color: #fff; letter-spacing: -0.5px; }
        .guest-logo span { color: #3b82f6; }
        label { display: block; font-size: .85rem; color: #94a3b8; margin-bottom: 6px; font-weight: 500; }
        input[type=text], input[type=email], input[type=password] {
            width: 100%; background: #0f172a; border: 1px solid #334155; border-radius: 8px;
            padding: 10px 14px; color: #fff; font-size: .95rem; margin-bottom: 18px; outline: none; }
        input:focus { border-color: #3b82f6; }
        .btn-primary { width: 100%; background: #3b82f6; color: #fff; border: none; border-radius: 8px;
                       padding: 11px; font-size: 1rem; font-weight: 600; cursor: pointer; margin-top: 4px; }
        .btn-primary:hover { background: #2563eb; }
        .guest-footer { text-align: center; margin-top: 20px; font-size: .85rem; color: #64748b; }
        .guest-footer a { color: #3b82f6; text-decoration: none; }
        .error-msg { color: #f87171; font-size: .8rem; margin-top: -14px; margin-bottom: 12px; }
    </style>
</head>
<body>
    <div class="guest-card">
        <div class="guest-logo">🎯 <span>Apuestas</span> Deportivas</div>
        {{ $slot }}
    </div>
</body>
</html>
