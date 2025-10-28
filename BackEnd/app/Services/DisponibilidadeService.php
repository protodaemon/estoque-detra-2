<?php

namespace App\Services;

use App\Models\ProdutoDecoracao;
use App\Models\LocacaoItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Serviço responsável por calcular a disponibilidade real dos produtos
 * considerando locações ativas no período especificado
 * 
 * IMPORTANTE: Este serviço NÃO altera o estoque físico dos produtos.
 * Ele apenas CALCULA quantos itens estão disponíveis para locação
 * baseado nas locações existentes.
 */
class DisponibilidadeService
{
    /**
     * Calcula disponibilidade para TODOS os produtos em um período específico
     * 
     * @param string $dataInicio - Data de início do período
     * @param string $dataFim - Data de fim do período
     * @return array - Array com disponibilidade de todos os produtos
     */
    public function verificarDisponibilidadePorPeriodo($dataInicio, $dataFim)
    {
        // Busca TODOS os produtos de decoração
        $produtos = ProdutoDecoracao::all();
        $resultado = [];

        // Processa cada produto individualmente
        foreach ($produtos as $produto) {
            // Pega a quantidade disponível original do produto (estoque físico)
            $qtdDisponivelOriginal = $produto->qtd_disponivel ?? 0;

            // Pega a quantidade danificada do produto
            $qtdDanificado = $produto->qtd_danificado ?? 0;

            // AQUI É A MÁGICA: Calcula quantos itens estão "em uso" no período
            // Busca locações que:
            // - Status 0 (pendente) ou 1 (parcialmente devolvida) - ATIVAS
            // - Status 2 (finalizada) NÃO entra na conta
            $emUso = LocacaoItem::where('produto_decoracao_id', $produto->produtos_decoracao_id)
                ->whereHas('locacao', function ($q) use ($dataInicio, $dataFim) {
                    // Apenas locações ATIVAS (0 = pendente, 1 = parcial)
                    // Status 2 (finalizada) não conta porque já foi devolvida
                    $q->whereIn('status', [0, 1])
                        ->where(function ($query) use ($dataInicio, $dataFim) {
                            // Locações que se sobrepõem ao período consultado:

                            // 1. Locação inicia no período
                            $query->whereBetween('data_locacao', [$dataInicio, $dataFim])
                                // 2. OU locação termina no período
                                ->orWhereBetween('data_prevista_retorno', [$dataInicio, $dataFim])
                                // 3. OU locação engloba todo o período (inicia antes e termina depois)
                                ->orWhere(function ($q) use ($dataInicio, $dataFim) {
                                    $q->where('data_locacao', '<=', $dataInicio)
                                        ->where('data_prevista_retorno', '>=', $dataFim);
                                });
                        });
                })
                ->sum('qtd_alugada'); // Soma todas as quantidades alugadas no período

            // Calcula quantos itens estão realmente utilizáveis
            // (total - danificados = utilizáveis)
            $qtdUtilizavel = max(0, $qtdDisponivelOriginal - $qtdDanificado);

            // Calcula quantos estão DISPONÍVEIS para nova locação
            // (utilizáveis - em uso = disponível)
            $disponivel = max(0, $qtdUtilizavel - $emUso);

            // Monta o resultado para este produto
            $resultado[] = [
                'produtos_decoracao_id' => $produto->produtos_decoracao_id,
                'id'                    => $produto->produtos_decoracao_id,
                'nome'                  => $produto->nome,
                'foto'                  => $produto->foto,
                'categoria_id'          => $produto->categoria_decoracao ?? null,
                'valor_locacao'         => $produto->valor_locacao,
                'qtd_total'             => $qtdDisponivelOriginal,  // Estoque físico original
                'qtd_disponivel'        => $disponivel,             // Disponível para locação
                'qtd_emprenho'          => $emUso,                  // Em uso (locado)
                'qtd_danificado'        => $qtdDanificado,          // Danificado (não utilizável)
            ];

            // Log para debug/acompanhamento
            Log::debug('Produto disponibilidade', [
                'produto_id' => $produto->produtos_decoracao_id,
                'nome'       => $produto->nome,
                'qtd_total'  => $qtdDisponivelOriginal,
                'qtd_danificado' => $qtdDanificado,
                'qtd_emprenho' => $emUso,
                'qtd_disponivel' => $disponivel,
            ]);
        }

        return $resultado;
    }

