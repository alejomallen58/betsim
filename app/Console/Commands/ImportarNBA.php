<?php

namespace App\Console\Commands;

use App\Models\Cuota;
use App\Models\Equipo;
use App\Models\Liga;
use App\Models\Partido;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class ImportarNBA extends Command
{
    protected $signature   = 'nba:importar';
    protected $description = 'Importa partidos de playoffs NBA desde TheSportsDB (sin API key)';

    // IDs de ligas en TheSportsDB
    private array $ligas = [
        '4387' => 'NBA Playoffs 2026',
    ];

    public function handle(): void
    {
        foreach ($this->ligas as $ligaId => $nombreLiga) {
            $this->info("Importando {$nombreLiga}...");

            // Próximos 15 partidos de la liga
            $response = Http::get("https://www.thesportsdb.com/api/v1/json/3/eventsnextleague.php", [
                'id' => $ligaId,
            ]);

            if (!$response->ok()) {
                $this->error("Error al conectar con TheSportsDB: " . $response->status());
                continue;
            }

            $eventos = $response->json('events');

            if (empty($eventos)) {
                $this->warn("No hay partidos próximos para {$nombreLiga}.");
                continue;
            }

            $liga = Liga::firstOrCreate(
                ['nombre' => $nombreLiga],
                ['pais' => 'Estados Unidos', 'activa' => true]
            );

            $creados  = 0;
            $omitidos = 0;

            foreach ($eventos as $evento) {
                $nombreLocal     = $evento['strHomeTeam'];
                $nombreVisitante = $evento['strAwayTeam'];
                $fechaStr        = $evento['dateEvent'] ?? null;   // "2026-06-01"
                $horaStr         = $evento['strTime']   ?? '00:00:00'; // "02:30:00"
                $ronda           = $evento['strRound']  ?? $evento['intRound'] ?? '?';

                if (!$fechaStr) {
                    $omitidos++;
                    continue;
                }

                $fecha = Carbon::parse("{$fechaStr} {$horaStr}");

                // Solo importar partidos futuros
                if ($fecha->isPast()) {
                    $omitidos++;
                    continue;
                }

                $local     = Equipo::firstOrCreate(
                    ['nombre' => $nombreLocal],
                    ['liga_id' => $liga->id]
                );
                $visitante = Equipo::firstOrCreate(
                    ['nombre' => $nombreVisitante],
                    ['liga_id' => $liga->id]
                );

                $yaExiste = Partido::where('equipo_local_id', $local->id)
                    ->where('equipo_visitante_id', $visitante->id)
                    ->whereDate('fecha', $fechaStr)
                    ->exists();

                if ($yaExiste) {
                    $omitidos++;
                    continue;
                }

                $partido = Partido::create([
                    'liga_id'             => $liga->id,
                    'equipo_local_id'     => $local->id,
                    'equipo_visitante_id' => $visitante->id,
                    'fecha'               => $fecha,
                    'estado'              => 'pendiente',
                    'jornada'             => 'Ronda ' . $ronda,
                ]);

                // NBA: sin empate, cuota victoria local vs visitante
                Cuota::create([
                    'partido_id'      => $partido->id,
                    'cuota_local'     => 1.85,
                    'cuota_empate'    => 99.00, // imposible en NBA, cuota disuasoria
                    'cuota_visitante' => 1.95,
                ]);

                $creados++;
                $this->line("  + {$nombreLocal} vs {$nombreVisitante} ({$fecha->format('d/m/Y H:i')})");
            }

            $this->info("  ✓ {$creados} partidos importados, {$omitidos} omitidos.");
        }

        $this->info('Importación NBA completada.');
    }
}
