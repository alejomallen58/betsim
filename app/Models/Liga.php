<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Liga extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'pais', 'logo', 'activa'];

    protected $casts = [
        'activa' => 'boolean',
    ];

    public function equipos()
    {
        return $this->hasMany(Equipo::class);
    }

    public function partidos()
    {
        return $this->hasMany(Partido::class);
    }
}
