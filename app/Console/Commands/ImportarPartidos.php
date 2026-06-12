<?php

namespace App\Console\Commands;

use App\Models\Cuota;
use App\Models\Equipo;
use App\Models\Liga;
use App\Models\Partido;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ImportarPartidos extends Command
{
    protected $signature   = 'partidos:importar';
    protected $description = 'Importa partidos reales desde football-data.org';

    // Competiciones de football-data.org: código API => nombre en nuestra BD
    private array $ligas = [
        'WC'  => 'Mundial 2026',
        'PD'  => 'La Liga',
        'PL'  => 'Premier League',
        'SA'  => 'Serie A',
        'CL'  => 'Champions League',
        'BL1' => 'Bundesliga',
    ];

    public function handle(): void
    {
        $apiKey = env('FOOTBALL_API_KEY');
        $apiUrl = env('FOOTBALL_API_URL');

        if (!$apiKey) {
            $this->error('Configura FOOTBALL_API_KEY en el .env antes de importar.');
            return;
        }

        foreach ($this->ligas as $codigo => $nombreLiga) {
            $this->info("Importando {$nombreLiga}...");

            // Pedimos los partidos de los próximos 30 días
            $hoy    = now()->format('Y-m-d');
            $fin    = now()->addDays(30)->format('Y-m-d');

            $response = Http::withHeaders([
                'X-Auth-Token' => $apiKey,
            ])->get("{$apiUrl}/competitions/{$codigo}/matches", [
                'dateFrom' => $hoy,
                'dateTo'   => $fin,
                'status'   => 'SCHEDULED',
            ]);

            if (!$response->ok()) {
                $this->error("Error al conectar con la API para {$nombreLiga}: " . $response->status());
                continue;
            }

            $partidos = $response->json('matches');

            if (empty($partidos)) {
                $this->warn("No hay partidos próximos para {$nombreLiga}.");
                continue;
            }

            // Buscar o crear la liga en nuestra BD
            $liga = Liga::firstOrCreate(
                ['nombre' => $nombreLiga],
                ['pais' => $this->getPaisDeLiga($codigo), 'activa' => true]
            );

            $creados   = 0;
            $omitidos  = 0;

            foreach ($partidos as $p) {
                $nombreLocal     = $p['homeTeam']['name'] ?? '';
                $nombreVisitante = $p['awayTeam']['name'] ?? '';
                $fecha           = $p['utcDate'] ?? null;

                // Descartar partidos con datos incompletos
                if (empty($nombreLocal) || empty($nombreVisitante) || empty($fecha)) {
                    $this->warn("  Partido omitido por datos incompletos (equipo o fecha vacíos).");
                    $omitidos++;
                    continue;
                }

                $jornada = 'Jornada ' . ($p['matchday'] ?? '?');

                // Buscar o crear equipos
                $local     = Equipo::firstOrCreate(
                    ['nombre' => $nombreLocal],
                    ['liga_id' => $liga->id]
                );
                $visitante = Equipo::firstOrCreate(
                    ['nombre' => $nombreVisitante],
                    ['liga_id' => $liga->id]
                );

                // Evitar duplicados: mismo local, visitante y fecha (solo la parte de día)
                $yaExiste = Partido::where('equipo_local_id', $local->id)
                    ->where('equipo_visitante_id', $visitante->id)
                    ->whereDate('fecha', substr($fecha, 0, 10))
                    ->exists();

                if ($yaExiste) {
                    $omitidos++;
                    continue;
                }

                // Crear partido con cuotas por defecto (se pueden editar desde admin)
                $partido = Partido::create([
                    'liga_id'             => $liga->id,
                    'equipo_local_id'     => $local->id,
                    'equipo_visitante_id' => $visitante->id,
                    'fecha'               => $fecha,
                    'estado'              => 'pendiente',
                    'jornada'             => $jornada,
                ]);

                Cuota::create([
                    'partido_id'      => $partido->id,
                    'cuota_local'     => 2.00,
                    'cuota_empate'    => 3.20,
                    'cuota_visitante' => 3.50,
                ]);

                $creados++;
            }

            $this->info("  ✓ {$creados} partidos importados, {$omitidos} ya existían.");
        }

        $this->info('Importación completada.');
    }

    private function getPaisDeLiga(string $codigo): string
    {
        return match($codigo) {
            'WC'  => 'Internacional',
            'PD'  => 'España',
            'PL'  => 'Inglaterra',
            'SA'  => 'Italia',
            'CL'  => 'Europa',
            'BL1' => 'Alemania',
            default => 'Desconocido',
        };
    }
}
