<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Visita extends Model
{
    use HasFactory;

    protected $fillable = [
        'unidad_id', 'autorizado_por', 'registrado_por',
        'nombre_visitante', 'rut_visitante', 'foto_path', 'codigo_qr',
        'autorizado_desde', 'autorizado_hasta', 'hora_ingreso', 'hora_salida', 'estado',
        'motivo_rechazo', 'reportada_por',
    ];

    protected $casts = [
        'autorizado_desde' => 'datetime',
        'autorizado_hasta' => 'datetime',
        'hora_ingreso' => 'datetime',
        'hora_salida' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Visita $visita) {
            $visita->codigo_qr ??= (string) Str::uuid();
        });
    }

    public function unidad(): BelongsTo
    {
        return $this->belongsTo(Unidad::class);
    }

    public function autorizadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'autorizado_por');
    }

    public function registradoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registrado_por');
    }

    public function reportadaPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reportada_por');
    }
}
