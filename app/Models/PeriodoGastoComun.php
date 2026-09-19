<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PeriodoGastoComun extends Model
{
    use HasFactory;

    protected $table = 'periodos_gastos_comunes';
    protected $fillable = ['condominio_id', 'periodo', 'total_gastos', 'estado'];
    protected $casts = ['periodo' => 'date', 'total_gastos' => 'decimal:2'];

    public function condominio(): BelongsTo { return $this->belongsTo(Condominio::class); }
    public function cargos(): HasMany { return $this->hasMany(CargoGastoComun::class, 'periodo_gastos_comunes_id'); }
}