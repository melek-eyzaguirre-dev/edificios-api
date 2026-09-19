<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Registro pensado para uso interno (admin crea conserjes/residentes).
     * Si más adelante quieres auto-registro de residentes, se agrega
     * verificación por invitación/código de unidad — no lo abras público tal cual.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'rut' => ['nullable', 'string', 'max:20', 'unique:users,rut'],
            'password' => ['required', 'string', 'min:8'],
            'rol' => ['required', 'in:super_admin,admin_administradora,admin_condominio,conserje,residente,personal_aseo,proveedor'],
            'administradora_id' => ['nullable', 'exists:administradoras,id'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'rut' => $request->rut,
            'password' => Hash::make($request->password),
            'rol' => $request->rol,
            'administradora_id' => $request->administradora_id,
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    public function registerResident(Request $request)
    {
        $request->merge(['rol' => 'residente']);
        return $this->register($request);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Credenciales inválidas'], 401);
        }

        if (! $user->activo) {
            return response()->json(['message' => 'Usuario deshabilitado'], 403);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Sesión cerrada']);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}
