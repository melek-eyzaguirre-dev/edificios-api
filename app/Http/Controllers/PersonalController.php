<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PersonalController extends Controller
{
    public function index(Request $request)
    {
        return User::query()
            ->whereIn('rol', ['conserje', 'admin_condominio', 'admin_administradora', 'personal_aseo', 'proveedor'])
            ->where('activo', true)
            ->when($request->filled('rol'), fn ($query) => $query->where('rol', $request->rol))
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'rol', 'telefono']);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'rut' => ['nullable', 'string', 'max:20', 'unique:users,rut'],
            'password' => ['required', 'string', 'min:8'],
            'rol' => ['required', 'in:conserje,personal_aseo,proveedor,admin_condominio'],
            'telefono' => ['nullable', 'string', 'max:30'],
        ]);
        $data['password'] = Hash::make($data['password']);
        return response()->json(User::create($data), 201);
    }

    public function update(Request $request, User $personal)
    {
        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:30'],
            'rol' => ['sometimes', 'in:conserje,personal_aseo,proveedor,admin_condominio'],
            'activo' => ['sometimes', 'boolean'],
        ]);
        $personal->update($data);
        return $personal->fresh();
    }

    public function destroy(User $personal)
    {
        $personal->update(['activo' => false]);
        return response()->json(['message' => 'Personal desactivado']);
    }
}