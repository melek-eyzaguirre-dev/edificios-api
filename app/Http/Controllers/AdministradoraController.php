<?php

namespace App\Http\Controllers;

use App\Models\Administradora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdministradoraController extends Controller
{
    public function index()
    {
        return response()->json(Administradora::all());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => ['required', 'string', 'max:255'],
            'rut' => ['nullable', 'string', 'max:20', 'unique:administradoras,rut'],
            'email_contacto' => ['nullable', 'email'],
            'telefono_contacto' => ['nullable', 'string', 'max:30'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $administradora = Administradora::create($validator->validated());

        return response()->json($administradora, 201);
    }

    public function show(Administradora $administradora)
    {
        return response()->json($administradora->load('condominios'));
    }
}
