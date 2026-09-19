<?php

namespace App\Http\Controllers;

use App\Models\CargoGastoComun;
use App\Models\Estacionamiento;
use Illuminate\Http\Request;

class ResidenteController extends Controller
{
    public function summary(Request $request)
    {
        $unidad = $request->user()->unidades()->with('residentes:id,name,email')->first();
        abort_unless($unidad, 404, 'El usuario no tiene una unidad asociada.');

        $cargo = CargoGastoComun::with('periodo:id,periodo,estado')
            ->where('unidad_id', $unidad->id)->latest('id')->first();
        $condominioId = $unidad->condominio_id;

        return response()->json([
            'unidad' => $unidad,
            'tipo_relacion' => $unidad->pivot?->tipo,
            'habitantes' => $unidad->residentes,
            'estado_cuenta' => $cargo ? [
                'periodo' => $cargo->periodo?->periodo?->format('Y-m'),
                'monto' => (float) $cargo->monto,
                'pagado' => (float) $cargo->pagado,
                'saldo_anterior' => max(0, (float) $cargo->monto - (float) $cargo->pagado),
                'vencimiento' => $cargo->vencimiento?->format('Y-m-d'),
                'estado' => (float) $cargo->pagado >= (float) $cargo->monto ? 'pagado' : 'pendiente',
            ] : null,
            'estacionamientos_visita' => Estacionamiento::where('condominio_id', $condominioId)->where('tipo', 'visita')->where('disponible', true)->get(['id', 'codigo', 'moneda', 'tarifa_hora', 'max_horas_visita']),
        ]);
    }
}