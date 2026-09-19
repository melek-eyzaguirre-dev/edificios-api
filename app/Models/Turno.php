<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Turno extends Model
{
    use HasFactory;

    protected $fillable = ['condominio_id', 'user_id', 'tipo', 'sistema', 'inicio', 'fin', 'dias_trabajados', 'dias_libres', 'vigente_desde', 'vigente_hasta', 'notas'];

    protected $casts = ['inicio' => 'datetime', 'fin' => 'datetime', 'dias_trabajados' => 'array', 'dias_libres' => 'array', 'vigente_desde' => 'date', 'vigente_hasta' => 'date'];

    public function condominio(): BelongsTo { return $this->belongsTo(Condominio::class); }
    public function usuario(): BelongsTo { return $this->belongsTo(User::class, 'user_id'); }
    public function novedades(): HasMany { return $this->hasMany(Novedad::class); }
    public function asistencias(): HasMany { return $this->hasMany(Asistencia::class); }
}