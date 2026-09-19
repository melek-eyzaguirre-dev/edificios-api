<?php

namespace App\Http\Controllers;

use App\Models\Novedad;
use App\Models\Reserva;
use App\Models\Visita;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function summary(Request $request)
    {
        $condominioId = $request->validate(['condominio_id' => ['required', 'exists:condominios,id']])['condominio_id'];
        $inicio = now()->subDays(6)->startOfDay();

        $visitas = Visita::whereHas('unidad', fn ($query) => $query->where('condominio_id', $condominioId));
        $reservas = Reserva::whereHas('unidad', fn ($query) => $query->where('condominio_id', $condominioId));
        $novedades = Novedad::where('condominio_id', $condominioId);

        $visitasPorDia = collect(CarbonPeriod::create($inicio->copy()->startOfDay(), now()->copy()->startOfDay()))
            ->map(fn ($fecha) => [
                'fecha' => $fecha->format('Y-m-d'),
                'visitas' => (clone $visitas)->whereDate('created_at', $fecha)->count(),
                'novedades' => (clone $novedades)->whereDate('created_at', $fecha)->count(),
            ])->values();

        return response()->json([
            'kpis' => [
                'reservas_mes' => (clone $reservas)->whereMonth('inicio', now()->month)->whereYear('inicio', now()->year)->count(),
                'visitas_hoy' => (clone $visitas)->whereDate('created_at', today())->count(),
                'visitas_dentro' => (clone $visitas)->where('estado', 'en_edificio')->count(),
                'novedades_abiertas' => (clone $novedades)->whereIn('estado', ['abierta', 'en_proceso'])->count(),
            ],
            'series' => $visitasPorDia,
        ]);
    }
}