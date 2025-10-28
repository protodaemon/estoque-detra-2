<?php

namespace App\Http\Controllers;

use App\Models\SaidaConsumivel;
use App\Models\ProdutoConsumivel;
use Illuminate\Http\Request;

class SaidaConsumivelController extends Controller
{

    // funcao pra inverter o sinal de um numero, pra quantidade ser usado corretamente na logica do for no store()
    public function invertSign($number) {
    return $number * -1;
    }

    public function index(Request $request)
    {
        $query = SaidaConsumivel::with('produto');

        if ($request->filled('produto')) {
            $query->whereHas('produto', function ($q) use ($request) {
                $q->where('nome', 'like', '%' . $request->produto . '%');
            });
        }

        $perPage = $request->input('per_page', 10);

        // ordenar por data de saída desc e depois por nome asc
        return $query
            ->orderBy('data_saida', 'desc')
            // ->orderBy('nome', 'asc')
            ->paginate($perPage);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'produto_consumivel_id' => 'required|exists:produtos_consumivel,produtos_consumivel_id',
            'quantidade' => 'required|integer|min:0',
            'data_saida' => 'nullable|date',
            'observacao' => 'nullable|string|max:255',
        ]);

        $quantidade = (int) $validated['quantidade'];

        // Atualização atômica do estoque (evita race conditions)
        ProdutoConsumivel::where('produtos_consumivel_id', $validated['produto_consumivel_id'])
            ->decrement('quantidade', $quantidade);

        $saida = SaidaConsumivel::create($validated);

        return response()->json([
            'mensagem' => 'Saida criada com sucesso',
            'data' => $saida,
        ], 201);
    }
}
