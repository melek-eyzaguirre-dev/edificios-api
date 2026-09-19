<?php

namespace App\Http\Controllers;

use App\Models\Estacionamiento;
use App\Models\EstacionamientoOcupacion;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class EstacionamientoOcupacionController extends Controller
{
    public function index(Request $request)
    {
        $unidadIds = $request->user()->unidades()->pluck('unidades.id');
        return EstacionamientoOcupacion::with('estacionamiento:id,codigo,moneda,tarifa_hora')
            ->whereIn('unidad_id', $unidadIds)->latest('inicio')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'estacionamiento_id' => ['required', 'exists:estacionamientos,id'],
            'inicio' => ['required', 'date', 'after_or_equal:now'],
            'fin' => ['required', 'date', 'after:inicio'],
        ]);
        $unidad = $request->user()->unidades()->first();
        abort_unless($unidad, 422, 'El residente no tiene una unidad asociada.');
        $parking = Estacionamiento::whereKey($data['estacionamiento_id'])->where('tipo', 'visita')->where('disponible', true)->firstOrFail();
        $inicio = Carbon::parse($data['inicio']);
        $fin = Carbon::parse($data['fin']);
        $horas = $inicio->diffInMinutes($fin) / 60;
        abort_if($horas > $parking->max_horas_visita, 422, "El máximo permitido es {$parking->max_horas_visita} horas.");
        abort_if(EstacionamientoOcupacion::where('estacionamiento_id', $parking->id)->where('inicio', '<', $fin)->where(function ($query) use ($inicio) { $query->whereNull('fin')->orWhere('fin', '>', $inicio); })->exists(), 422, 'Ese estacionamiento ya está reservado en ese horario.');

        $ocupacion = EstacionamientoOcupacion::create([...$data, 'unidad_id' => $unidad->id]);
        return response()->json($ocupacion->load('estacionamiento:id,codigo,moneda,tarifa_hora'), 201);
    }
}