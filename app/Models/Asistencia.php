<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asistencia extends Model
{
    protected $fillable = ['turno_id', 'user_id', 'fecha', 'entrada', 'salida', 'minutos_extra', 'observaciones'];
    protected $casts = ['fecha' => 'date', 'entrada' => 'datetime', 'salida' => 'datetime'];

    public function turno(): BelongsTo { return $this->belongsTo(Turno::class); }
    public function usuario(): BelongsTo { return $this->belongsTo(User::class, 'user_id'); }
}