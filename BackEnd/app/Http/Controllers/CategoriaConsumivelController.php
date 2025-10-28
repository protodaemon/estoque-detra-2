<?php

namespace App\Http\Controllers;

use App\Models\CategoriaConsumivel;
use Illuminate\Http\Request;

class CategoriaConsumivelController extends Controller
{
    public function index()
    {
        return response()->json(CategoriaConsumivel::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:90|unique:categoria_consumivel,nome',
            'descricao' => 'nullable|string|max:255'
        ]);

        $categoria = CategoriaConsumivel::create([
            'nome' => $request->nome,
            'descricao' => $request->descricao,
        ]);

        return response()->json($categoria, 201);
    }
}
