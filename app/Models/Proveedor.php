<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Proveedor extends Model
{
    use HasFactory;

    protected $fillable = ['condominio_id', 'razon_social', 'rut', 'contacto', 'telefono', 'email', 'categoria', 'activo'];

    protected $casts = ['activo' => 'boolean'];

    public function condominio(): BelongsTo { return $this->belongsTo(Condominio::class); }
    public function articulos(): HasMany { return $this->hasMany(ArticuloInventario::class); }
}