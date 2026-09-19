<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeedbackResidente extends Model
{
    protected $table = 'feedback_residentes';
    protected $fillable = ['condominio_id', 'unidad_id', 'user_id', 'tipo', 'asunto', 'mensaje', 'estado', 'respuesta'];
    public function usuario(): BelongsTo { return $this->belongsTo(User::class, 'user_id'); }
    public function unidad(): BelongsTo { return $this->belongsTo(Unidad::class); }
}