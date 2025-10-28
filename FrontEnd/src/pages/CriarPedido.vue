<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100">
    <!-- Modal para criação de evento -->
    <ModalParaCriacaoDeEvento
      :mostrar="mostrarModalEvento"
      :dados="dadosEvento"
      @confirmar="confirmarEvento"
      @cancelar="cancelarEvento"
    />
    
    <!-- Modal de histórico de pedidos -->
    <ModalHistoricoPedidos
      :mostrar="mostrarModalHistorico"
      :pedidos="pedidos"
      @fechar="mostrarModalHistorico = false"
      @selecionar-pedido="onSelecionarPedido"
    />

    <!-- Modal de resultado da locação -->
    <ModalResultadoLocacao 
      :mostrar="modalResultado.mostrar" 
      :tipo="modalResultado.tipo" 
      :titulo="modalResultado.titulo"
      :mensagem="modalResultado.mensagem" 
      :detalhes="modalResultado.detalhes"
      :mostrarBotaoSecundario="modalResultado.tipo === 'erro'"
      :textoBotaoPrincipal="modalResultado.tipo === 'sucesso' ? 'Nova Locação' : 'OK'"
      :textoBotaoSecundario="'Cancelar'" @confirmar="confirmarModalResultado" @acao-secundaria="tentarNovamente"
      @voltar-menu="this.$router.push('/consumivel')" 
    />

    <div class="bg-white shadow-sm sticky top-0 z-20">
      <div class="max-w-7xl mx-auto px-6 py-4">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-4">
            <button @click="$router.go(-1)" class="text-gray-500 hover:text-gray-700 transition-colors">
              <ArrowLeft class="w-5 h-5" />
            </button>
            <div>
              <h1 class="text-2xl font-bold text-gray-800">Criar Lista de Locação</h1>
              <div class="flex items-center text-sm text-gray-600 mt-1 space-x-2">
                <span class="text-gray-400">Menu</span>
                <ChevronRight class="w-4 h-4" />
                <span class="text-blue-600 font-medium">Criação Listas</span>
              </div>
            </div>
          </div>

            <!-- NOVO BOTÃO À DIREITA -->
          <div class="flex items-center">
            <button
              @click="abrirHistorico"
              class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-colors mr-3"
              title="consultar-pedidos">
              Consultar Pedidos
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="p-6">

      <div class="bg-white shadow-lg rounded-2xl p-6 mb-6 border border-gray-200">
        <div class="flex justify-between items-start gap-4">
          <div class="flex gap-4 text-gray-600 flex-1 max-w-3xl">
            <!-- Campo ID à esquerda com largura dinâmica -->
            <div v-if="pedidoSelecionadoId !== null" class="flex-shrink-0">
              <label class="block text-sm font-medium text-gray-700 mb-2">
                ID
              </label>
              <input
                :value="pedidoSelecionadoId"
                type="text"
                readonly
                :style="{ width: `${Math.max(60, String(pedidoSelecionadoId).length * 12 + 40)}px` }"
                class="font-mono py-2 px-4 border-2 border-gray-200 rounded-lg bg-gray-50 text-gray-700 transition-all text-center"
              />
            </div>

            <!-- Campo Observações com largura flexível -->
            <div class="flex-1 min-w-0">
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Observações (opcional)
              </label>
              <input
                v-model="observacoes"
                type="text"
                placeholder="Ex: Fornecedor X, lote Y..."
                class="w-full py-2 px-4 border-2 border-gray-200 rounded-lg focus:border-blue-400 focus:ring-4 focus:ring-blue-100 transition-all"
              />
            </div>
          </div>

          <!-- Card de total de itens -->
          <div class="text-right bg-blue-50 p-4 rounded-xl flex-shrink-0">
            <div class="text-sm text-gray-600 mb-2">Total de itens: {{ itensLocacao.length }}</div>
            <div class="text-2xl font-bold text-blue-700">
              <!-- R$ {{ valorTotalLocacao.toFixed(2) }} -->
            </div>
          </div>
        </div>
      </div>

      <div class="flex-1 space-y-6">
        <div class="bg-white shadow-lg rounded-2xl p-4 border border-gray-200">
          <div class="relative">
            <input v-model="filtros.searchProduto" type="text" placeholder="Buscar produtos para locação..."
              class="w-full px-5 py-4 pl-12 border rounded-xl border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent text-sm transition-all" />
            <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none"
              stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
          </div>
        </div>

        <div
          class="bg-white border-4 border-dashed border-blue-300 rounded-2xl p-6 min-h-[300px] shadow-lg transition-all duration-300 border-gray-200"
          :class="{ 'border-blue-500 bg-blue-50 scale-102': isDragging }" @dragover.prevent="handleDragOver"
          @dragleave="handleDragLeave" @drop="onDrop">
          <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-blue-700">Itens Selecionados</h3>
            <span v-if="itensLocacao.length > 0"
              class="text-sm text-gray-600 bg-blue-100 px-4 py-2 rounded-full font-medium">
              {{ itensLocacao.length }} {{ itensLocacao.length === 1 ? 'item' : 'itens' }}
            </span>
          </div>

          <div v-if="itensLocacao.length === 0" class="text-center py-16">
            <div class="w-24 h-24 mx-auto mb-4 bg-blue-100 rounded-full flex items-center justify-center">
              <svg class="w-12 h-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                </path>
              </svg>
            </div>
            <p class="text-gray-500 text-xl mb-2 font-medium">Arraste os itens aqui</p>
            <p class="text-gray-400">ou clique duas vezes nos produtos abaixo</p>
          </div>

          <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <div v-for="(item, index) in itensLocacao" :key="item.uniqueId"
              class="bg-white shadow-lg hover:shadow-xl p-4 relative transition-all duration-300 border border-gray-200 hover:border-blue-300 rounded-2xl transform hover:-translate-y-1"
              :class="{ 'border-red-400 bg-red-50': (temErroQuantidade(item) && pedidoSelecionadoId == null) }">
              <div class="relative bg-gray-50 rounded-xl mb-4 overflow-hidden" style="height: 180px;">
                <template v-if="item.foto">
                  <img :src="item.foto" :alt="item.nome"
                    class="w-full h-full object-cover hover:scale-110 transition-transform duration-300" />
                </template>
                <template v-else>
                  <div class="w-full h-full flex items-center justify-center text-gray-400">
                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 002 2z">
                      </path>
                    </svg>
                  </div>
                </template>

                <div
                  class="absolute top-2 left-2 bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">
                  Disponível: {{ item.quantidade }}
                </div>

                <div v-if="temErroQuantidade(item) && pedidoSelecionadoId == null"
                  class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded-full text-xs font-bold">
                  ⚠️
                </div>
              </div>

              <div class="mb-4">
                <h3 class="text-sm font-bold text-blue-700 mb-2 leading-tight">{{ item.nome }}</h3>
                <!--                 <div class="text-lg font-bold text-green-600 mb-3">
                  R$ {{ item.valor_locacao }}
                </div> -->
              </div>

              <div class="space-y-2">
                <div class="flex gap-2 items-center">
                  <button @click="diminuirQuantidade(index)"
                    class="w-8 h-8 bg-gray-200 hover:bg-gray-300 rounded-lg text-sm font-bold text-gray-600 transition-colors flex items-center justify-center">
                    -
                  </button>
                  <input v-model.number="item.qtdParaLocacao" type="number" min="1" :max="item.quantidade"
                    class="flex-1 px-3 py-2 border rounded-lg text-sm text-center transition-all" :class="[
                      (temErroQuantidade(item) && pedidoSelecionadoId == null)
                        ? 'border-red-500 bg-red-50 focus:ring-red-300 focus:border-red-500'
                        : 'border-gray-300 focus:ring-blue-300 focus:border-transparent'
                    ]" @input="validarQuantidade(index)" @blur="corrigirQuantidadeSeNecessario(index)"
                    @keyup.enter="corrigirQuantidadeSeNecessario(index)" />
                  <button @click="aumentarQuantidade(index)" :disabled="item.qtdParaLocacao >= item.quantidade"
                    class="w-8 h-8 bg-blue-200 hover:bg-blue-300 disabled:bg-gray-200 disabled:text-gray-400 rounded-lg text-sm font-bold text-blue-700 transition-colors flex items-center justify-center">
                    
                  </button>
                </div>

                <div v-if="temErroQuantidade(item) && pedidoSelecionadoId == null" class="text-xs text-red-600 font-medium">
                  Máximo disponível: {{ item.quantidade }}
                </div>

                <div v-if="pedidoSelecionadoId == null" class="text-xs text-gray-500 text-center"> jamba
                  {{ item.qtdParaLocacao }} de {{ item.quantidade }} disponíveis
                </div>
              </div>

              <button @click="removerItem(index)"
                class="absolute -top-3 -right-3 w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-full text-sm font-bold shadow-lg transition-all duration-200 flex items-center justify-center hover:scale-110">
                ×
              </button>
            </div>
          </div>
        </div>

        <div class="bg-white shadow-lg rounded-2xl p-6 border border-gray-200">
          <h3 class="text-2xl font-bold text-blue-700 mb-6">Produtos Disponíveis</h3>

          <div v-if="carregando" class="text-center py-16">
            <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
            <p class="mt-4 text-gray-500">Carregando produtos...</p>
          </div>

          <div v-else-if="produtosFiltrados.length === 0" class="text-center py-16">
            <div class="w-24 h-24 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
              <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
              </svg>
            </div>
            <p class="text-gray-500 text-xl font-medium">Nenhum produto encontrado</p>
            <p class="text-gray-400">Tente ajustar os filtros de busca</p>
          </div>

          <div v-else>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
              <div v-for="peca in produtosFiltrados" :key="peca.id" :class="[
                'bg-white shadow-lg p-4 transition-all duration-300 border border-gray-200 rounded-2xl',
                peca.quantidade === 0
                  ? 'opacity-40 cursor-not-allowed grayscale'
                  : itemJaSelecionado(peca.id)
                    ? 'border-2 border-blue-500 bg-blue-50 opacity-60'
                    : 'cursor-move hover:shadow-xl hover:border-blue-300 hover:-translate-y-2 transform'
              ]" :draggable="peca.quantidade > 0 && !itemJaSelecionado(peca.id)"
                @dragstart="startDrag($event, peca)" @dblclick="adicionarItemDuplo(peca)">
                <div class="relative bg-gray-50 rounded-xl mb-4 overflow-hidden" style="height: 180px;">
                  <template v-if="peca.foto">
                    <img :src="peca.foto" :alt="peca.nome"
                      class="w-full h-full object-cover transition-transform duration-300"
                      :class="{ 'hover:scale-110': !itemJaSelecionado(peca.id) && peca.quantidade > 0 }" />
                  </template>
                  <template v-else>
                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                      <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 002 2z">
                        </path>
                      </svg>
                    </div>
                  </template>

                  <div class="absolute top-2 left-2 flex flex-col gap-1">
                    <div v-if="peca.quantidade > 0"
                      class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">
                      Estoque: {{ peca.quantidade }}
                    </div>
                    <div v-else class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-medium">
                      Indisponível
                    </div>
                    <div v-if="peca.qtd_danificado > 0"
                      class="bg-orange-100 text-orange-800 px-2 py-1 rounded-full text-xs font-medium">
                      Danificado: {{ peca.qtd_danificado }}
                    </div>
                    <div
                      class="bg-orange-100 text-orange-800 px-2 py-1 rounded-full text-xs font-medium">
                      ID: {{ peca.produtos_consumivel_id }}
                    </div>
                  </div>

                  <div v-if="itemJaSelecionado(peca.id)" class="absolute top-2 right-2">
                    <div class="bg-blue-500 text-white p-2 rounded-full">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                      </svg>
                    </div>
                  </div>
                </div>

                <div>
                  <h3 class="text-sm font-bold text-blue-700 mb-2 leading-tight">{{ peca.nome }}</h3>
                </div>
              </div>
            </div>

            <!-- Paginação -->
            <div v-if="totalPaginas > 1" class="flex justify-end mt-6">
              <Paginacao 
                :paginaAtual="paginaAtual" 
                :totalPaginas="totalPaginas"
                @atualizar-pagina="atualizarPagina" 
              />
            </div>
          </div>
        </div>

        <div class="">
          <div class="flex flex-col lg:flex-row justify-between items-center gap-4">
            <div class="flex gap-4">
              <!-- Botão Limpar Dados (só aparece quando há pedido selecionado) -->
              <button 
                v-if="pedidoSelecionadoId !== null"
                @click="limparDados"
                class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-8 py-4 rounded-xl shadow-lg transition-all duration-200 transform hover:scale-105 hover:shadow-xl flex items-center gap-2"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                  </path>
                </svg>
                Limpar Dados
              </button>

              <!-- Botão Salvar Pedido -->
              <button 
                v-if="pedidoSelecionadoId == null"
                @click="salvarPedido"
                :disabled="salvando" 
                :class="[
                  'bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-4 rounded-xl shadow-lg transition-all duration-200 transform hover:scale-105 hover:shadow-xl flex items-center gap-2',
                  salvando ? 'opacity-50 cursor-not-allowed' : ''
                ]"
              >
                <svg v-if="!salvando" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M5 13l4 4L19 7">
                  </path>
                </svg>
                <svg v-else class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                  </path>
                </svg>
                {{ salvando ? 'Salvando...' : 'Salvar Pedido' }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import Paginacao from '../components/Paginacao.vue'
import ModalParaCriacaoDeEvento from '../components/ModalParaCriacaoDeEvento.vue'
import ModalResultadoLocacao from '../components/ModalResultadoLocacao.vue'
import ModalHistoricoPedidos from '../components/ModalHistoricoPedidos.vue'
  
export default {
  components: {
    Paginacao,
    ModalParaCriacaoDeEvento,
    ModalResultadoLocacao,
    ModalHistoricoPedidos
  },
  data() {
    return {
      mostrarModalEvento: false,
      mostrarModalHistorico: false,
      dadosEvento: {},
      modalResultado: {
        mostrar: false,
        tipo: '',
        titulo: '',
        mensagem: '',
        detalhes: {}
      },
      pedidos: [],
      error: null,
      produtos: [],
      itensLocacao: [],
      filtros: {
        searchProduto: '',
      },
      // ID do pedido carregado via histórico (mostra o campo quando não for null)
      pedidoSelecionadoId: null,
      paginaAtual: 1,
      totalPaginas: 1,
      itensPorPagina: 8,
      isDragging: false,
      searchTimeout: null,
      carregando: false,
      salvando: false,
      observacoes: ''
    }
  },
  computed: {
    produtosFiltrados() {
      // Retorna os produtos já filtrados pelo servidor
      return Array.isArray(this.produtos) ? this.produtos : []
    },

    valorTotalLocacao() {
      return this.itensLocacao.reduce((total, item) => {
        return total + (parseFloat(item.valor_locacao) * item.qtdParaLocacao)
      }, 0)
    },

    temItensComErro() {
      return this.itensLocacao.some(item => this.temErroQuantidade(item))
    }
  },

  watch: {
    'filtros.searchProduto': {
      handler() {
        // Debounce para busca
        this.limparTimeouts()
        this.searchTimeout = setTimeout(() => {
          this.paginaAtual = 1 // Resetar para primeira página ao buscar
          this.carregarProdutos()
        }, 500) // Aguarda 500ms após usuário parar de digitar
      }
    }
  },

  methods: {
    // Verifica se um item tem erro de quantidade
    temErroQuantidade(item) {
      if (!item || !item.qtdParaLocacao || !item.quantidade) return false

      const quantidade = parseInt(item.qtdParaLocacao)
      const disponivel = parseInt(item.quantidade)

      return quantidade <= 0 ||
        quantidade > disponivel ||
        !Number.isInteger(quantidade) ||
        isNaN(quantidade)
    },

    // Gerenciamento do evento
    confirmarEvento(dados) {
      this.mostrarModalEvento = false
      this.eventoConfirmado = true
      this.carregarProdutos()
    },

    cancelarEvento() {
      this.mostrarModalEvento = false
      this.$router.push('/consumivel')
    },

    novoEvento() {
      this.dadosEvento = {
        nome: '',
        contato: '',
        evento: '',
        data: '',
        dataRetorno: null,
      }
      this.itensLocacao = []
      this.observacoes = ''
      this.pedidoSelecionadoId = null
      // this.mostrarModalEvento = true
      this.filtros = {
        searchProduto: '',
      }
    },

    // Drag and Drop
    startDrag(event, produto) {
      if (produto.quantidade === 0 || this.itemJaSelecionado(produto.id)) {
        event.preventDefault()
        return
      }
      event.dataTransfer.effectAllowed = 'move'
      event.dataTransfer.setData('application/json', JSON.stringify(produto))
    },

    handleDragOver(event) {
      event.preventDefault()
      this.isDragging = true
    },

    handleDragLeave(event) {
      if (!event.currentTarget.contains(event.relatedTarget)) {
        this.isDragging = false
      }
    },

    onDrop(event) {
      event.preventDefault()
      this.isDragging = false

      try {
        const data = event.dataTransfer.getData('application/json')
        if (!data) return

        const produto = JSON.parse(data)
        this.adicionarItem(produto)
      } catch (err) {
        console.error('Erro ao processar drop:', err)
      }
    },

    // Gerenciamento de itens
    adicionarItem(produto) {
      if (produto.quantidade === 0 || this.itemJaSelecionado(produto.id)) {
        return
      }

      const novoItem = {
        ...produto,
        uniqueId: Date.now() + Math.random(),
        qtdParaLocacao: 1
      }

      this.itensLocacao.push(novoItem)
      this.atualizarStatusProdutos()
    },

    adicionarItemDuplo(produto) {
      if (produto.quantidade > 0 && !this.itemJaSelecionado(produto.id)) {
        this.adicionarItem(produto)
      }
    },

    itemJaSelecionado(produtoId) {
      return this.itensLocacao.some(item =>
        parseInt(item.id) === parseInt(produtoId)
      )
    },

    removerItem(index) {
      this.itensLocacao.splice(index, 1)
      this.atualizarStatusProdutos()
    },

    aumentarQuantidade(index) {
      const item = this.itensLocacao[index]
      if (item && item.qtdParaLocacao < item.quantidade) {
        item.qtdParaLocacao++
        this.$forceUpdate()
      }
    },

    diminuirQuantidade(index) {
      const item = this.itensLocacao[index]
      if (item && item.qtdParaLocacao > 1) {
        item.qtdParaLocacao--
        this.$forceUpdate()
      }
    },

    // Valida a quantidade enquanto o usuário digita
    validarQuantidade(index) {
      const item = this.itensLocacao[index]
      if (!item) return

      let quantidade = item.qtdParaLocacao

      if (typeof quantidade === 'string') {
        quantidade = parseInt(quantidade) || 1
      }
      if (!Number.isInteger(quantidade) || isNaN(quantidade)) {
        quantidade = 1
      }
      if (quantidade < 1) {
        quantidade = 1
      }

      // substitui this.$set por splice para manter reatividade no Vue 3
      this.itensLocacao.splice(index, 1, { ...item, qtdParaLocacao: quantidade })
    },

    // Corrige a quantidade quando o campo perde o foco ou pressiona Enter
    corrigirQuantidadeSeNecessario(index) {
      /* const item = this.itensLocacao[index]
      if (!item) return

      let quantidade = parseInt(item.qtdParaLocacao) || 1

      if (quantidade < 1) {
        quantidade = 1
      } else if (quantidade > item.quantidade) {
        quantidade = item.quantidade
        this.mostrarNotificacaoLimite(item.nome, item.quantidade)
      } */

      // this.itensLocacao.splice(index, 1, { ...item, qtdParaLocacao: quantidade })
    },

    mostrarNotificacaoLimite(nomeItem, limite) {
      console.warn(`Quantidade ajustada para o máximo disponível: ${limite} unidades de ${nomeItem}`)
    },

    atualizarStatusProdutos() {
      this.$forceUpdate()
    },

    limparLista() {
      if (this.itensLocacao.length === 0) return

      if (confirm('Deseja realmente limpar toda a lista de itens selecionados?')) {
        this.itensLocacao = []
        this.atualizarStatusProdutos()
      }
    },

    // Salvamento da locação
    /*async salvarPedido() { // salvarPedido antiga

      this.salvando = true

      try {
        const payload = 
        {
          itens: this.itensLocacao.map(item => ({
            id: item.id,
            qtdParaLocacao: item.qtdParaLocacao,
            valor_locacao: item.valor_locacao
          })),
        }

        let observacao = this.observacoes; // <-- O valor guardado é colocado aqui
        console.log(observacao);
        console.log(this.observacoes);
        // O objeto 'entradaData' é enviado no corpo da requisição POST
        await axios.post('/criar-pedido-consumivel', {observacao: this.observacoes});
        await axios.post('/criar_produto_pedido_consumivel', payload);
        // ...
        

        
            /*         this.modalResultado = {
          mostrar: true,
          tipo: 'sucesso',
          titulo: 'Locação Salva com Sucesso!',
          mensagem: 'A locação foi registrada no sistema e está pronta para uso.',
          detalhes: {
            /* cliente: this.dadosEvento.nome,
            data: this.formatarData(this.dadosEvento.data), *\/
            totalItens: this.itensLocacao.length,
            valorTotal: this.valorTotalLocacao.toFixed(2).replace('.', ',')
          }
        } *\/

      } catch (error) {
        console.error('Erro ao salvar pedido:', error)

        this.modalResultado = {
          mostrar: true,
          tipo: 'erro',
          titulo: 'Erro ao Salvar Pedido',
          mensagem: 'Não foi possível salvar o pedido. Verifique os dados e tente novamente.',
          detalhes: {
            erro: error.response?.data?.message || error.message || 'Erro desconhecido'
          }
        }
      } finally {
        this.salvando = false
      }
    }, */

    async salvarPedido() 
    {
      // Verificar se há itens selecionados
      if (this.itensLocacao.length === 0) {
        alert('⚠️ Adicione pelo menos um item à lista antes de salvar.');
        return;
      }

      // Verificar se há erros de quantidade
      if (this.temItensComErro) {
        alert('⚠️ Corrija as quantidades inválidas antes de salvar.');
        return;
      }

      this.salvando = true;

      try {
        // Preparar payload com observação e itens
        const payload = {
          observacao: this.observacoes.trim() || null,
          itens: this.itensLocacao.map(item => ({
            id: parseInt(item.produtos_consumivel_id), // ID do produto consumível
            quantidade: parseInt(item.qtdParaLocacao), // Quantidade solicitada
            // valor_locacao: parseFloat(item.valor_locacao) // Valor unitário
          }))
        };

        console.log('Enviando payload para criar pedido completo:', payload);

        // 1. ÚNICA REQUISIÇÃO: Criar pedido + itens + atualizar estoque
        const response = await axios.post('/criar-pedido-com-itens-consumivel', payload);

        console.log('Resposta do servidor:', response.data);

        // Mostrar modal de sucesso
        this.modalResultado = {
          mostrar: true,
          tipo: 'sucesso',
          titulo: 'Pedido Criado com Sucesso! 🎉',
          mensagem: 'O pedido foi salvo e o estoque foi atualizado automaticamente.',
          detalhes: {
            pedidoId: response.data.pedido_consumivel_id,
            totalItens: this.itensLocacao.length,
            // valorTotal: this.valorTotalLocacao.toFixed(2).replace('.', ','),
            status: 'Pendente'
          }
        };

        // Limpar formulário para novo pedido
        this.limparFormulario();

      } catch (error) {
        console.error('Erro ao salvar pedido:', error);

        // Tratar diferentes tipos de erro
        let mensagemErro = 'Erro desconhecido ao salvar pedido.';
        let detalhesErro = {};

        if (error.response?.status === 422) {
          // Erro de validação
          mensagemErro = 'Dados inválidos. Verifique os itens selecionados.';
          detalhesErro = error.response.data.detalhes || {};
        } else if (error.response?.data?.erro) {
          // Erro específico do servidor (ex: estoque insuficiente)
          mensagemErro = error.response.data.erro;
        } else if (error.response?.status === 500) {
          mensagemErro = 'Erro interno do servidor. Tente novamente.';
        } else if (error.message) {
          mensagemErro = error.message;
        }

        this.modalResultado = {
          mostrar: true,
          tipo: 'erro',
          titulo: 'Erro ao Salvar Pedido ❌',
          mensagem: mensagemErro,
          detalhes: detalhesErro
        };

      } finally {
        this.salvando = false;
      }
    },

    // Métodos do modal de resultado
    confirmarModalResultado() {
      this.modalResultado.mostrar = false
      if (this.modalResultado.tipo === 'sucesso') {
        this.novoEvento()
      }
    },

    // Método auxiliar para limpar o formulário após sucesso
    limparFormulario() {
      this.observacoes = '';
      this.itensLocacao = [];
      this.filtros.searchProduto = '';
      this.paginaAtual = 1;
      this.pedidoSelecionadoId = null
      // Recarregar produtos para refletir estoque atualizado
      this.$nextTick(() => {
        this.carregarProdutos();
      });
    },

    // Métodos do modal de resultado
    tentarNovamente() {
      this.modalResultado.mostrar = false
    },

    atualizarPagina(novaPagina) {
      if (novaPagina < 1 || novaPagina > this.totalPaginas) return
      this.paginaAtual = novaPagina
      this.carregarProdutos()
    },

    async carregarProdutos() {
      // if (!this.dadosEvento.data) return
      this.carregando = true

      try {
        const params = {
          page: this.paginaAtual,
          per_page: this.itensPorPagina/* ,
          data_inicio: this.dadosEvento.data,
          data_fim: this.dadosEvento.dataRetorno */
        }

        if (this.filtros.searchProduto && this.filtros.searchProduto.trim()) {
          params.nome = this.filtros.searchProduto.trim()
        }

        const response = await axios.get('/produtos-consumivel', { params } )
        console.log('Resposta dos produtos:', response.data)

        this.produtos = Array.isArray(response.data.data) ? response.data.data : []
        this.paginaAtual = response.data.current_page || 1
        this.totalPaginas = response.data.last_page || 1

      } catch (error) {
        console.error('Erro ao carregar produtos:', error)
        this.produtos = []
      } finally {
        this.carregando = false
      }
    },

    formatarData(data) {
      try {
        if (!data) return ''
        if (data.includes('/')) return data
        const date = new Date(data + 'T00:00:00')
        if (isNaN(date.getTime())) {
          console.warn('Data inválida:', data)
          return data
        }
        return date.toLocaleDateString('pt-BR')
      } catch (error) {
        console.error('Erro ao formatar data:', error)
        return data
      }
    },

    limparTimeouts() {
      if (this.searchTimeout) {
        clearTimeout(this.searchTimeout)
        this.searchTimeout = null
      }
    },

    async buscarPedidos() {
      this.carregando = true;
      this.error = null;
      
      try {
        const response = await axios.get('/pedido-consumivel');
        this.pedidos = response.data.data || [];
      } catch (error) {
        console.error("Erro ao buscar pedidos:", error);
        this.error = "Não foi possível carregar os pedidos. Tente novamente mais tarde.";
      } finally {
        this.carregando = false;
      }
    },

    abrirHistorico() {
      if (!this.pedidos.length) this.buscarPedidos()
      this.mostrarModalHistorico = true
    },

    async onSelecionarPedido(pedidoBasico) {
      try {
        let pedidoDetalhe = pedidoBasico
        if (!pedidoBasico?.itens || !pedidoBasico.itens.length) {
          const id = pedidoBasico.id || pedidoBasico.pedido_consumivel_id
          const { data } = await axios.get(`/pedido-consumivel/${id}`)
          pedidoDetalhe = data.data || data
        }

        const itens = Array.isArray(pedidoDetalhe.itens) ? pedidoDetalhe.itens : []
        this.itensLocacao = this.mapItensPedidoParaItensLocacao(itens)
      
        
        this.observacoes = pedidoDetalhe.observacoes || ''
        this.pedidoSelecionadoId = pedidoDetalhe.id || pedidoDetalhe.pedido_consumivel_id || null

        // opcional: preencher observações do pedido
        /*if (pedidoDetalhe.observacao && !this.observacoes) {
          this.observacoes = pedidoDetalhe.observacao
        } */

        this.$nextTick(() => this.atualizarStatusProdutos())
      } catch (e) {
        console.error('Falha ao carregar itens do pedido selecionado:', e)
        alert('Não foi possível carregar os itens do pedido selecionado.')
      } finally {
        this.mostrarModalHistorico = false
      }
    },

    // Método auxiliar para limpar o formulário após sucesso
    limparFormulario() {
      this.itensLocacao = []
      this.observacoes = ''
      this.pedidoSelecionadoId = null
    },

    novoEvento() {
      this.dadosEvento = {
        nome: '',
        contato: '',
        evento: '',
        data: '',
        dataRetorno: null,
      }
      this.itensLocacao = []
      this.observacoes = ''
      this.pedidoSelecionadoId = null
      this.filtros = {
        searchProduto: '',
      }
    },

    cancelarEvento() {
      this.mostrarModalEvento = false
      this.$router.push('/consumivel')
      this.pedidoSelecionadoId = null
    },

    mapItensPedidoParaItensLocacao(itens) {
      return itens.map((i) => {
        const produto = i.produto || i.consumivel || i.item || {}
        const produtoId = parseInt(i.produtos_consumivel_id ?? produto.id ?? i.id)
        const qtdSolicitada = parseInt(i.quantidade_solicitada ?? i.quantidade ?? i.qtd ?? 1)
        const estoqueDisponivel = parseInt(
          produto.quantidade ?? produto.estoque ?? i.quantidade_disponivel ?? i.disponivel ?? qtdSolicitada
        )

        return {
          id: produtoId,
          produtos_consumivel_id: produtoId,
          nome: produto.nome || i.nome || `Produto ${produtoId}`,
          foto: produto.foto || null,
          quantidade: isNaN(estoqueDisponivel) ? qtdSolicitada : estoqueDisponivel,
          qtdParaLocacao: isNaN(qtdSolicitada) ? 1 : qtdSolicitada,
          valor_locacao: produto.valor_locacao ?? i.valor_locacao ?? 0,
          uniqueId: Date.now() + Math.random()
        }
      })
    },

    limparDados() {
      // Limpa o ID do pedido selecionado
      this.pedidoSelecionadoId = null
    
      // Limpa as observações
      this.observacoes = ''
    
      // Limpa a lista de itens
      this.itensLocacao = []
    
      // Atualiza o status dos produtos
      this.atualizarStatusProdutos()
    
      // Opcional: mostrar uma notificação de confirmação
      console.log('Dados limpos com sucesso')
    },
  },
  mounted() {
    this.carregarProdutos();
    this.buscarPedidos();
  },

  beforeUnmount() {
  this.limparTimeouts()
  }
}
</script>

<style scoped>
* {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.card-base {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.card-base:hover:not(.opacity-40):not(.opacity-60) {
  transform: translateY(-8px) scale(1.02);
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.card-base[draggable="true"]:active {
  transform: rotate(3deg) scale(0.95);
  opacity: 0.8;
}

.border-dashed {
  transition: all 0.3s ease;
}

.scale-102 {
  transform: scale(1.02);
}

.border-red-400 {
  border-color: #f87171 !important;
  animation: pulse-error 2s infinite;
}

.bg-red-50 {
  background-color: #fef2f2;
}

@keyframes pulse-error {

  0%,
  100% {
    opacity: 1;
  }

  50% {
    opacity: 0.8;
  }
}

.overflow-y-auto::-webkit-scrollbar {
  width: 6px;
}

.overflow-y-auto::-webkit-scrollbar-track {
  background: #f1f5f9;
  border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}

.loading {
  pointer-events: none;
  opacity: 0.7;
}

@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.card-base {
  animation: slideIn 0.3s ease-out;
}

button:not(:disabled):hover {
  transform: translateY(-1px);
}

button:not(:disabled):active {
  transform: translateY(0);
}

.grayscale {
  filter: grayscale(100%);
}

.cursor-move:hover {
  cursor: grab;
}

.cursor-move:active {
  cursor: grabbing;
}

@media (max-width: 768px) {
  .card-base:hover {
    transform: translateY(-4px) scale(1.01);
  }

  .flex.gap-6 {
    flex-direction: column;
    gap: 1rem;
  }

  .w-72 {
    width: 100%;
  }
}

input:focus,
button:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.1);
}

input.border-red-500:focus {
  box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1) !important;
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }

  100% {
    transform: rotate(360deg);
  }
}

.animate-spin {
  animation: spin 1s linear infinite;
}

.rounded-full {
  backdrop-filter: blur(10px);
}

.shadow-lg {
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

.shadow-xl {
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.shadow-2xl {
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

.bg-blue-500 {
  background-color: rgb(236 72 153);
}

@keyframes shake {

  0%,
  100% {
    transform: translateX(0);
  }

  25% {
    transform: translateX(-2px);
  }

  75% {
    transform: translateX(2px);
  }
}

.border-red-500 {
  animation: shake 0.5s ease-in-out;
}
</style>