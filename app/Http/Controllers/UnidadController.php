<?php

namespace App\Http\Controllers;

use App\Models\Unidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UnidadController extends Controller
{
    public function index(Request $request)
    {
        $query = Unidad::query();

        if ($request->has('condominio_id')) {
            $query->where('condominio_id', $request->condominio_id);
        }

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'condominio_id' => ['required', 'exists:condominios,id'],
            'numero' => ['required', 'string', 'max:20'],
            'torre' => ['nullable', 'string', 'max:20'],
            'prorrateo' => ['nullable', 'numeric'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $unidad = Unidad::create($validator->validated());

        return response()->json($unidad, 201);
    }

    public function show(Unidad $unidad)
    {
        return response()->json($unidad->load('residentes'));
    }
}
