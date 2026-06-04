<?php

use App\Http\Controllers\ApuestaController;
use App\Http\Controllers\PartidoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminPartidoController;
use App\Http\Controllers\Admin\AdminEquipoController;
use App\Http\Controllers\Admin\AdminApiController;
use App\Http\Controllers\Admin\AdminUsuarioController;
use Illuminate\Support\Facades\Route;

// Página principal → listado de partidos
Route::get('/', [PartidoController::class, 'index'])->name('home');

// Partidos (público)
Route::get('/partidos', [PartidoController::class, 'index'])->name('partidos.index');
Route::get('/partidos/{partido}', [PartidoController::class, 'show'])->name('partidos.show');

// Dashboard usuario
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rutas autenticadas
Route::middleware('auth')->group(function () {
    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Apuestas del usuario
    Route::get('/mis-apuestas', [ApuestaController::class, 'index'])->name('apuestas.index');
    Route::post('/partidos/{partido}/apostar', [ApuestaController::class, 'store'])->name('apuestas.store');
});

// Panel de administración
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/', fn() => redirect()->route('admin.partidos.index'))->name('home');

    // Partidos
    Route::get('/partidos', [AdminPartidoController::class, 'index'])->name('partidos.index');
    Route::get('/partidos/crear', [AdminPartidoController::class, 'create'])->name('partidos.create');
    Route::post('/partidos', [AdminPartidoController::class, 'store'])->name('partidos.store');
    Route::get('/partidos/{partido}/editar', [AdminPartidoController::class, 'edit'])->name('partidos.edit');
    Route::put('/partidos/{partido}', [AdminPartidoController::class, 'update'])->name('partidos.update');
    Route::get('/partidos/{partido}/resultado',  [AdminPartidoController::class, 'resultadoForm'])->name('partidos.resultado.form');
    Route::post('/partidos/{partido}/resultado', [AdminPartidoController::class, 'resultado'])->name('partidos.resultado');

    // API externa
    Route::post('/api/importar',    [AdminApiController::class, 'importar'])->name('api.importar');
    Route::post('/api/sincronizar', [AdminApiController::class, 'sincronizar'])->name('api.sincronizar');
    Route::post('/api/nba',         [AdminApiController::class, 'importarNba'])->name('api.nba');

    // Usuarios
    Route::get('/usuarios', [AdminUsuarioController::class, 'index'])->name('usuarios.index');
    Route::post('/usuarios/{usuario}/saldo', [AdminUsuarioController::class, 'ajustarSaldo'])->name('usuarios.saldo');

    // Equipos
    Route::get('/equipos', [AdminEquipoController::class, 'index'])->name('equipos.index');
    Route::get('/equipos/crear', [AdminEquipoController::class, 'create'])->name('equipos.create');
    Route::post('/equipos', [AdminEquipoController::class, 'store'])->name('equipos.store');
    Route::get('/equipos/{equipo}/editar', [AdminEquipoController::class, 'edit'])->name('equipos.edit');
    Route::put('/equipos/{equipo}', [AdminEquipoController::class, 'update'])->name('equipos.update');
    Route::delete('/equipos/{equipo}', [AdminEquipoController::class, 'destroy'])->name('equipos.destroy');
});

require __DIR__.'/auth.php';
