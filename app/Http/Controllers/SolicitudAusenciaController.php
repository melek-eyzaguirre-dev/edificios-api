<?php

namespace App\Http\Controllers;

use App\Models\SolicitudAusencia;
use Illuminate\Http\Request;

class SolicitudAusenciaController extends Controller
{
    public function index(Request $request)
    {
        return SolicitudAusencia::with('usuario:id,name,email,rut,rol')
            ->when(in_array($request->user()->rol, ['conserje', 'personal_aseo'], true), fn ($query) => $query->where('user_id', $request->user()->id))
            ->latest()->paginate(30);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tipo' => ['required', 'in:licencia_medica,permiso,vacaciones,inasistencia_justificada'],
            'desde' => ['required', 'date'],
            'hasta' => ['required', 'date', 'after_or_equal:desde'],
            'motivo' => ['required', 'string', 'max:2000'],
            'documento' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ]);
        if ($request->hasFile('documento')) {
            $data['documento_path'] = $request->file('documento')->store('ausencias/documentos', 'private');
            $data['documento_mime'] = $request->file('documento')->getMimeType();
        }
        unset($data['documento']);
        $data['user_id'] = $request->user()->id;
        return response()->json(SolicitudAusencia::create($data), 201);
    }

    public function update(Request $request, SolicitudAusencia $solicitudAusencia)
    {
        $data = $request->validate(['estado' => ['required', 'in:aprobada,rechazada'], 'observaciones' => ['nullable', 'string', 'max:2000']]);
        $solicitudAusencia->update([...$data, 'revisada_por' => $request->user()->id]);
        return $solicitudAusencia->fresh('usuario:id,name,email,rut,rol');
    }
}