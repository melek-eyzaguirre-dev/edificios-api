<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SolicitudAusencia extends Model
{
    protected $table = 'solicitudes_ausencia';
    protected $fillable = ['user_id', 'revisada_por', 'tipo', 'desde', 'hasta', 'motivo', 'documento_path', 'documento_mime', 'estado', 'observaciones'];
    protected $casts = ['desde' => 'date', 'hasta' => 'date'];
    public function usuario(): BelongsTo { return $this->belongsTo(User::class, 'user_id'); }
    public function revisadaPor(): BelongsTo { return $this->belongsTo(User::class, 'revisada_por'); }
}