    /**
     * Versão OTIMIZADA para calcular disponibilidade apenas de produtos específicos
     * Mais eficiente quando você já sabe quais produtos quer consultar
     * 
     * @param array $produtoIds - Array com IDs dos produtos a consultar
     * @param string $dataInicio - Data de início do período
     * @param string $dataFim - Data de fim do período
     * @return array - Array associativo com disponibilidade (chave = produto_id)
     */
    public function verificarDisponibilidadePorIds($produtoIds, $dataInicio, $dataFim)
    {
        // Se não passou nenhum ID, retorna vazio
        if (empty($produtoIds)) {
            return [];
        }

        // Busca apenas os produtos especificados
        $produtos = ProdutoDecoracao::whereIn('produtos_decoracao_id', $produtoIds)->get();
        $resultado = [];

        // OTIMIZAÇÃO: Faz uma única query para buscar todas as locações do período
        // Agrupa por produto_id e já soma as quantidades
        $locacoesItens = LocacaoItem::whereIn('produto_decoracao_id', $produtoIds)
            ->whereHas('locacao', function ($q) use ($dataInicio, $dataFim) {
                // Mesma lógica de filtro das locações ativas
                $q->whereIn('status', [0, 1]) // Apenas pendentes ou parciais
                    ->where(function ($query) use ($dataInicio, $dataFim) {
                        // Mesma lógica de sobreposição de datas
                        $query->whereBetween('data_locacao', [$dataInicio, $dataFim])
                            ->orWhereBetween('data_prevista_retorno', [$dataInicio, $dataFim])
                            ->orWhere(function ($q) use ($dataInicio, $dataFim) {
                                $q->where('data_locacao', '<=', $dataInicio)
                                    ->where('data_prevista_retorno', '>=', $dataFim);
                            });
                    });
            })
            ->select('produto_decoracao_id', DB::raw('SUM(qtd_alugada) as total_em_uso'))
            ->groupBy('produto_decoracao_id')
            ->pluck('total_em_uso', 'produto_decoracao_id'); // Retorna array [produto_id => total_em_uso]

        // Processa cada produto
        foreach ($produtos as $produto) {
            // Dados base do produto
            $qtdDisponivelOriginal = $produto->qtd_disponivel ?? 0;
            $qtdDanificado = $produto->qtd_danificado ?? 0;

            // Pega a quantidade em uso deste produto (já calculada na query acima)
            $emUso = $locacoesItens[$produto->produtos_decoracao_id] ?? 0;

            // Calcula disponibilidade
            $qtdUtilizavel = max(0, $qtdDisponivelOriginal - $qtdDanificado);
            $disponivel = max(0, $qtdUtilizavel - $emUso);

            // Resultado indexado por produto_id para acesso rápido
            $resultado[$produto->produtos_decoracao_id] = [
                'produtos_decoracao_id' => $produto->produtos_decoracao_id,
                'id'                    => $produto->produtos_decoracao_id,
                'nome'                  => $produto->nome,
                'foto'                  => $produto->foto,
                'categoria_id'          => $produto->categoria_decoracao ?? null,
                'valor_locacao'         => $produto->valor_locacao,
                'qtd_total'             => $qtdDisponivelOriginal,  // Estoque físico
                'qtd_disponivel'        => $disponivel,             // Disponível para locação
                'qtd_emprenho'          => $emUso,                  // Em uso (comprometido)
                'qtd_danificado'        => $qtdDanificado,          // Danificado
            ];

            // Log para debug
            Log::debug('Produto disponibilidade', [
                'produto_id' => $produto->produtos_decoracao_id,
                'nome'       => $produto->nome,
                'qtd_total'  => $qtdDisponivelOriginal,
                'qtd_danificado' => $qtdDanificado,
                'qtd_emprenho' => $emUso,
                'qtd_disponivel' => $disponivel,
            ]);
        }

        return $resultado;
    }
}

/*
NOVA LÓGICA COM SOBREPOSIÇÃO DE PERÍODOS:

1. 🔍 ANALISA: Verifica locações com status 0 OU 1 que se sobrepõem ao período consultado
2. 📊 CALCULA: quantidade_disponível = estoque_físico - danificados - em_uso_sobreposto
3. 🚫 NÃO ALTERA: Nunca modifica o estoque físico dos produtos
4. ✅ RETORNA: Quantos itens estão livres considerando sobreposições

EXEMPLO PRÁTICO:
- Produto: Cadeira (100 unidades, 5 danificadas)
- Nova locação: 10/01 a 15/01
- Locação existente (status 0): 12/01 a 20/01 (10 cadeiras) ← SOBREPÕE!
- Locação existente (status 2): 05/01 a 25/01 (20 cadeiras) ← NÃO CONTA!
- RESULTADO: 85 unidades disponíveis (100 - 5 - 10 = 85)

STATUS DAS LOCAÇÕES:
- Status 0 (pendente): Conta se houver sobreposição de período
- Status 1 (parcial): Conta se houver sobreposição de período
- Status 2 (finalizada): NUNCA conta

SOBREPOSIÇÃO DE PERÍODOS (usando Carbon):
Duas locações se sobrepõem se:
- Locação existente INICIA <= Nova locação TERMINA
- E locação existente TERMINA >= Nova locação INICIA

EXEMPLOS DE SOBREPOSIÇÃO:
✅ Nova: 10/01 a 15/01 | Existente: 12/01 a 20/01 (sobrepõe)
✅ Nova: 10/01 a 15/01 | Existente: 05/01 a 12/01 (sobrepõe)
✅ Nova: 10/01 a 15/01 | Existente: 08/01 a 18/01 (sobrepõe totalmente)
❌ Nova: 10/01 a 15/01 | Existente: 16/01 a 20/01 (não sobrepõe)
❌ Nova: 10/01 a 15/01 | Existente: 01/01 a 09/01 (não sobrepõe)
*/