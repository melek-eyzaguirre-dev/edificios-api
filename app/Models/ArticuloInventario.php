<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ArticuloInventario extends Model
{
    use HasFactory;

    protected $table = 'articulos_inventario';
    protected $fillable = ['condominio_id', 'proveedor_id', 'nombre', 'categoria', 'unidad_medida', 'stock_actual', 'stock_minimo', 'costo_unitario', 'activo'];
    protected $casts = ['stock_actual' => 'decimal:2', 'stock_minimo' => 'decimal:2', 'costo_unitario' => 'decimal:2', 'activo' => 'boolean'];

    public function proveedor(): BelongsTo { return $this->belongsTo(Proveedor::class); }
    public function movimientos(): HasMany { return $this->hasMany(MovimientoInventario::class); }
}