@extends('layouts.app')
@section('title', 'Admin · Equipos')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <div class="text-xs text-yellow-400 font-semibold mb-1">PANEL DE ADMINISTRACIÓN</div>
        <h1 class="text-2xl font-bold text-white">Gestión de Equipos</h1>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.partidos.index') }}" class="btn-primary text-sm">Partidos</a>
        <a href="{{ route('admin.equipos.create') }}" class="btn-success text-sm">+ Nuevo equipo</a>
    </div>
</div>

<div class="card overflow-hidden">
    <table>
        <thead>
            <tr>
                <th>Equipo</th>
                <th>Liga</th>
                <th>Ciudad</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($equipos as $equipo)
            <tr>
                <td class="font-semibold text-white">{{ $equipo->nombre }}</td>
                <td class="text-slate-300 text-sm">{{ $equipo->liga->nombre }}</td>
                <td class="text-slate-400 text-sm">{{ $equipo->ciudad ?? '—' }}</td>
                <td>
                    <div class="flex gap-3">
                        <a href="{{ route('admin.equipos.edit', $equipo) }}" class="text-sky-400 hover:text-sky-300 text-sm">Editar</a>
                        <form method="POST" action="{{ route('admin.equipos.destroy', $equipo) }}"
                              onsubmit="return confirm('¿Eliminar este equipo?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-300 text-sm">Eliminar</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="p-4">{{ $equipos->links() }}</div>
</div>
@endsection
