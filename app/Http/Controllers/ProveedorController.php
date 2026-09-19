<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index(Request $request)
    {
        return Proveedor::query()
            ->when($request->condominio_id, fn ($query, $id) => $query->where('condominio_id', $id))
            ->orderBy('razon_social')->paginate(20);
    }

    public function store(Request $request)
    {
        return response()->json(Proveedor::create($request->validate([
            'condominio_id' => ['required', 'exists:condominios,id'],
            'razon_social' => ['required', 'string', 'max:255'],
            'rut' => ['nullable', 'string', 'max:20'],
            'contacto' => ['nullable', 'string', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'categoria' => ['nullable', 'string', 'max:100'],
            'activo' => ['sometimes', 'boolean'],
        ])), 201);
    }

    public function update(Request $request, Proveedor $proveedor)
    {
        $proveedor->update($request->validate([
            'razon_social' => ['sometimes', 'string', 'max:255'], 'rut' => ['nullable', 'string', 'max:20'],
            'contacto' => ['nullable', 'string', 'max:255'], 'telefono' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'], 'categoria' => ['nullable', 'string', 'max:100'], 'activo' => ['sometimes', 'boolean'],
        ]));

        return $proveedor->fresh();
    }
}