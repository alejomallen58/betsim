<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cuota extends Model
{
    use HasFactory;

    protected $fillable = [
        'partido_id',
        'cuota_local',
        'cuota_empate',
        'cuota_visitante',
    ];

    protected $casts = [
        'cuota_local'     => 'decimal:2',
        'cuota_empate'    => 'decimal:2',
        'cuota_visitante' => 'decimal:2',
    ];

    public function partido()
    {
        return $this->belongsTo(Partido::class);
    }

}
