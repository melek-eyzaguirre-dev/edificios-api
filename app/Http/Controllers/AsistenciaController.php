<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use Illuminate\Http\Request;

class AsistenciaController extends Controller
{
    public function index(Request $request)
    {
        return Asistencia::with(['usuario:id,name,rol', 'turno:id,tipo,sistema,inicio,fin'])
            ->when(in_array($request->user()->rol, ['conserje', 'personal_aseo'], true), fn ($query) => $query->where('user_id', $request->user()->id))
            ->when($request->filled('turno_id'), fn ($query) => $query->where('turno_id', $request->turno_id))
            ->when($request->filled('desde'), fn ($query) => $query->whereDate('fecha', '>=', $request->desde))
            ->latest('fecha')->paginate(30);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'turno_id' => ['required', 'exists:turnos,id'],
            'user_id' => ['required', 'exists:users,id'],
            'fecha' => ['required', 'date'],
            'entrada' => ['nullable', 'date'],
            'salida' => ['nullable', 'date', 'after:entrada'],
            'minutos_extra' => ['nullable', 'integer', 'min:0', 'max:720'],
            'observaciones' => ['nullable', 'string'],
        ]);
        return response()->json(Asistencia::updateOrCreate(['turno_id' => $data['turno_id'], 'fecha' => $data['fecha']], $data)->load('usuario:id,name,rol'), 201);
    }

    public function marcarEntrada(Request $request, Asistencia $asistencia)
    {
        $asistencia->update(['entrada' => now()]);
        return $asistencia->fresh('usuario:id,name,rol');
    }

    public function marcarSalida(Request $request, Asistencia $asistencia)
    {
        $asistencia->update(['salida' => now()]);
        return $asistencia->fresh('usuario:id,name,rol');
    }
}