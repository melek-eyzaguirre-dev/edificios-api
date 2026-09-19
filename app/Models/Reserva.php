<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reserva extends Model
{
    use HasFactory;

    protected $fillable = [
        'espacio_comun_id', 'unidad_id', 'user_id',
        'inicio', 'fin', 'estado', 'motivo_rechazo',
    ];

    protected $casts = [
        'inicio' => 'datetime',
        'fin' => 'datetime',
    ];

    public function espacioComun(): BelongsTo
    {
        return $this->belongsTo(EspacioComun::class);
    }

    public function unidad(): BelongsTo
    {
        return $this->belongsTo(Unidad::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Verifica si un rango de horario se solapa con reservas activas existentes
     * para el mismo espacio común. Úsalo en el FormRequest antes de guardar.
     */
    public static function seSolapa(int $espacioComunId, string $inicio, string $fin, ?int $ignorarReservaId = null): bool
    {
        return static::where('espacio_comun_id', $espacioComunId)
            ->whereIn('estado', ['pendiente', 'confirmada'])
            ->when($ignorarReservaId, fn ($q) => $q->where('id', '!=', $ignorarReservaId))
            ->where('inicio', '<', $fin)
            ->where('fin', '>', $inicio)
            ->exists();
    }
}
