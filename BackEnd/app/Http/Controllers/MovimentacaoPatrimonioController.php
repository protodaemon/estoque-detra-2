<?php

namespace App\Http\Controllers;

use App\Models\MovimentacaoPatrimonio;
use Illuminate\Http\Request;

class MovimentacaoPatrimonioController extends Controller
{
    public function index(Request $request)
    {
        $query = MovimentacaoPatrimonio::with([
            'produto.categoria',
            'localizacaoAnterior',
            'localizacaoNova',
            'responsavel'
        ]);

        // Filtro por produto
        if ($request->has('produto') && $request->produto) {
            $query->whereHas('produto', function ($q) use ($request) {
                $q->where('nome', 'like', '%' . $request->produto . '%')
                    ->orWhere('numero_identificacao', 'like', '%' . $request->produto . '%');
            });
        }

        // Filtro por tipo de movimentação
        if ($request->has('tipo') && $request->tipo) {
            $query->where('tipo_movimentacao', $request->tipo);
        }

        // Filtro por data
        if ($request->has('data_inicio') && $request->data_inicio) {
            $query->whereDate('data_movimentacao', '>=', $request->data_inicio);
        }

        if ($request->has('data_fim') && $request->data_fim) {
            $query->whereDate('data_movimentacao', '<=', $request->data_fim);
        }

        $perPage = $request->get('per_page', 10);
        $movimentacoes = $query->orderBy('data_movimentacao', 'desc')->paginate($perPage);

        return response()->json($movimentacoes);
    }
}
