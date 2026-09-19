<?php

namespace App\Http\Controllers;

use App\Models\Novedad;
use Illuminate\Http\Request;

class NovedadController extends Controller
{
    public function index(Request $request)
    {
        return Novedad::with(['registradoPor:id,name', 'turno.usuario:id,name'])
            ->when($request->condominio_id, fn ($query, $id) => $query->where('condominio_id', $id))
            ->latest()->paginate(20);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'condominio_id' => ['required', 'exists:condominios,id'],
            'turno_id' => ['nullable', 'exists:turnos,id'],
            'fecha_hora' => ['nullable', 'date'],
            'titulo' => ['required', 'string', 'max:255'],
            'descripcion' => ['required', 'string'],
            'prioridad' => ['sometimes', 'in:baja,media,alta,critica'],
            'evidencia' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,mp3,wav,m4a,ogg', 'max:20480'],
        ]);
        $data['registrado_por'] = $request->user()->id;
        if (! empty($data['fecha_hora'])) {
            $data['created_at'] = $data['fecha_hora'];
            unset($data['fecha_hora']);
        }
        if ($request->hasFile('evidencia')) {
            $file = $request->file('evidencia');
            $data['evidencia_path'] = $file->store('novedades/evidencias', 'private');
            $data['evidencia_mime'] = $file->getMimeType();
        }

        return response()->json(Novedad::create($data)->load(['registradoPor:id,name', 'turno.usuario:id,name']), 201);
    }

    public function update(Request $request, Novedad $novedad)
    {
        $data = $request->validate([
            'titulo' => ['sometimes', 'string', 'max:255'],
            'descripcion' => ['sometimes', 'string'],
            'prioridad' => ['sometimes', 'in:baja,media,alta,critica'],
            'estado' => ['sometimes', 'in:abierta,en_proceso,resuelta'],
        ]);
        if (($data['estado'] ?? null) === 'resuelta') {
            $data['resuelta_at'] = now();
        }
        $novedad->update($data);

        return $novedad->fresh(['registradoPor:id,name', 'turno.usuario:id,name']);
    }
}