<?php

namespace App\Http\Controllers;

use App\Models\EspacioComun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EspacioComunController extends Controller
{
    public function index(Request $request)
    {
        $query = EspacioComun::query();

        if ($request->has('condominio_id')) {
            $query->where('condominio_id', $request->condominio_id);
        }

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'condominio_id' => ['required', 'exists:condominios,id'],
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'capacidad' => ['nullable', 'integer', 'min:1'],
            'duracion_maxima_horas' => ['nullable', 'integer', 'min:1'],
            'anticipacion_minima_horas' => ['nullable', 'integer', 'min:0'],
            'requiere_aprobacion' => ['nullable', 'boolean'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $espacio = EspacioComun::create($validator->validated());

        return response()->json($espacio, 201);
    }
}
