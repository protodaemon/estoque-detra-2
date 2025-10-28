<?php

namespace App\Http\Controllers;

use App\Models\PedidoConsumivel;
use App\Models\ProdutoConsumivel;
use App\Models\ProdutoPedidoConsumivel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PedidoConsumivelController extends Controller
{
    public function index(Request $request)
    {
        $query = PedidoConsumivel::with(['itens.produto']);
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



    public function update(Request $request, $id)
    {
        $pedido = PedidoConsumivel::findOrFail($id);

        $request->validate([
            'status'            => 'nullable|string|max:12|in:pendente,aprovado,cancelado,concluído',
            'data_alteracao'    => 'nullable|date',
            'observacoes'       => 'nullable|string|max:500'
        ]);

        if ($request->has('status')) {
            $pedido->status = $request->status;
        }

        // Inserindo a data atualização com a data que a alteração foi feita
        $pedido->data_alteracao = now();

        if ($request->has('observacoes')) {
            $pedido->observacoes = $request->observacoes;
        }

        $pedido->save();

        return response()->json([
            'mensagem' => 'Observação atualizada com sucesso',
            'pedido_consumivel_id' => $pedido->pedido_consumivel_id
        ], 200);
    }

    public function delete($id)
    {
        $pedido = PedidoConsumivel::findOrFail($id);

        $pedido->data_cancelamento = now();

        return response()->json([
            'mensagem' => 'Pedido excluído com sucesso.'
        ], 200);
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
}
