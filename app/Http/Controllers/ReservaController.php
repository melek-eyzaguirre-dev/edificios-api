<?php

namespace App\Http\Controllers;

use App\Models\EspacioComun;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReservaController extends Controller
{
    public function index(Request $request)
    {
        $query = Reserva::with(['espacioComun', 'unidad', 'user']);

        if ($request->has('espacio_comun_id')) {
            $query->where('espacio_comun_id', $request->espacio_comun_id);
        }

        if ($request->filled('condominio_id')) {
            $query->whereHas('unidad', fn ($unidad) => $unidad->where('condominio_id', $request->condominio_id));
        }

        return response()->json($query->orderBy('inicio')->get());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'espacio_comun_id' => ['required', 'exists:espacios_comunes,id'],
            'unidad_id' => ['required', 'exists:unidades,id'],
            'inicio' => ['required', 'date'],
            'fin' => ['required', 'date', 'after:inicio'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();
        $espacio = EspacioComun::findOrFail($data['espacio_comun_id']);

        // Regla 1: el espacio debe estar activo
        if (! $espacio->activo) {
            return response()->json(['message' => 'Este espacio común no está disponible'], 422);
        }

        // Regla 2: no debe solaparse con otra reserva activa (pendiente o confirmada)
        if (Reserva::seSolapa($data['espacio_comun_id'], $data['inicio'], $data['fin'])) {
            return response()->json(['message' => 'Ese horario ya está reservado para este espacio'], 422);
        }

        // Regla 3: duración máxima permitida
        $horas = (strtotime($data['fin']) - strtotime($data['inicio'])) / 3600;
        if ($horas > $espacio->duracion_maxima_horas) {
            return response()->json([
                'message' => "La reserva excede la duración máxima permitida ({$espacio->duracion_maxima_horas}h)",
            ], 422);
        }

        // Regla 4: anticipación mínima
        $horasAnticipacion = (strtotime($data['inicio']) - time()) / 3600;
        if ($horasAnticipacion < $espacio->anticipacion_minima_horas) {
            return response()->json([
                'message' => "Debes reservar con al menos {$espacio->anticipacion_minima_horas}h de anticipación",
            ], 422);
        }

        $reserva = Reserva::create([
            ...$data,
            'user_id' => $request->user()->id,
            'estado' => $espacio->requiere_aprobacion ? 'pendiente' : 'confirmada',
        ]);

        return response()->json($reserva->load(['espacioComun', 'unidad']), 201);
    }

    public function update(Request $request, Reserva $reserva)
    {
        $validator = Validator::make($request->all(), [
            'estado' => ['required', 'in:confirmada,rechazada,cancelada'],
            'motivo_rechazo' => ['required_if:estado,rechazada', 'nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $reserva->update($validator->validated());

        return response()->json($reserva);
    }

    public function destroy(Reserva $reserva)
    {
        $reserva->update(['estado' => 'cancelada']);

        return response()->json(['message' => 'Reserva cancelada']);
    }
}
