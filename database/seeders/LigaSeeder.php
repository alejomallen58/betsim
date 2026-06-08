<?php

namespace Database\Seeders;

use App\Models\Liga;
use App\Models\Equipo;
use Illuminate\Database\Seeder;

class LigaSeeder extends Seeder
{
    public function run(): void
    {
        // La Liga
        $laliga = Liga::create(['nombre' => 'La Liga', 'pais' => 'España', 'activa' => true]);
        $equiposLaliga = [
            ['nombre' => 'Real Madrid',      'ciudad' => 'Madrid'],
            ['nombre' => 'FC Barcelona',     'ciudad' => 'Barcelona'],
            ['nombre' => 'Atlético de Madrid','ciudad' => 'Madrid'],
            ['nombre' => 'Sevilla FC',        'ciudad' => 'Sevilla'],
            ['nombre' => 'Real Betis',        'ciudad' => 'Sevilla'],
            ['nombre' => 'Valencia CF',       'ciudad' => 'Valencia'],
            ['nombre' => 'Athletic Club',     'ciudad' => 'Bilbao'],
            ['nombre' => 'Real Sociedad',     'ciudad' => 'San Sebastián'],
        ];
        foreach ($equiposLaliga as $e) {
            Equipo::create(array_merge($e, ['liga_id' => $laliga->id]));
        }

        // Premier League
        $premier = Liga::create(['nombre' => 'Premier League', 'pais' => 'Inglaterra', 'activa' => true]);
        $equiposPremier = [
            ['nombre' => 'Manchester City',   'ciudad' => 'Manchester'],
            ['nombre' => 'Arsenal',           'ciudad' => 'Londres'],
            ['nombre' => 'Liverpool',         'ciudad' => 'Liverpool'],
            ['nombre' => 'Chelsea',           'ciudad' => 'Londres'],
            ['nombre' => 'Manchester United', 'ciudad' => 'Manchester'],
            ['nombre' => 'Tottenham',         'ciudad' => 'Londres'],
        ];
        foreach ($equiposPremier as $e) {
            Equipo::create(array_merge($e, ['liga_id' => $premier->id]));
        }

        // Serie A
        $serieA = Liga::create(['nombre' => 'Serie A', 'pais' => 'Italia', 'activa' => true]);
        $equiposSerieA = [
            ['nombre' => 'Juventus',    'ciudad' => 'Turín'],
            ['nombre' => 'Inter Milan', 'ciudad' => 'Milán'],
            ['nombre' => 'AC Milan',    'ciudad' => 'Milán'],
            ['nombre' => 'AS Roma',     'ciudad' => 'Roma'],
            ['nombre' => 'Napoli',      'ciudad' => 'Nápoles'],
            ['nombre' => 'Lazio',       'ciudad' => 'Roma'],
        ];
        foreach ($equiposSerieA as $e) {
            Equipo::create(array_merge($e, ['liga_id' => $serieA->id]));
        }
    }
}
