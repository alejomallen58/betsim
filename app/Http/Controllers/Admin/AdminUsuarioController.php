<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUsuarioController extends Controller
{
    public function index()
    {
        if (!auth()->user()->is_admin) abort(403);

        $usuarios = User::where('is_admin', false)
            ->withCount('apuestas')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function ajustarSaldo(Request $request, User $usuario)
    {
        if (!auth()->user()->is_admin) abort(403);

        $request->validate([
            'cantidad'  => 'required|numeric|min:0.01|max:100000',
            'operacion' => 'required|in:sumar,restar',
        ]);

        if ($request->operacion === 'sumar') {
            $usuario->increment('saldo', $request->cantidad);
            $mensaje = "Se han añadido {$request->cantidad} € a {$usuario->name}.";
        } else {
            if ($usuario->saldo < $request->cantidad) {
                return back()->with('error', "El usuario solo tiene {$usuario->saldo} € disponibles.");
            }
            $usuario->decrement('saldo', $request->cantidad);
            $mensaje = "Se han restado {$request->cantidad} € a {$usuario->name}.";
        }

        return back()->with('success', $mensaje);
    }
}
