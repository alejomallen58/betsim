<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acceso restringido – Apuestas Deportivas</title>
    <style>
        body { background-color: #0f172a; color: #e2e8f0; font-family: 'Segoe UI', sans-serif;
               display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; }
        .error-box { background: #1e293b; border: 1px solid #334155; border-radius: 16px;
                     padding: 48px 40px; text-align: center; max-width: 420px; width: 90%; }
        .error-code { font-size: 5rem; font-weight: 800; color: #dc2626; line-height: 1; }
        .error-title { font-size: 1.25rem; font-weight: 700; color: #fff; margin: 12px 0 8px; }
        .error-msg { color: #94a3b8; font-size: .9rem; margin-bottom: 28px; }
        .btn-back { display: inline-block; background: #2563eb; color: #fff; border-radius: 8px;
                    padding: 10px 24px; font-weight: 600; text-decoration: none; transition: background .2s; }
        .btn-back:hover { background: #1d4ed8; }
    </style>
</head>
<body>
    <div class="error-box">
        <div class="error-code">403</div>
        <div class="error-title">Acceso restringido</div>
        <div class="error-msg">No tienes permisos para acceder a esta sección.</div>
        <a href="{{ url('/') }}" class="btn-back">← Volver al inicio</a>
    </div>
</body>
</html>
