<?php

namespace App\Http\Controllers;

use App\Models\CategoriaPatrimonio;
use Illuminate\Http\Request;

class CategoriaPatrimonioController extends Controller
{
    public function index()
    {
        return response()->json(CategoriaPatrimonio::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:90|unique:categoria_patrimonio,nome',
            'descricao' => 'nullable|string|max:255'
        ]);

        $categoria = CategoriaPatrimonio::create([
            'nome' => $request->nome,
            'descricao' => $request->descricao,
        ]);

        return response()->json($categoria, 201);
    }
}
