<?php

namespace App\Http\Controllers;

use App\Models\Turno;
use Illuminate\Http\Request;

class TurnoController extends Controller
{
    public function index(Request $request)
    {
        return Turno::with('usuario:id,name,email,rol')
            ->when($request->filled('condominio_id'), fn ($query) => $query->where('condominio_id', $request->condominio_id))
            ->when($request->filled('desde'), fn ($query) => $query->where('inicio', '>=', $request->desde))
            ->orderBy('inicio')
            ->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'condominio_id' => ['required', 'exists:condominios,id'],
            'user_id' => ['required', 'exists:users,id'],
            'tipo' => ['required', 'in:diurno,nocturno,fin_de_semana,personalizado'],
            'sistema' => ['required', 'in:4x4,6x1,5x2,personalizado'],
            'inicio' => ['required', 'date'],
            'fin' => ['required', 'date', 'after:inicio'],
            'dias_trabajados' => ['nullable', 'array'],
            'dias_libres' => ['nullable', 'array'],
            'vigente_desde' => ['nullable', 'date'],
            'vigente_hasta' => ['nullable', 'date', 'after_or_equal:vigente_desde'],
            'notas' => ['nullable', 'string'],
        ]);

        return response()->json(Turno::create($data)->load('usuario:id,name,email,rol'), 201);
    }
}