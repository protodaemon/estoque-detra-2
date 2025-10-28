<?php

namespace App\Http\Controllers;

use App\Models\ProdutoPedidoConsumivel;
use App\Models\ProdutoConsumivel; // Importar o model do produto
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Importar para usar transações
use Illuminate\Support\Facades\Log; // Importar para logs

class ProdutoPedidoConsumivelController extends Controller
{
    // ... seus outros métodos como index(), show(), etc.

    /**
     * Adiciona um novo produto a um pedido existente.
     */
    public function store(Request $request)
    {
        // 1. VALIDAÇÃO DOS DADOS DE ENTRADA
        $validatedData = $request->validate([
            'pedido_consumivel_id'   => 'required|integer|exists:pedido_consumivel,pedido_consumivel_id',
            'produtos_consumivel_id' => 'required|integer|exists:produtos_consumivel,produtos_consumivel_id',
            'quantidade'             => 'required|integer|min:1',
        ]);

        try {
            // 2. TRANSAÇÃO DE BANCO DE DADOS (garante que tudo aconteça, ou nada aconteça)
            $itemPedido = DB::transaction(function () use ($validatedData) {
                
                // 3. VERIFICAÇÃO DE ESTOQUE
                $produto = ProdutoConsumivel::find($validatedData['produtos_consumivel_id']);
                
                if ($produto->quantidade < $validatedData['quantidade']) {
                    // Se não há estoque suficiente, lança uma exceção.
                    // Isso irá automaticamente disparar o ROLLBACK da transação.
                    throw new \Exception('Estoque insuficiente para o produto: ' . $produto->nome);
                }

                // 4. CRIAÇÃO DO REGISTRO NA TABELA DE LIGAÇÃO
                // Usa o método updateOrCreate para evitar duplicatas:
                // - Se já existe um item com esse produto nesse pedido, apenas soma a quantidade.
                // - Se não existe, cria um novo registro.
                $item = ProdutoPedidoConsumivel::updateOrCreate(
                    [
                        'pedido_consumivel_id'   => $validatedData['pedido_consumivel_id'],
                        'produtos_consumivel_id' => $validatedData['produtos_consumivel_id'],
                    ],
                    [
                        'quantidade' => DB::raw('quantidade + ' . $validatedData['quantidade']) // Soma a quantidade
                    ]
                );

                // 5. ATUALIZAÇÃO DO ESTOQUE (deduz a quantidade)
                $produto->quantidade -= $validatedData['quantidade'];
                $produto->save();

                return $item; // Retorna o item criado/atualizado
            });

            // Se a transação foi bem-sucedida, retorna a resposta
            return response()->json([
                'mensagem' => 'Item adicionado ao pedido com sucesso!',
                'item' => $itemPedido
            ], 201); // 201 Created

        } catch (\Exception $e) {
            // Se qualquer exceção foi lançada, a transação já foi desfeita (ROLLBACK).
            Log::error('Erro ao adicionar item ao pedido: ' . $e->getMessage());

            // Retorna o erro específico (ex: "Estoque insuficiente...") ou uma mensagem genérica
            return response()->json(['erro' => $e->getMessage()], 422); // 422 Unprocessable Entity
        }
    }
}