<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CargoGastoComun extends Model
{
    use HasFactory;

    protected $table = 'cargos_gastos_comunes';
    protected $fillable = ['periodo_gastos_comunes_id', 'unidad_id', 'monto', 'pagado', 'vencimiento'];
    protected $casts = ['monto' => 'decimal:2', 'pagado' => 'decimal:2', 'vencimiento' => 'date'];

    public function periodo(): BelongsTo { return $this->belongsTo(PeriodoGastoComun::class, 'periodo_gastos_comunes_id'); }
    public function unidad(): BelongsTo { return $this->belongsTo(Unidad::class); }
}