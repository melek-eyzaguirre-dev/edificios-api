<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EstacionamientoOcupacion extends Model
{
    use HasFactory;

    protected $table = 'estacionamiento_ocupaciones';

    protected $fillable = ['estacionamiento_id', 'visita_id', 'unidad_id', 'inicio', 'fin'];

    protected $casts = [
        'inicio' => 'datetime',
        'fin' => 'datetime',
    ];

    public function estacionamiento(): BelongsTo
    {
        return $this->belongsTo(Estacionamiento::class);
    }

    public function visita(): BelongsTo
    {
        return $this->belongsTo(Visita::class);
    }

    public function unidad(): BelongsTo
    {
        return $this->belongsTo(Unidad::class);
    }
}
