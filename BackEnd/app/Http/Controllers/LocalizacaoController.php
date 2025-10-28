<?php

namespace App\Http\Controllers;

use App\Models\Localizacao;
use Illuminate\Http\Request;

class LocalizacaoController extends Controller
{
    /**
     * Listar todas as localizações
     */
    public function index()
    {
        return response()->json(Localizacao::all());
    }

    /**
     * Criar uma nova localização
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:100|unique:localizacao,nome',
        ]);

        $localizacao = Localizacao::create($request->only('nome'));

        return response()->json($localizacao, 201);
    }

    /**
     * Mostrar uma localização específica
     */
    public function show($id)
    {
        $localizacao = Localizacao::with('produtos')->findOrFail($id);
        return response()->json($localizacao);
    }

    /**
     * Atualizar uma localização
     */
    public function update(Request $request, $id)
    {
        $localizacao = Localizacao::findOrFail($id);

        $request->validate([
            'nome' => 'required|string|max:100|unique:localizacao,nome,' . $id . ',localizacao_id',
        ]);

        $localizacao->update($request->only('nome'));

        return response()->json($localizacao);
    }

    /**
     * Remover uma localização
     */
    public function destroy($id)
    {
        $localizacao = Localizacao::findOrFail($id);
        $localizacao->delete();

        return response()->json(null, 204);
    }
}
