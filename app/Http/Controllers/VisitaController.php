<?php

namespace App\Http\Controllers;

use App\Models\Visita;
use Illuminate\Http\Request;

class VisitaController extends Controller
{
    public function index(Request $request)
    {
        return Visita::with(['unidad.condominio', 'registradoPor:id,name'])
            ->when($request->filled('condominio_id'), fn ($query) => $query->whereHas('unidad', fn ($unidad) => $unidad->where('condominio_id', $request->condominio_id)))
            ->when($request->filled('estado'), fn ($query) => $query->where('estado', $request->estado))
            ->when($request->filled('rut'), fn ($query) => $query->where('rut_visitante', 'like', '%'.$request->rut.'%'))
            ->latest()
            ->paginate(25);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'unidad_id' => ['required', 'exists:unidades,id'],
            'nombre_visitante' => ['required', 'string', 'max:255'],
            'rut_visitante' => ['nullable', 'string', 'max:30'],
            'autorizado_desde' => ['nullable', 'date'],
            'autorizado_hasta' => ['nullable', 'date', 'after:autorizado_desde'],
        ]);
        $data['registrado_por'] = $request->user()->id;
        $data['estado'] = 'autorizada';

        return response()->json(Visita::create($data)->load(['unidad', 'registradoPor:id,name']), 201);
    }

    public function update(Request $request, Visita $visita)
    {
        $data = $request->validate([
            'estado' => ['required', 'in:autorizada,en_edificio,finalizada,rechazada'],
            'motivo_rechazo' => ['required_if:estado,rechazada', 'nullable', 'string', 'max:1000'],
        ]);
        $data['hora_ingreso'] = $data['estado'] === 'en_edificio' ? now() : $visita->hora_ingreso;
        $data['hora_salida'] = $data['estado'] === 'finalizada' ? now() : $visita->hora_salida;
        $visita->update($data);

        return $visita->fresh(['unidad', 'registradoPor:id,name']);
    }

    public function reportarNoAutorizada(Request $request, Visita $visita)
    {
        $data = $request->validate(['motivo_rechazo' => ['required', 'string', 'max:1000']]);
        abort_unless($request->user()->unidades()->whereKey($visita->unidad_id)->exists(), 403, 'Solo un residente de la unidad puede reportar esta visita.');

        $visita->update([
            'estado' => 'rechazada',
            'motivo_rechazo' => $data['motivo_rechazo'],
            'reportada_por' => $request->user()->id,
        ]);

        return $visita->fresh(['unidad', 'reportadaPor:id,name']);
    }

    public function destroy(Visita $visita)
    {
        $visita->delete();
        return response()->json(['message' => 'Visita eliminada']);
    }
}