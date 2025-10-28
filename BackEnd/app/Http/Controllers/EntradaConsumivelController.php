<?php

namespace App\Http\Controllers;

use App\Models\EntradaConsumivel;
use App\Models\ProdutoConsumivel;
use Illuminate\Http\Request;

class EntradaConsumivelController extends Controller
{


    public function index(Request $request)
    {
        $query = EntradaConsumivel::with('produto');

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
            'produto_consumivel_id' => 'required|exists:produtos_consumivel,produtos_consumivel_id',
            'quantidade' => 'required|integer|min:0',
            'data_entrada' => 'nullable|date',
            'observacao' => 'nullable|string|max:255',
        ]);

        $quantidade = (int) $validated['quantidade'];

        // Atualização atômica do estoque (evita race conditions)
        ProdutoConsumivel::where('produtos_consumivel_id', $validated['produto_consumivel_id'])
            ->increment('quantidade', $quantidade);

        $entrada = EntradaConsumivel::create($validated);

        return response()->json([
            'mensagem' => 'Entrada criada com sucesso',
            'data' => $entrada,
        ], 201);
    }
}
