<?php

namespace App\Console\Commands;

use App\Models\Partido;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SincronizarResultados extends Command
{
    protected $signature   = 'partidos:resultados';
    protected $description = 'Sincroniza resultados de partidos finalizados desde football-data.org';

    private array $ligas = [
        'PD' => 'La Liga',
        'PL' => 'Premier League',
        'SA' => 'Serie A',
    ];

    public function handle(): void
    {
        $apiKey = env('FOOTBALL_API_KEY');
        $apiUrl = env('FOOTBALL_API_URL');

        if (!$apiKey || $apiKey === 'pon_aqui_tu_token') {
            $this->error('Configura FOOTBALL_API_KEY en el .env.');
            return;
        }

        foreach ($this->ligas as $codigo => $nombreLiga) {
            $this->info("Sincronizando resultados de {$nombreLiga}...");

            // Partidos de los últimos 3 días que ya deberían tener resultado
            $desde = now()->subDays(3)->format('Y-m-d');
            $hasta = now()->format('Y-m-d');

            $response = Http::withHeaders([
                'X-Auth-Token' => $apiKey,
            ])->get("{$apiUrl}/competitions/{$codigo}/matches", [
                'dateFrom' => $desde,
                'dateTo'   => $hasta,
                'status'   => 'FINISHED',
            ]);

            if (!$response->ok()) {
                $this->error("Error API para {$nombreLiga}: " . $response->status());
                continue;
            }

            $partidos = $response->json('matches');

            if (empty($partidos)) {
                $this->warn("Sin resultados nuevos para {$nombreLiga}.");
                continue;
            }

            $actualizados = 0;

            foreach ($partidos as $p) {
                $nombreLocal     = $p['homeTeam']['name'];
                $nombreVisitante = $p['awayTeam']['name'];
                $golesLocal      = $p['score']['fullTime']['home'];
                $golesVisitante  = $p['score']['fullTime']['away'];
                $fecha           = substr($p['utcDate'], 0, 10);

                // Buscar el partido en nuestra BD que siga pendiente
                $partido = Partido::where('estado', 'pendiente')
                    ->whereHas('equipoLocal',     fn($q) => $q->where('nombre', $nombreLocal))
                    ->whereHas('equipoVisitante', fn($q) => $q->where('nombre', $nombreVisitante))
                    ->whereDate('fecha', $fecha)
                    ->first();

                if (!$partido) {
                    continue; // No está en nuestra BD o ya fue procesado
                }

                // Determinar ganador y resolver apuestas
                if ($golesLocal > $golesVisitante) {
                    $ganador = 'local';
                } elseif ($golesLocal < $golesVisitante) {
                    $ganador = 'visitante';
                } else {
                    $ganador = 'empate';
                }

                $partido->update([
                    'goles_local'     => $golesLocal,
                    'goles_visitante' => $golesVisitante,
                    'estado'          => 'finalizado',
                ]);

                foreach ($partido->apuestas()->where('estado', 'pendiente')->get() as $apuesta) {
                    if ($apuesta->tipo === $ganador) {
                        $apuesta->update(['estado' => 'ganada']);
                        $apuesta->user->increment('saldo', $apuesta->ganancia_potencial);
                    } else {
                        $apuesta->update(['estado' => 'perdida']);
                    }
                }

                $actualizados++;
                $this->line("  → {$nombreLocal} {$golesLocal}-{$golesVisitante} {$nombreVisitante}");
            }

            $this->info("  ✓ {$actualizados} partidos actualizados.");
        }

        $this->info('Sincronización completada.');
    }
}
