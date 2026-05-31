<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Partido extends Model
{
    use HasFactory;

    protected $fillable = [
        'liga_id',
        'equipo_local_id',
        'equipo_visitante_id',
        'fecha',
        'estado',
        'goles_local',
        'goles_visitante',
        'jornada',
    ];

    protected $casts = [
        'fecha' => 'datetime',
    ];

    public function liga()
    {
        return $this->belongsTo(Liga::class);
    }

    public function equipoLocal()
    {
        return $this->belongsTo(Equipo::class, 'equipo_local_id');
    }

    public function equipoVisitante()
    {
        return $this->belongsTo(Equipo::class, 'equipo_visitante_id');
    }

    public function cuota()
    {
        return $this->hasOne(Cuota::class);
    }

    public function apuestas()
    {
        return $this->hasMany(Apuesta::class);
    }

}
