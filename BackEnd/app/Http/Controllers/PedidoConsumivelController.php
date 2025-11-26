<?php

namespace App\Http\Controllers;

use App\Models\PedidoConsumivel;
use App\Models\ProdutoConsumivel;
use App\Models\ProdutoPedidoConsumivel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class PedidoConsumivelController extends Controller
{
    public function index(Request $request)
    {
        $query = PedidoConsumivel::with(['itens.produto', 'usuario']);
        $perPage = $request->input('per_page', 12);
        
        $pedidos = $query->orderBy('pedido_consumivel_id')
                         ->paginate($perPage);

        return response()->json([
            'data' => $pedidos->items(),
            'total' => $pedidos->total(),
            'current_page' => $pedidos->currentPage(),
            'per_page' => $pedidos->perPage()
        ]);
    }
    

/*     public function store(Request $request) // funcao store antiga
    {
        $request->validate([
            'observacao'       => 'required|string|max:500'
        ]);

        $pedido = PedidoConsumivel::create([
            'status'            => 'pendente',
            'data_pedido'       => now(),
            'observacoes'       => $request->observacao    
        ]);

        Log::info($request->observacao);

        return response()->json([
            'mensagem' => 'Pedido criado com sucesso!',
            'pedido_consumivel_id' => $pedido->pedido_consumivel_id
        ], 201);
        
    } */


    public function criarPedidoCompleto(Request $request)
    {
        // Validação
        $validator = Validator::make($request->all(), [
            'observacao' => 'nullable|string|max:500',
            'itens' => 'required|array|min:1',
            'itens.*.id' => 'required|integer|exists:produtos_consumivel,produtos_consumivel_id',
            'itens.*.quantidade' => 'required|integer|min:1|max:99999',
            // 'itens.*.valor_locacao' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'erro' => 'Dados inválidos',
                'detalhes' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // 1. CRIAR O PEDIDO
            $pedido = PedidoConsumivel::create([
                'status' => 'pendente',
                'data_pedido' => now(),
                'usuario_id' => Auth::id(),
                'observacoes' => $request->observacao ?? null
            ]);

            Log::info('Rã ', ['pedido' => $pedido->toArray()]);

            // ✅ GARANTIR que o ID foi gerado
            $pedidoId = $pedido->pedido_consumivel_id;
            
            if (!$pedidoId) {
                throw new \Exception('Falha ao criar pedido - ID não gerado');
            }

            Log::info('Pedido criado', ['pedido_id' => $pedidoId]);

            // 2. PROCESSAR ITENS
            $itensCriados = [];
            foreach ($request->itens as $index => $itemData) {
                $produtoId = (int) $itemData['id'];
                $quantidade = (int) $itemData['quantidade'];

                // Buscar produto
                $produto = ProdutoConsumivel::find($produtoId);
                if (!$produto) {
                    throw new \Exception("Produto ID {$produtoId} não encontrado");
                }

                Log::info('Estoque verificado', [
                    'produto_id' => $produtoId,
                    'estoque_disponivel' => $produto->quantidade,
                    'quantidade_solicitada' => $quantidade
                ]);


                // ✅ VERIFICAR ESTOQUE
                if ($produto->quantidade < $quantidade) {
                    throw new \Exception("Estoque insuficiente para '{$produto->nome}'. Disponível: {$produto->quantidade}, Solicitado: {$quantidade}");
                }


                // Criar item do pedido
                $itemPedido = ProdutoPedidoConsumivel::create([
                    'pedido_consumivel_id' => $pedidoId,
                    'produtos_consumivel_id' => $produtoId,
                    'quantidade' => $quantidade
                ]);

                // ✅ DEDUZIR ESTOQUE
                $produto->decrement('quantidade', $quantidade);

                $itensCriados[] = [
                    'produto_id' => $produtoId,
                    'quantidade' => $quantidade,
                    'item_id' => $itemPedido->produtos_pedido_consumivel_id
                ];

                Log::info('Item adicionado', [
                    'pedido_id' => $pedidoId,
                    'produto_id' => $produtoId,
                    'quantidade' => $quantidade
                ]);
            }

            DB::commit();

            // ✅ RESPOSTA COMPLETA COM ID
            return response()->json([
                'mensagem' => 'Pedido criado com sucesso!',
                'pedido_consumivel_id' => $pedidoId, // ✅ CHAVE CORRETA
                'total_itens' => count($request->itens),
                'itens_criados' => $itensCriados
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao criar pedido completo', [
                'erro' => $e->getMessage(),
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'erro' => $e->getMessage()
            ], 500);
        }
    }



    public function update(Request $request, int $id)
    {
        try {
            $validated = $request->validate([
                'observacao' => 'nullable|string|max:500',
                'itens' => 'nullable|array',
                'itens.*.id' => 'required_with:itens|integer|exists:produtos_consumivel,produtos_consumivel_id',
                'itens.*.quantidade' => 'required_with:itens|integer|min:1',
                'itens_deletados' => 'nullable|array',
                'itens_deletados.*' => 'integer|exists:produtos_pedido_consumivel,produtos_pedido_consumivel_id',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Falha de validação ao atualizar pedido', [
                'errors' => $e->errors(),
                'request' => $request->all(),
            ]);
            return response()->json([
                'erro' => 'Dados inválidos.',
                'detalhes' => $e->errors(),
            ], 422);
        }

        try {
            $resultado = DB::transaction(function () use ($id, $validated) {
                $pedido = PedidoConsumivel::lockForUpdate()->findOrFail($id);

                // Remoção de itens marcados para exclusão (devolve estoque)
                if (!empty($validated['itens_deletados'])) {
                    foreach ($validated['itens_deletados'] as $itemId) {
                        $item = ProdutoPedidoConsumivel::lockForUpdate()->find($itemId);
                        if ($item && $item->pedido_consumivel_id == $id) {
                            $produto = ProdutoConsumivel::lockForUpdate()->findOrFail($item->produtos_consumivel_id);
                            $produto->quantidade += (int) $item->quantidade;
                            $produto->save();
                            $item->delete();
                            Log::info('Item deletado ao atualizar pedido', ['item_id' => $itemId]);
                        }
                    }
                }

                // Itens atuais após exclusões
                $itensAtuais = ProdutoPedidoConsumivel::where('pedido_consumivel_id', $id)->get();
                $itensAtuaisMap = $itensAtuais->keyBy('produtos_consumivel_id');

                // Se nenhum item novo foi enviado e não sobraram itens, sinaliza exclusão
                $itensNovos = $validated['itens'] ?? [];
                if ($itensAtuaisMap->isEmpty() && empty($itensNovos)) {
                    Log::info('Pedido sem itens após edição - será excluído', ['pedido_id' => $id]);
                    return ['tipo' => 'excluir'];
                }

                // Atualiza observações
                if (isset($validated['observacao'])) {
                    $pedido->observacoes = $validated['observacao'];
                    $pedido->save();
                }

                // Processa itens novos/alterados
                foreach ($itensNovos as $itemData) {
                    $produtoId = (int) $itemData['id'];
                    $qtdNova = (int) $itemData['quantidade'];

                    if ($itensAtuaisMap->has($produtoId)) {
                        $itemExistente = $itensAtuaisMap->get($produtoId);
                        $produto = ProdutoConsumivel::lockForUpdate()->findOrFail($produtoId);
                        $delta = $qtdNova - (int) $itemExistente->quantidade;

                        if ($delta > 0) {
                            if ($produto->quantidade < $delta) {
                                throw new \Exception("Estoque insuficiente para {$produto->nome}.");
                            }
                            $produto->quantidade -= $delta;
                        } elseif ($delta < 0) {
                            $produto->quantidade += abs($delta);
                        }
                        $produto->save();

                        $itemExistente->quantidade = $qtdNova;
                        $itemExistente->save();
                    } else {
                        $produto = ProdutoConsumivel::lockForUpdate()->findOrFail($produtoId);
                        if ($produto->quantidade < $qtdNova) {
                            throw new \Exception("Estoque insuficiente para {$produto->nome}.");
                        }

                        ProdutoPedidoConsumivel::create([
                            'pedido_consumivel_id' => $id,
                            'produtos_consumivel_id' => $produtoId,
                            'quantidade' => $qtdNova,
                        ]);

                        $produto->quantidade -= $qtdNova;
                        $produto->save();
                    }
                }

                return [
                    'tipo' => 'atualizado',
                    'pedido' => $pedido->fresh()->load('itens.produto')
                ];
            });

            // Se ficou sem itens, usa a função delete do controller
            if ($resultado['tipo'] === 'excluir') {
                $obs = $validated['observacao'] ?? 'Cancelado ao remover todos os itens na edição';
                return $this->delete($id, $obs);
            }

            return response()->json([
                'mensagem' => 'Pedido atualizado com sucesso!',
                'pedido_excluido' => false,
                'pedido_consumivel_id' => $resultado['pedido']->pedido_consumivel_id,
                'pedido' => $resultado['pedido'],
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['erro' => 'Pedido não encontrado.'], 404);
        } catch (\Throwable $e) {
            Log::error('Erro ao atualizar pedido: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            return response()->json(['erro' => 'Falha ao atualizar pedido.'], 500);
        }
    }

    public function delete($id, $observacao_cancelamento = null)
    {
        try {
            DB::transaction(function () use ($id, $observacao_cancelamento) {
                $pedido = PedidoConsumivel::lockForUpdate()->findOrFail($id);

                // Devolve estoque e (opcionalmente) remove itens
                $itens = ProdutoPedidoConsumivel::where('pedido_consumivel_id', $id)
                    ->lockForUpdate()->get();

                foreach ($itens as $item) {
                    $produto = ProdutoConsumivel::lockForUpdate()->findOrFail($item->produtos_consumivel_id);
                    $produto->quantidade += (int) $item->quantidade;
                    $produto->save();

                    // Se preferir manter histórico dos itens no pedido cancelado, deixe comentado:
                    // $item->delete();
                }

                // Marca como cancelado
                $pedido->data_cancelamento = now();
                $pedido->status = 'cancelado';
                $pedido->observacao_cancelamento = $observacao_cancelamento;
                $pedido->save();
            });

            return response()->json([
                'mensagem' => 'Pedido excluído com sucesso!',
                'pedido_excluido' => true,
                'pedido_consumivel_id' => (int) $id,
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['erro' => 'Pedido não encontrado.'], 404);
        } catch (\Throwable $e) {
            Log::error('Erro ao excluir pedido: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            return response()->json(['erro' => 'Falha ao excluir pedido.'], 500);
        }
    }

    public function iniciarPedido(Request $request)
    {
        try {
            DB::beginTransaction();
            
            $pedido = PedidoConsumivel::create([
                'status' => 'pendente',
                'data_pedido' => now(),
                'observacoes' => $request->observacoes ?? null
            ]);

            DB::commit();

            return response()->json([
                'mensagem' => 'Pedido iniciado com sucesso!',
                'pedido' => [
                    'id' => $pedido->pedido_consumivel_id,
                    'data_pedido' => $pedido->data_pedido,
                    'status' => $pedido->status
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'mensagem' => 'Erro ao iniciar pedido',
                'erro' => $e->getMessage()
            ], 500);
        }
    }

    public function finalizarPedido(Request $request)
    {
        $request->validate([
            'pedido_id' => 'required|exists:pedido_consumivel,pedido_consumivel_id',
            'itens' => 'required|array|min:1',
            'itens.*.id' => 'required|exists:produtos_consumivel,produtos_consumivel_id',
            'itens.*.quantidade' => 'required|integer|min:1',
            'valor_total' => 'required|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();

            $pedido = PedidoConsumivel::findOrFail($request->pedido_id);
            
            if ($pedido->status !== 'em_criacao') {
                throw new \Exception('Este pedido não pode mais ser modificado');
            }

            foreach ($request->itens as $item) {
                // Criar item do pedido
                ProdutoPedidoConsumivel::create([
                    'pedido_consumivel_id' => $pedido->pedido_consumivel_id,
                    'produtos_consumivel_id' => $item['id'],
                    'quantidade' => $item['quantidade'],
                    'valor_unitario' => $item['valor_locacao'] ?? 0
                ]);
            }

            // Atualizar status do pedido
            $pedido->status = 'pendente';
            $pedido->save();

            DB::commit();

            return response()->json([
                'mensagem' => 'Pedido finalizado com sucesso!',
                'pedido_id' => $pedido->pedido_consumivel_id
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'mensagem' => 'Erro ao finalizar pedido',
                'erro' => $e->getMessage()
            ], 500);
        }
    }

    public function show(int $id)
    {
        try {
            $pedido = PedidoConsumivel::with(['itens.produto'])->findOrFail($id);
            return response()->json(['data' => $pedido], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning('Pedido não encontrado', ['id' => $id]);
            return response()->json(['erro' => 'Pedido não encontrado.'], 404);
        } catch (\Throwable $e) {
            Log::error('Erro ao recuperar pedido: ' . $e->getMessage(), [
                'stack' => $e->getTraceAsString(),
            ]);
            return response()->json(['erro' => 'Falha ao recuperar pedido.'], 500);
        }
    }

    public function confirmar(int $id)
    {
        try {
            $pedido = PedidoConsumivel::findOrFail($id);
            
            if ($pedido->status === 'concluido') {
                return response()->json([
                    'erro' => 'Pedido já está concluído.'
                ], 400);
            }

            if ($pedido->status === 'cancelado') {
                return response()->json([
                    'erro' => 'Não é possível confirmar um pedido cancelado.'
                ], 400);
            }

            $pedido->status = 'concluido';
            $pedido->data_confirmacao = now();
            $pedido->save();

            return response()->json([
                'mensagem' => 'Pedido confirmado com sucesso!',
                'pedido' => $pedido->fresh()
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['erro' => 'Pedido não encontrado.'], 404);
        } catch (\Throwable $e) {
            Log::error('Erro ao confirmar pedido: ' . $e->getMessage(), [
                'stack' => $e->getTraceAsString()
            ]);
            return response()->json(['erro' => 'Falha ao confirmar pedido.'], 500);
        }
    }
}
