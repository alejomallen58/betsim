<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Apuesta extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'partido_id',
        'tipo',
        'cantidad',
        'cuota',
        'ganancia_potencial',
        'estado',
    ];

    protected $casts = [
        'cantidad'           => 'decimal:2',
        'cuota'              => 'decimal:2',
        'ganancia_potencial' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function partido()
    {
        return $this->belongsTo(Partido::class);
    }

}
