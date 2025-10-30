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
    public function update(Request $request, int $id)
    {
        try {
            $validated = $request->validate([
                'pedido_consumivel_id'   => 'sometimes|integer|exists:pedido_consumivel,pedido_consumivel_id',
                'produtos_consumivel_id' => 'sometimes|integer|exists:produtos_consumivel,produtos_consumivel_id',
                'quantidade'             => 'required|integer|min:1',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Falha de validação ao atualizar item do pedido', [
                'errors'  => $e->errors(),
                'request' => $request->all(),
            ]);
            throw $e;
        }

        try {
            $itemAtualizado = DB::transaction(function () use ($id, $validated) {
                // Bloqueia o registro para evitar condições de corrida
                $item = ProdutoPedidoConsumivel::lockForUpdate()->findOrFail($id);

                $novoPedidoId  = $validated['pedido_consumivel_id']   ?? $item->pedido_consumivel_id;
                $novoProdutoId = $validated['produtos_consumivel_id'] ?? $item->produtos_consumivel_id;
                $novaQtd       = $validated['quantidade']             ?? $item->quantidade;

                // Se o produto foi alterado, devolve estoque do antigo e desconta do novo
                if ((int) $novoProdutoId !== (int) $item->produtos_consumivel_id) {
                    // Devolve a quantidade antiga ao produto anterior
                    $produtoAntigo = ProdutoConsumivel::lockForUpdate()->findOrFail($item->produtos_consumivel_id);
                    $produtoAntigo->quantidade += (int) $item->quantidade;
                    $produtoAntigo->save();

                    // Desconta a nova quantidade do novo produto
                    $produtoNovo = ProdutoConsumivel::lockForUpdate()->findOrFail($novoProdutoId);
                    if ($produtoNovo->quantidade < $novaQtd) {
                        throw new \Exception('Estoque insuficiente para o novo produto selecionado.');
                    }
                    $produtoNovo->quantidade -= $novaQtd;
                    $produtoNovo->save();

                    // Atualiza o item do pedido
                    $item->pedido_consumivel_id   = $novoPedidoId;
                    $item->produtos_consumivel_id = $novoProdutoId;
                    $item->quantidade             = $novaQtd;
                    $item->save();
                } else {
                    // Mesmo produto: ajusta estoque apenas pela diferença
                    $produto = ProdutoConsumivel::lockForUpdate()->findOrFail($novoProdutoId);
                    $delta = (int) $novaQtd - (int) $item->quantidade;

                    if ($delta > 0) {
                        if ($produto->quantidade < $delta) {
                            throw new \Exception('Estoque insuficiente para aumentar a quantidade.');
                        }
                        $produto->quantidade -= $delta;
                        $produto->save();
                    } elseif ($delta < 0) {
                        $produto->quantidade += abs($delta);
                        $produto->save();
                    }

                    // Atualiza o item do pedido
                    $item->pedido_consumivel_id = $novoPedidoId;
                    $item->quantidade           = $novaQtd;
                    $item->save();
                }

                return $item->fresh();
            });

            return response()->json([
                'mensagem' => 'Item do pedido atualizado com sucesso!',
                'item'     => $itemAtualizado,
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Erro ao atualizar item do pedido: ' . $e->getMessage(), [
                'stack' => $e->getTraceAsString(),
            ]);

            return response()->json(['erro' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try {
            DB::transaction(function () use ($id) {
                // Bloqueia o registro para evitar condições de corrida
                $item = ProdutoPedidoConsumivel::lockForUpdate()->findOrFail($id);

                // Devolve a quantidade ao estoque do produto
                $produto = ProdutoConsumivel::lockForUpdate()->findOrFail($item->produtos_consumivel_id);
                $produto->quantidade += (int) $item->quantidade;
                $produto->save();

                // Deleta o item do pedido
                $item->delete();

                Log::info('Item do pedido deletado e estoque devolvido', [
                    'item_id' => $id,
                    'produto_id' => $item->produtos_consumivel_id,
                    'quantidade_devolvida' => $item->quantidade,
                ]);
            });

            return response()->json([
                'mensagem' => 'Item removido do pedido e estoque atualizado com sucesso!',
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning('Item do pedido não encontrado para deleção', ['id' => $id]);
            return response()->json(['erro' => 'Item não encontrado.'], 404);
        } catch (\Throwable $e) {
            Log::error('Erro ao deletar item do pedido: ' . $e->getMessage(), [
                'stack' => $e->getTraceAsString(),
            ]);
            return response()->json(['erro' => $e->getMessage()], 500);
        }
    }
}
