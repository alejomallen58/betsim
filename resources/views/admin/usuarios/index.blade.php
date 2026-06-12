@extends('layouts.app')
@section('title', 'Admin · Usuarios')

@section('content')
<style>
    .saldo-form      { display: flex; gap: 6px; align-items: center; }
    .saldo-input     { width: 90px !important; padding: 5px 8px !important; font-size: .82rem !important; }
    .btn-sumar       { background: #16a34a; color: #fff; border: none; border-radius: 6px;
                       padding: 5px 10px; font-size: .8rem; font-weight: 600; cursor: pointer; white-space: nowrap; }
    .btn-sumar:hover { background: #15803d; }
    .btn-restar      { background: #dc2626; color: #fff; border: none; border-radius: 6px;
                       padding: 5px 10px; font-size: .8rem; font-weight: 600; cursor: pointer; white-space: nowrap; }
    .btn-restar:hover { background: #b91c1c; }
</style>

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;flex-wrap:wrap;gap:12px">
    <div>
        <div style="font-size:.75rem;color:#facc15;font-weight:600;margin-bottom:4px">PANEL DE ADMINISTRACIÓN</div>
        <h1 style="font-size:1.5rem;font-weight:700;color:#fff;margin:0">Gestión de Usuarios</h1>
    </div>
    <a href="{{ route('admin.partidos.index') }}" class="btn-primary" style="font-size:.875rem">← Partidos</a>
</div>

<div class="card" style="overflow:hidden">
    <table>
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Email</th>
                <th>Saldo actual</th>
                <th>Apuestas</th>
                <th>Registro</th>
                <th>Ajustar saldo</th>
            </tr>
        </thead>
        <tbody>
            @foreach($usuarios as $usuario)
            <tr>
                <td style="font-weight:600;color:#fff">{{ $usuario->name }}</td>
                <td style="color:#94a3b8;font-size:.875rem">{{ $usuario->email }}</td>
                <td>
                    <span style="color:#4ade80;font-weight:700">{{ number_format($usuario->saldo, 2, ',', '.') }} €</span>
                </td>
                <td style="color:#94a3b8;font-size:.875rem;text-align:center">{{ $usuario->apuestas_count }}</td>
                <td style="color:#64748b;font-size:.8rem">{{ $usuario->created_at->format('d/m/Y') }}</td>
                <td>
                    <form method="POST" action="{{ route('admin.usuarios.saldo', $usuario) }}" class="saldo-form">
                        @csrf
                        <input type="hidden" name="operacion" id="op_{{ $usuario->id }}" value="sumar">
                        <input type="number" name="cantidad" class="saldo-input"
                               min="0.01" step="0.01" placeholder="€" required>
                        <button type="submit" class="btn-sumar"
                                onclick="document.getElementById('op_{{ $usuario->id }}').value='sumar'">
                            + Sumar
                        </button>
                        <button type="submit" class="btn-restar"
                                onclick="document.getElementById('op_{{ $usuario->id }}').value='restar'">
                            − Restar
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div style="padding:16px">{{ $usuarios->links() }}</div>
</div>
@endsection
