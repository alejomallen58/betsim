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
        $resumen = str_replace("\n", ' | ', trim($output));

        $hayError = str_contains($output, 'Error') || str_contains($output, 'Configura');

        return redirect()->route('admin.partidos.index')
            ->with($hayError ? 'error' : 'success',
                   ($hayError ? 'La importación ha fallado: ' : 'Importación completada: ') . $resumen);
    }

    public function sincronizar()
    {
        if (!auth()->user()->is_admin) abort(403);

        Artisan::call('partidos:resultados');
        $output = Artisan::output();
        $resumen = str_replace("\n", ' | ', trim($output));

        $hayError = str_contains($output, 'Error');

        return redirect()->route('admin.partidos.index')
            ->with($hayError ? 'error' : 'success',
                   ($hayError ? 'La sincronización ha fallado: ' : 'Sincronización completada: ') . $resumen);
    }

    public function importarNba()
    {
        if (!auth()->user()->is_admin) abort(403);

        Artisan::call('nba:importar');
        $output = Artisan::output();
        $resumen = str_replace("\n", ' | ', trim($output));

        $hayError = str_contains($output, 'Error');

        return redirect()->route('admin.partidos.index')
            ->with($hayError ? 'error' : 'success',
                   ($hayError ? 'La importación NBA ha fallado: ' : 'NBA importada: ') . $resumen);
    }
}
