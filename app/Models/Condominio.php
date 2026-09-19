<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Condominio extends Model
{
    use HasFactory;

    protected $fillable = ['administradora_id', 'nombre', 'direccion', 'comuna', 'ciudad', 'logo_path', 'activo'];

    public function administradora(): BelongsTo
    {
        return $this->belongsTo(Administradora::class);
    }

    public function unidades(): HasMany
    {
        return $this->hasMany(Unidad::class);
    }

    public function espaciosComunes(): HasMany
    {
        return $this->hasMany(EspacioComun::class);
    }

    public function estacionamientos(): HasMany
    {
        return $this->hasMany(Estacionamiento::class);
    }

    // Conserjes y administradores asignados a este condominio
    public function staff(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'condominio_user');
    }
}
