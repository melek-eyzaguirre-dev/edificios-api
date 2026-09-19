<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unidad extends Model
{
    use HasFactory;

    protected $table = 'unidades';

    protected $fillable = ['condominio_id', 'numero', 'torre', 'prorrateo'];

    public function condominio(): BelongsTo
    {
        return $this->belongsTo(Condominio::class);
    }

    public function residentes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'unidad_user')
            ->withPivot('tipo')
            ->withTimestamps();
    }

    public function visitas(): HasMany
    {
        return $this->hasMany(Visita::class);
    }

    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class);
    }

    public function estacionamientoFijo(): HasMany
    {
        return $this->hasMany(Estacionamiento::class);
    }
}
