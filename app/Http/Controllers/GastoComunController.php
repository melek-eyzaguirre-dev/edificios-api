<?php

namespace App\Http\Controllers;

use App\Models\CargoGastoComun;
use App\Models\PeriodoGastoComun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GastoComunController extends Controller
{
    public function index(Request $request)
    {
        return PeriodoGastoComun::withCount('cargos')
            ->when($request->condominio_id, fn ($query, $id) => $query->where('condominio_id', $id))
            ->latest('periodo')->paginate(12);
    }

    public function store(Request $request)
    {
        $data = $request->validate(['condominio_id' => ['required', 'exists:condominios,id'], 'periodo' => ['required', 'date_format:Y-m-d'], 'total_gastos' => ['required', 'numeric', 'min:0']]);
        return response()->json(PeriodoGastoComun::create($data), 201);
    }

    public function detail(PeriodoGastoComun $periodoGastoComun)
    {
        return response()->json($periodoGastoComun->load('cargos.unidad'));
    }

    public function charges(Request $request, PeriodoGastoComun $periodoGastoComun)
    {
        $data = $request->validate(['cargos' => ['required', 'array', 'min:1'], 'cargos.*.unidad_id' => ['required', 'exists:unidades,id'], 'cargos.*.monto' => ['required', 'numeric', 'min:0'], 'cargos.*.vencimiento' => ['nullable', 'date']]);
        return DB::transaction(function () use ($data, $periodoGastoComun) {
            foreach ($data['cargos'] as $charge) {
                CargoGastoComun::updateOrCreate(['periodo_gastos_comunes_id' => $periodoGastoComun->id, 'unidad_id' => $charge['unidad_id']], $charge);
            }
            $periodoGastoComun->update(['estado' => 'emitido']);

            return $periodoGastoComun->fresh('cargos.unidad');
        });
    }

    public function distribute(Request $request, PeriodoGastoComun $periodoGastoComun)
    {
        $data = $request->validate(['vencimiento' => ['nullable', 'date']]);
        $unidades = $periodoGastoComun->condominio->unidades()->orderBy('id')->get();
        $prorrateoTotal = (float) $unidades->sum(fn ($unidad) => (float) ($unidad->prorrateo ?: 0));
        abort_if($unidades->isEmpty() || $prorrateoTotal <= 0, 422, 'Las unidades deben tener prorrateo configurado.');

        return DB::transaction(function () use ($data, $periodoGastoComun, $unidades, $prorrateoTotal) {
            $totalCentavos = (int) round((float) $periodoGastoComun->total_gastos * 100);
            $asignado = 0;
            foreach ($unidades as $index => $unidad) {
                $montoCentavos = $index === $unidades->count() - 1
                    ? $totalCentavos - $asignado
                    : (int) round($totalCentavos * ((float) $unidad->prorrateo / $prorrateoTotal));
                $asignado += $montoCentavos;
                CargoGastoComun::updateOrCreate(
                    ['periodo_gastos_comunes_id' => $periodoGastoComun->id, 'unidad_id' => $unidad->id],
                    ['monto' => $montoCentavos / 100, 'vencimiento' => $data['vencimiento'] ?? null],
                );
            }
            $periodoGastoComun->update(['estado' => 'emitido']);
            return $periodoGastoComun->fresh('cargos.unidad');
        });
    }

    public function pay(Request $request, CargoGastoComun $cargoGastoComun)
    {
        $data = $request->validate(['pagado' => ['required', 'numeric', 'min:0', 'lte:monto']]);
        $cargoGastoComun->update($data);

        return $cargoGastoComun->fresh('unidad');
    }
}