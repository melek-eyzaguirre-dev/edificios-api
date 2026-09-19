<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EspacioComun extends Model
{
    use HasFactory;

    protected $table = 'espacios_comunes';

    protected $fillable = [
        'condominio_id', 'nombre', 'descripcion', 'capacidad',
        'duracion_maxima_horas', 'anticipacion_minima_horas',
        'valor_garantia', 'requiere_aprobacion', 'activo',
    ];

    public function condominio(): BelongsTo
    {
        return $this->belongsTo(Condominio::class);
    }

    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class);
    }

    // Trae solo las reservas activas (confirmadas o pendientes) para validar disponibilidad
    public function reservasActivas(): HasMany
    {
        return $this->reservas()->whereIn('estado', ['pendiente', 'confirmada']);
    }
}
