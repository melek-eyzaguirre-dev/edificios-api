<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Novedad extends Model
{
    use HasFactory;

    protected $table = 'novedades';

    protected $fillable = ['condominio_id', 'registrado_por', 'turno_id', 'titulo', 'descripcion', 'prioridad', 'estado', 'resuelta_at', 'evidencia_path', 'evidencia_mime'];

    protected $casts = ['resuelta_at' => 'datetime'];

    public function condominio(): BelongsTo { return $this->belongsTo(Condominio::class); }
    public function registradoPor(): BelongsTo { return $this->belongsTo(User::class, 'registrado_por'); }
    public function turno(): BelongsTo { return $this->belongsTo(Turno::class); }
}