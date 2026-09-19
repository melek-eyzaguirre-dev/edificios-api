<?php

namespace App\Http\Controllers;

use App\Models\Estacionamiento;
use Illuminate\Http\Request;

class EstacionamientoController extends Controller
{
    public function index(Request $request)
    {
        return Estacionamiento::with('unidad:id,numero,torre')
            ->when($request->filled('condominio_id'), fn ($query) => $query->where('condominio_id', $request->condominio_id))
            ->orderBy('codigo')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'condominio_id' => ['required', 'exists:condominios,id'],
            'codigo' => ['required', 'string', 'max:30'],
            'tipo' => ['required', 'in:fijo,visita'],
            'unidad_id' => ['nullable', 'exists:unidades,id'],
            'disponible' => ['sometimes', 'boolean'],
            'moneda' => ['sometimes', 'string', 'size:3'],
            'tarifa_hora' => ['sometimes', 'numeric', 'min:0'],
            'max_horas_visita' => ['sometimes', 'integer', 'min:1', 'max:24'],
        ]);
        return response()->json(Estacionamiento::create($data), 201);
    }

    public function update(Request $request, Estacionamiento $estacionamiento)
    {
        $data = $request->validate([
            'codigo' => ['sometimes', 'string', 'max:30'],
            'tipo' => ['sometimes', 'in:fijo,visita'],
            'unidad_id' => ['nullable', 'exists:unidades,id'],
            'disponible' => ['sometimes', 'boolean'],
            'moneda' => ['sometimes', 'string', 'size:3'],
            'tarifa_hora' => ['sometimes', 'numeric', 'min:0'],
            'max_horas_visita' => ['sometimes', 'integer', 'min:1', 'max:24'],
        ]);
        $estacionamiento->update($data);
        return $estacionamiento->fresh('unidad:id,numero,torre');
    }

    public function destroy(Estacionamiento $estacionamiento)
    {
        $estacionamiento->delete();
        return response()->json(['message' => 'Estacionamiento eliminado']);
    }
}