<?php

namespace Database\Seeders;

use App\Models\Equipo;
use App\Models\Liga;
use App\Models\Partido;
use App\Models\Cuota;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PartidoSeeder extends Seeder
{
    public function run(): void
    {
        $laliga  = Liga::where('nombre', 'La Liga')->first();
        $premier = Liga::where('nombre', 'Premier League')->first();
        $serieA  = Liga::where('nombre', 'Serie A')->first();

        // --- La Liga ---
        $madrid    = Equipo::where('nombre', 'Real Madrid')->first();
        $barca     = Equipo::where('nombre', 'FC Barcelona')->first();
        $atletico  = Equipo::where('nombre', 'Atlético de Madrid')->first();
        $sevilla   = Equipo::where('nombre', 'Sevilla FC')->first();
        $betis     = Equipo::where('nombre', 'Real Betis')->first();
        $valencia  = Equipo::where('nombre', 'Valencia CF')->first();

        $this->crearPartido($laliga->id, $madrid->id,   $barca->id,    Carbon::now()->addDays(2),  'Jornada 35', 1.80, 3.60, 4.50);
        $this->crearPartido($laliga->id, $atletico->id, $sevilla->id,  Carbon::now()->addDays(3),  'Jornada 35', 2.10, 3.20, 3.50);
        $this->crearPartido($laliga->id, $betis->id,    $valencia->id, Carbon::now()->addDays(4),  'Jornada 35', 2.40, 3.10, 3.00);

        // Partido finalizado de ejemplo
        $p = $this->crearPartido($laliga->id, $madrid->id, $atletico->id, Carbon::now()->subDays(5), 'Jornada 34', 1.90, 3.50, 4.00);
        $p->update(['estado' => 'finalizado', 'goles_local' => 2, 'goles_visitante' => 1]);

        // --- Premier League ---
        $city    = Equipo::where('nombre', 'Manchester City')->first();
        $arsenal = Equipo::where('nombre', 'Arsenal')->first();
        $liver   = Equipo::where('nombre', 'Liverpool')->first();
        $chelsea = Equipo::where('nombre', 'Chelsea')->first();

        $this->crearPartido($premier->id, $city->id,   $arsenal->id, Carbon::now()->addDays(1),  'Matchday 36', 1.70, 3.80, 5.00);
        $this->crearPartido($premier->id, $liver->id,  $chelsea->id, Carbon::now()->addDays(3),  'Matchday 36', 2.00, 3.30, 3.80);

        // --- Serie A ---
        $juve  = Equipo::where('nombre', 'Juventus')->first();
        $inter = Equipo::where('nombre', 'Inter Milan')->first();
        $milan = Equipo::where('nombre', 'AC Milan')->first();
        $napol = Equipo::where('nombre', 'Napoli')->first();

        $this->crearPartido($serieA->id, $juve->id,  $inter->id, Carbon::now()->addDays(5), 'Giornata 35', 2.20, 3.30, 3.20);
        $this->crearPartido($serieA->id, $milan->id, $napol->id, Carbon::now()->addDays(6), 'Giornata 35', 2.00, 3.40, 3.70);
    }

    private function crearPartido(int $ligaId, int $localId, int $visitanteId, $fecha, string $jornada,
                                   float $cLocal, float $cEmpate, float $cVisitante): Partido
    {
        $partido = Partido::create([
            'liga_id'             => $ligaId,
            'equipo_local_id'     => $localId,
            'equipo_visitante_id' => $visitanteId,
            'fecha'               => $fecha,
            'estado'              => 'pendiente',
            'jornada'             => $jornada,
        ]);

        Cuota::create([
            'partido_id'      => $partido->id,
            'cuota_local'     => $cLocal,
            'cuota_empate'    => $cEmpate,
            'cuota_visitante' => $cVisitante,
        ]);

        return $partido;
    }
}
