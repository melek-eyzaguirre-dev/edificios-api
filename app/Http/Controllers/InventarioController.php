<?php

namespace App\Http\Controllers;

use App\Models\ArticuloInventario;
use App\Models\MovimientoInventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventarioController extends Controller
{
    public function index(Request $request)
    {
        return ArticuloInventario::with('proveedor:id,razon_social')
            ->when($request->condominio_id, fn ($query, $id) => $query->where('condominio_id', $id))
            ->orderBy('nombre')->paginate(20);
    }

    public function store(Request $request)
    {
        return response()->json(ArticuloInventario::create($request->validate([
            'condominio_id' => ['required', 'exists:condominios,id'], 'proveedor_id' => ['nullable', 'exists:proveedores,id'],
            'nombre' => ['required', 'string', 'max:255'], 'categoria' => ['sometimes', 'string', 'max:100'],
            'unidad_medida' => ['sometimes', 'string', 'max:30'], 'stock_actual' => ['sometimes', 'numeric', 'min:0'],
            'stock_minimo' => ['sometimes', 'numeric', 'min:0'], 'costo_unitario' => ['nullable', 'numeric', 'min:0'],
        ])), 201);
    }

    public function movement(Request $request, ArticuloInventario $articuloInventario)
    {
        $data = $request->validate(['tipo' => ['required', 'in:entrada,salida,ajuste'], 'cantidad' => ['required', 'numeric', 'gt:0'], 'motivo' => ['nullable', 'string', 'max:255']]);
        return DB::transaction(function () use ($data, $articuloInventario, $request) {
            $newStock = match ($data['tipo']) {
                'entrada' => $articuloInventario->stock_actual + $data['cantidad'],
                'salida' => $articuloInventario->stock_actual - $data['cantidad'],
                'ajuste' => $data['cantidad'],
            };
            if ($newStock < 0) {
                return response()->json(['message' => 'Stock insuficiente para registrar la salida'], 422);
            }
            $articuloInventario->update(['stock_actual' => $newStock]);
            $movement = MovimientoInventario::create([...$data, 'articulo_inventario_id' => $articuloInventario->id, 'registrado_por' => $request->user()->id]);

            return response()->json(['articulo' => $articuloInventario->fresh(), 'movimiento' => $movement], 201);
        });
    }
}