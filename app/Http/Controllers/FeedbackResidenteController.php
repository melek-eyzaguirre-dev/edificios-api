<?php

namespace App\Http\Controllers;

use App\Models\FeedbackResidente;
use Illuminate\Http\Request;

class FeedbackResidenteController extends Controller
{
    public function index(Request $request)
    {
        return FeedbackResidente::where('user_id', $request->user()->id)->latest()->get();
    }

    public function store(Request $request)
    {
        $unidad = $request->user()->unidades()->first();
        abort_unless($unidad, 422, 'El usuario no tiene una unidad asociada.');
        $data = $request->validate(['tipo' => ['required', 'in:reclamo,sugerencia,felicitacion'], 'asunto' => ['required', 'string', 'max:255'], 'mensaje' => ['required', 'string']]);
        return response()->json(FeedbackResidente::create([...$data, 'user_id' => $request->user()->id, 'unidad_id' => $unidad->id, 'condominio_id' => $unidad->condominio_id]), 201);
    }
}