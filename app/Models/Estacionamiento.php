<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Estacionamiento extends Model
{
    use HasFactory;

    protected $fillable = ['condominio_id', 'codigo', 'tipo', 'unidad_id', 'disponible', 'moneda', 'tarifa_hora', 'max_horas_visita'];
    protected $casts = ['tarifa_hora' => 'decimal:2'];

    public function condominio(): BelongsTo
    {
        return $this->belongsTo(Condominio::class);
    }

    public function unidad(): BelongsTo
    {
        return $this->belongsTo(Unidad::class);
    }

    public function ocupaciones(): HasMany
    {
        return $this->hasMany(EstacionamientoOcupacion::class);
    }
}
