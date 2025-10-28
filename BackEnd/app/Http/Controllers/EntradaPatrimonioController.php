<?php

namespace App\Http\Controllers;

use App\Models\EntradaPatrimonio;
use App\Models\ProdutoPatrimonio;
use Illuminate\Http\Request;

class EntradaPatrimonioController extends Controller
{
    public function index(Request $request)
    {
        $query = EntradaPatrimonio::with('produto');

        if ($request->filled('produto')) {
            $query->whereHas('produto', function ($q) use ($request) {
                $q->where('nome', 'like', '%' . $request->produto . '%');
            });
        }

        $perPage = $request->input('per_page', 10);

        return $query->orderBy('data_entrada', 'desc')->paginate($perPage);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'produto_patrimonio_id' => 'required|exists:produtos_patrimonio,produtos_patrimonio_id',
            'quantidade' => 'required|integer|min:0',
            'data_entrada' => 'nullable|date',
            'observacao' => 'nullable|string|max:255',
        ]);

        $quantidade = $request->quantidade;
        $modelo = ProdutoPatrimonio::findOrFail($validated['produto_patrimonio_id']);
        //$ultimoNumero = ProdutoPatrimonio::max('numero_identificacao');
        $ultimoNumero = ProdutoPatrimonio::selectRaw('MAX(CAST(numero_identificacao AS UNSIGNED)) as max_num')
            ->value('max_num');
        $ultimoNumero = $ultimoNumero ? intval($ultimoNumero) : 0;

        for ($i = 1; $i <= $quantidade; $i++) {
            $novoNumero = str_pad($ultimoNumero + $i, 5, '0', STR_PAD_LEFT);

            $produto = ProdutoPatrimonio::create([
                'nome' => "$modelo->nome $i", // pode repetir nome, mas IDs são diferentes
                'descricao' => $modelo->descricao,
                'categoria_patrimonio' => $modelo->categoria_patrimonio,
                'foto' => $modelo->foto,
                'numero_identificacao' => $novoNumero,
                'observacoes' => $modelo->observacoes,
            ]);

        }

        $entrada = EntradaPatrimonio::create($validated);

        return response()->json([
            'mensagem' => 'Entrada criada com sucesso',
            'data' => $entrada,
        ], 201);
    }

}
