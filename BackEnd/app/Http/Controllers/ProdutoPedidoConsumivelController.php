<?php

namespace App\Http\Controllers;

use App\Models\ProdutoPedidoConsumivel;
use App\Models\ProdutoConsumivel;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class ProdutoPedidoConsumivelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /*
     * 
     * 
     * 
     * 
     * 
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
            'pedido_consumivel_id'   => 'required|integer|exists:pedido_consumivel,pedido_consumivel_id',
            'produtos_consumivel_id' => 'required|integer|exists:produtos_consumivel,produtos_consumivel_id',
            'quantidade'             => 'required|integer|digits=5',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Falha de validação ao criar locação', [
                'errors'     => $e->errors(),
                'request'    => $request->all(),
            ]);
            throw $e;
        }

        // DB::beginTransaction();
        

        try 
        {
            $itemPedido = DB::transaction
            (
                function () use ($validatedData) 
                {
                    // 3. VERIFICAÇÃO DE ESTOQUE
                    $produto = ProdutoConsumivel::find($validatedData['produtos_consumivel_id']);
                    
                    if ($produto->quantidade < $validatedData['quantidade']) {
                        // Se não há estoque suficiente, lança uma exceção.
                        // Isso irá automaticamente disparar o ROLLBACK da transação.
                        throw new \Exception('Estoque insuficiente para o produto: ' . $produto->nome);
                    }

                    $item = ProdutoPedidoConsumivel::updateOrCreate
                    (
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
                }
            );

                            /*            // $locacao = Locacao::create([
                                        //     'nome'                  => $request->cliente['nome'],
                                        //     'contato'               => $request->cliente['contato'] ?? '',
                                        //     'usuario_id'            => $usuarioId,
                                        //     'data_locacao'          => $request->cliente['data'],
                                        //     'data_prevista_retorno' => $request->cliente['dataRetorno'],
                                        //     'status'                => 0,
                                        // ]);

                                        foreach ($request->itens as $item) {
                                            $produtoId     = (int) $item['id'];
                                            $qtdSolicitada = (int) $item['qtdParaLocacao'];

                                            $produto = ProdutoDecoracao::findOrFail($produtoId);

                                            LocacaoItem::create([
                                                'produto_decoracao_id' => $produtoId,
                                                'locacao_id'           => $locacao->locacao_id,
                                                'qtd_alugada'          => $qtdSolicitada,
                                                'qtd_devolvida'        => 0,
                                                'qtd_avariada'         => 0,
                                            ]);

                                            Log::info("Item adicionado à locação", [
                                                'produto_id' => $produtoId,
                                                'qtd_solicitada' => $qtdSolicitada,
                                                'qtd_disponivel_atual' => $produto->qtd_disponivel
                                            ]);
                                        }

                                        DB::commit(); */

            return response()->json([
                'mensagem' => 'Item adicionado ao pedido com sucesso!',
                'item' => $itemPedido
            ], 201); // 201 Created

        } catch (\Throwable $e) {
            // Se qualquer exceção foi lançada, a transação já foi desfeita (ROLLBACK).
            Log::error('Erro ao adicionar item ao pedido: ' . $e->getMessage());

            // Retorna o erro específico (ex: "Estoque insuficiente...") ou uma mensagem genérica
            return response()->json(['erro' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ProdutoPedidoConsumivel $produtoPedidoConsumivel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProdutoPedidoConsumivel $produtoPedidoConsumivel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProdutoPedidoConsumivel $produtoPedidoConsumivel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProdutoPedidoConsumivel $produtoPedidoConsumivel)
    {
        //
    }
}
