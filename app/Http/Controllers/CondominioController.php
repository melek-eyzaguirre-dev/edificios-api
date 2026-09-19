<?php

namespace App\Http\Controllers;

use App\Models\Condominio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CondominioController extends Controller
{
    public function index()
    {
        return response()->json(Condominio::all());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'administradora_id' => ['required', 'exists:administradoras,id'],
            'nombre' => ['required', 'string', 'max:255'],
            'direccion' => ['required', 'string', 'max:255'],
            'comuna' => ['nullable', 'string', 'max:100'],
            'ciudad' => ['nullable', 'string', 'max:100'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $condominio = Condominio::create($validator->validated());

        return response()->json($condominio, 201);
    }

    public function show(Condominio $condominio)
    {
        return response()->json($condominio->load(['unidades', 'espaciosComunes']));
    }
}
