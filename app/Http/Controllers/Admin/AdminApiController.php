<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class AdminApiController extends Controller
{
    public function importar()
    {
        if (!auth()->user()->is_admin) abort(403);

        Artisan::call('partidos:importar');
        $output = Artisan::output();

        return redirect()->route('admin.partidos.index')
            ->with('success', 'Importación completada: ' . str_replace("\n", ' | ', trim($output)));
    }

    public function sincronizar()
    {
        if (!auth()->user()->is_admin) abort(403);

        Artisan::call('partidos:resultados');
        $output = Artisan::output();

        return redirect()->route('admin.partidos.index')
            ->with('success', 'Sincronización completada: ' . str_replace("\n", ' | ', trim($output)));
    }

    public function importarNba()
    {
        if (!auth()->user()->is_admin) abort(403);

        Artisan::call('nba:importar');
        $output = Artisan::output();

        return redirect()->route('admin.partidos.index')
            ->with('success', 'NBA importada: ' . str_replace("\n", ' | ', trim($output)));
    }
}
