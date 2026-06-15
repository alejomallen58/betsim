<?php

namespace Database\Seeders;

use App\Models\Liga;
use App\Models\Equipo;
use Illuminate\Database\Seeder;

class LigaSeeder extends Seeder
{
    public function run(): void
    {
        $ligas = [
            ['nombre' => 'La Liga',       'pais' => 'España',    'equipos' => [
                ['nombre' => 'Real Madrid',       'ciudad' => 'Madrid'],
                ['nombre' => 'FC Barcelona',      'ciudad' => 'Barcelona'],
                ['nombre' => 'Atlético de Madrid','ciudad' => 'Madrid'],
                ['nombre' => 'Sevilla FC',        'ciudad' => 'Sevilla'],
                ['nombre' => 'Real Betis',        'ciudad' => 'Sevilla'],
                ['nombre' => 'Valencia CF',       'ciudad' => 'Valencia'],
                ['nombre' => 'Athletic Club',     'ciudad' => 'Bilbao'],
                ['nombre' => 'Real Sociedad',     'ciudad' => 'San Sebastián'],
            ]],
            ['nombre' => 'Premier League','pais' => 'Inglaterra','equipos' => [
                ['nombre' => 'Manchester City',   'ciudad' => 'Manchester'],
                ['nombre' => 'Arsenal',           'ciudad' => 'Londres'],
                ['nombre' => 'Liverpool',         'ciudad' => 'Liverpool'],
                ['nombre' => 'Chelsea',           'ciudad' => 'Londres'],
                ['nombre' => 'Manchester United', 'ciudad' => 'Manchester'],
                ['nombre' => 'Tottenham',         'ciudad' => 'Londres'],
            ]],
            ['nombre' => 'Serie A',       'pais' => 'Italia',    'equipos' => [
                ['nombre' => 'Juventus',    'ciudad' => 'Turín'],
                ['nombre' => 'Inter Milan', 'ciudad' => 'Milán'],
                ['nombre' => 'AC Milan',    'ciudad' => 'Milán'],
                ['nombre' => 'AS Roma',     'ciudad' => 'Roma'],
                ['nombre' => 'Napoli',      'ciudad' => 'Nápoles'],
                ['nombre' => 'Lazio',       'ciudad' => 'Roma'],
            ]],
        ];

        foreach ($ligas as $ligaData) {
            $equipos = $ligaData['equipos'];
            unset($ligaData['equipos']);

            $liga = Liga::firstOrCreate(
                ['nombre' => $ligaData['nombre']],
                ['pais' => $ligaData['pais'], 'activa' => true]
            );

            foreach ($equipos as $e) {
                Equipo::firstOrCreate(
                    ['nombre' => $e['nombre']],
                    ['ciudad' => $e['ciudad'], 'liga_id' => $liga->id]
                );
            }
        }
    }
}
