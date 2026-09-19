<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MovimientoInventario extends Model
{
    use HasFactory;

    protected $table = 'movimientos_inventario';
    protected $fillable = ['articulo_inventario_id', 'registrado_por', 'tipo', 'cantidad', 'motivo'];
    protected $casts = ['cantidad' => 'decimal:2'];

    public function articulo(): BelongsTo { return $this->belongsTo(ArticuloInventario::class, 'articulo_inventario_id'); }
}