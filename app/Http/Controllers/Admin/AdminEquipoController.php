<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Equipo;
use App\Models\Liga;
use Illuminate\Http\Request;

class AdminEquipoController extends Controller
{
    public function index()
    {
        $equipos = Equipo::with('liga')->orderBy('nombre')->paginate(20);
        return view('admin.equipos.index', compact('equipos'));
    }

    public function create()
    {
        $ligas = Liga::where('activa', true)->orderBy('nombre')->get();
        return view('admin.equipos.create', compact('ligas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'liga_id' => 'required|exists:ligas,id',
            'nombre'  => 'required|string|max:100',
            'ciudad'  => 'nullable|string|max:100',
        ]);

        Equipo::create($request->only('liga_id', 'nombre', 'ciudad'));

        return redirect()->route('admin.equipos.index')
            ->with('success', 'Equipo creado correctamente.');
    }

    public function edit(Equipo $equipo)
    {
        $ligas = Liga::where('activa', true)->orderBy('nombre')->get();
        return view('admin.equipos.edit', compact('equipo', 'ligas'));
    }

    public function update(Request $request, Equipo $equipo)
    {
        $request->validate([
            'liga_id' => 'required|exists:ligas,id',
            'nombre'  => 'required|string|max:100',
            'ciudad'  => 'nullable|string|max:100',
        ]);

        $equipo->update($request->only('liga_id', 'nombre', 'ciudad'));

        return redirect()->route('admin.equipos.index')
            ->with('success', 'Equipo actualizado.');
    }

    public function destroy(Equipo $equipo)
    {
        $equipo->delete();
        return redirect()->route('admin.equipos.index')
            ->with('success', 'Equipo eliminado.');
    }
}
