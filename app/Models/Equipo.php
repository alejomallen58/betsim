<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Equipo extends Model
{
    use HasFactory;

    protected $fillable = ['liga_id', 'nombre', 'escudo', 'ciudad'];

    public function liga()
    {
        return $this->belongsTo(Liga::class);
    }

    public function partidosComoLocal()
    {
        return $this->hasMany(Partido::class, 'equipo_local_id');
    }

    public function partidosComoVisitante()
    {
        return $this->hasMany(Partido::class, 'equipo_visitante_id');
    }
}
