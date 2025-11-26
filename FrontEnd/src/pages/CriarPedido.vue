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
      :textoBotaoPrincipal="modalResultado.tipo === 'sucesso' ? 'Novo Pedido' : 'OK'"
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
              <h1 class="text-2xl font-bold text-gray-800">
                {{ pedidoSelecionadoId !== null ? 'Editar Pedido' : 'Criar Pedido' }}
              </h1>
              <div class="flex items-center text-sm text-gray-600 mt-1 space-x-2">
                <span class="text-gray-400">Menu</span>
                <ChevronRight class="w-4 h-4" />
                <span class="text-gray-400">Menu Consumível</span>
                <ChevronRight class="w-4 h-4" />
                <span class="text-gray-400">Menu Pedidos</span>
                <ChevronRight class="w-4 h-4" />
                <span class="text-blue-600 font-medium">
                  {{ pedidoSelecionadoId !== null ? 'Editar Pedido' : 'Criar Pedido' }}
                </span>
              </div>
            </div>
          </div>

          <!-- BOTÃO EDITAR PEDIDOS -->
           <!--
          <div class="flex items-center">
            <button
              @click="abrirHistorico"
              class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-colors mr-3 flex items-center gap-2"
              title="Editar Pedidos">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                </path>
              </svg>
              Editar Pedidos
            </button>
          </div> -->
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
                ID do Pedido
              </label>
              <input
                :value="pedidoSelecionadoId"
                type="text"
                readonly
                :style="{ width: `${Math.max(80, String(pedidoSelecionadoId).length * 12 + 40)}px` }"
                class="font-mono py-2 px-4 border-2 border-blue-300 rounded-lg bg-blue-50 text-blue-700 transition-all text-center font-semibold"
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

          <!-- Card de status -->
          <div 
            class="text-right p-4 rounded-xl flex-shrink-0"
            :class="{
              'bg-yellow-50': statusPedido === 'pendente',
              'bg-green-50': statusPedido === 'concluído',
              'bg-red-50': statusPedido === 'cancelado',
              'bg-blue-50': !statusPedido || (statusPedido !== 'pendente' && statusPedido !== 'concluído' && statusPedido !== 'cancelado')
            }"
          >
            <div 
              class="text-sm font-medium mb-2"
              :class="{
                'text-yellow-700': statusPedido === 'pendente',
                'text-green-700': statusPedido === 'concluído',
                'text-red-700': statusPedido === 'cancelado',
                'text-gray-600': !statusPedido || (statusPedido !== 'pendente' && statusPedido !== 'concluído' && statusPedido !== 'cancelado')
              }"
            >
              Status do pedido: 
              <span class="font-bold">{{ statusPedido || 'Novo' }}</span>
            </div>
          </div>

          <!-- Card de total de itens -->
          <div class="text-right bg-blue-50 p-4 rounded-xl flex-shrink-0">
            <div class="text-sm text-gray-600 mb-2">Total de itens: {{ itensPedido.length }}</div>
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
          class="bg-white border-4 border-dashed border-blue-300 rounded-2xl p-6 min-h-[300px] shadow-lg transition-all duration-300"
          :class="{ 'border-blue-500 bg-blue-50 scale-102': isDragging }" @dragover.prevent="handleDragOver"
          @dragleave="handleDragLeave" @drop="onDrop">
          <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-blue-700">Itens Selecionados</h3>
            <span v-if="itensPedido.length > 0"
              class="text-sm text-gray-600 bg-blue-100 px-4 py-2 rounded-full font-medium">
              {{ itensPedido.length }} {{ itensPedido.length === 1 ? 'item' : 'itens' }}
            </span>
          </div>

          <div v-if="itensPedido.length === 0" class="text-center py-16">
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
            <div v-for="(item, index) in itensPedido" :key="item.uniqueId"
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
                  ID: {{ item.produtos_consumivel_id }}
                  ID2: {{ item.id }}
                </div>
                <div v-if="temErroQuantidade(item) && pedidoSelecionadoId == null"
                  class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded-full text-xs font-bold">
                  ⚠️
                </div>
              </div>

              <div class="mb-4">
                <h3 class="text-sm font-bold text-blue-700 mb-2 leading-tight">{{ item.nome }}</h3>
              </div>

              <div class="space-y-2">
                <div class="flex gap-2 items-center">
                  <button @click="diminuirQuantidade(index)" :disabled="item.qtdPedido <= 1"
                    class="w-8 h-8 bg-red-100 hover:bg-red-200 disabled:bg-gray-200 disabled:text-gray-400 rounded-lg text-sm font-bold text-red-600 transition-colors flex items-center justify-center">
                    -
                  </button>

                  <input  
                    v-model.number="item.qtdPedido" type="number"
                    :min = "[pedidoSelecionadoId == null ? 1 : -99999]"
                    :max="[pedidoSelecionadoId == null ? item.quantidade : 99999]" 
                    class="flex-1 px-3 py-2 border rounded-lg text-sm text-center transition-all"
                    :class="[
                      (temErroQuantidade(item) && pedidoSelecionadoId == null)
                        ? 'border-red-500 bg-red-50 focus:ring-red-300 focus:border-red-500'
                        : 'border-gray-300 focus:ring-blue-300 focus:border-transparent'
                    ]"
                    onclick= "this.select()"
                    @input="validarQuantidade(index)" 
                    @blur="corrigirQuantidadeSeNecessario(index)"
                    @keyup.enter="corrigirQuantidadeSeNecessario(index)" 
                  />

                  <button @click="aumentarQuantidade(index)" :disabled="!podeAumentarQuantidade(index)" 
                    class="w-8 h-8 bg-blue-200 hover:bg-blue-300 disabled:bg-gray-200 disabled:text-gray-400 rounded-lg text-sm font-bold text-blue-700 transition-colors flex items-center justify-center">
                    +
                  </button>
                  <button @click="logger(index)"  
                    class="w-8 h-8 bg-blue-200 hover:bg-blue-300 disabled:bg-gray-200 disabled:text-gray-400 rounded-lg text-sm font-bold text-blue-700 transition-colors flex items-center justify-center">
                    |||
                  </button>
                </div>
                
                <div v-if="temErroQuantidade(item) && pedidoSelecionadoId == null" class="text-xs text-red-600 font-medium">
                  Máximo disponível: {{ item.quantidade }}
                </div>

                <div v-if="pedidoSelecionadoId == null" class="text-xs text-gray-500 text-center">
                  {{ item.qtdPedido }} de {{ item.quantidade }} disponíveis
                </div>
                <div v-else-if="pedidoSelecionadoId !== null && item.qtdPedidoDiferença > 0" class="text-xs text-gray-500 text-center" >
                  {{ item.qtdPedidoDiferença }} de {{ item.quantidade }} disponíveis
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
              <div
                v-for="peca in produtosFiltrados"
                :key="peca.id"
                :class="[
                  'bg-white shadow-lg p-4 transition-all duration-300 border border-gray-200 rounded-2xl',
                  peca.quantidade === 0
                    ? 'opacity-40 cursor-not-allowed grayscale'
                    : itemJaSelecionado(peca.id)
                      ? 'border-2 border-blue-500 bg-blue-50 opacity-40'
                      : 'cursor-move hover:shadow-xl hover:border-blue-300 hover:-translate-y-2 transform'
                ]"
                :dragstart="peca.quantidade > 0 && !itemJaSelecionado(peca.produtos_consumivel_id)"
                :draggable="peca.quantidade > 0 && !itemJaSelecionado(peca.produtos_consumivel_id)"
                :dblclick= "0 === 1"
                @dragstart="startDrag($event, peca)"
                @dblclick="adicionarItemDuplo(peca)"
              >
                <div class="relative bg-gray-50 rounded-xl mb-4 overflow-hidden" style="height: 180px;">
                  <template v-if="peca.foto">
                    <img
                      :src="peca.foto"
                      :alt="peca.nome"
                      class="w-full h-full object-cover transition-transform duration-300"
                      :class="{ 'hover:scale-110': !itemJaSelecionado(peca.id) && peca.quantidade > 0 }"
                    />
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
                    <div class="bg-orange-100 text-orange-800 px-2 py-1 rounded-full text-xs font-medium">
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
              <!-- Botão Limpar Dados (sempre visível) -->
              <button 
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

              <!-- Botão Salvar/Atualizar Pedido -->
              <button 
                @click="salvarOuAtualizarPedido"
                :disabled="salvando || (itensPedido.length === 0 && this.itensRemovidosIds.length <= 0) || temItensComErro" 
                :class="[
                  'text-white font-semibold px-8 py-4 rounded-xl shadow-lg transition-all duration-200 transform hover:scale-105 hover:shadow-xl flex items-center gap-2',
                  pedidoSelecionadoId !== null 
                    ? 'bg-orange-600 hover:bg-orange-700' 
                    : 'bg-blue-600 hover:bg-blue-700',
                  (salvando || (itensPedido.length === 0 && this.itensRemovidosIds.length <= 0) || temItensComErro) ? 'opacity-50 cursor-not-allowed' : ''
                ]"
              >
                <svg v-if="!salvando" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    :d="pedidoSelecionadoId !== null 
                      ? 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15'
                      : 'M5 13l4 4L19 7'">
                  </path>
                </svg>
                <svg v-else class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                  </path>
                </svg>
                {{ salvando ? 'Salvando...' : (pedidoSelecionadoId !== null ? 'Atualizar Pedido' : 'Salvar Pedido') }}
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
import { ArrowLeft, ChevronRight } from 'lucide-vue-next'
  
export default {
  components: {
    Paginacao,
    ModalParaCriacaoDeEvento,
    ModalResultadoLocacao,
    ModalHistoricoPedidos,
    ArrowLeft,
    ChevronRight
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
      itensPedido: [],
      itensRemovidosIds: [], // Array para rastrear IDs dos itens removidos
      filtros: {
        searchProduto: '',
      },
      pedidoSelecionadoId: null,
      paginaAtual: 1,
      totalPaginas: 1,
      itensPorPagina: 8,
      isDragging: false,
      searchTimeout: null,
      carregando: false,
      salvando: false,
      observacoes: '',
      statusPedido: ''
    }
  },
  computed: {
    produtosFiltrados() {
      return Array.isArray(this.produtos) ? this.produtos : []
    },

    valorTotalLocacao() {
      return this.itensPedido.reduce((total, item) => {
        return total + (parseFloat(item.valor_locacao) * item.qtdPedido)
      }, 0)
    },

    temItensComErro() {
      console.log('tem itens cm error?? - ' + this.itensPedido.some(item => this.temErroQuantidade(item)))
      return this.itensPedido.some(item => this.temErroQuantidade(item))
    }
  },

  watch: {
    'filtros.searchProduto': {
      handler() {
        this.limparTimeouts()
        this.searchTimeout = setTimeout(() => {
          this.paginaAtual = 1
          this.carregarProdutos()
        }, 500)
      }
    }
  },

  methods: {

    temErroQuantidade(item) 
    {
      if (!item || !item.qtdPedido || !item.quantidade) return false;
      const disponivel = Number.parseInt(item.quantidade, 10);
      let quantidade = Number.parseInt(item.qtdPedido, 10);
      // console.log('temerroqtd ' + quantidade);

      return quantidade <= 0 ||
        ( (quantidade > disponivel && this.pedidoSelecionadoId == null) || (item.qtdPedidoDiferenca > disponivel && this.pedidoSelecionadoId !== null) ) ||
        !Number.isInteger(quantidade) ||
        isNaN(quantidade);
    },

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
      this.itensPedido = []
      this.observacoes = ''
      this.statusPedido = ''
      this.pedidoSelecionadoId = null
      this.filtros = {
        searchProduto: '',
      }
    },

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

    adicionarItem(produto) {
      // Verifica se o produto já foi adicionado
      if (this.itemJaSelecionado(produto.id)) {
        alert('⚠️ Este produto já foi adicionado ao pedido!')
        return
      }

      if (produto.quantidade === 0) {
        alert('⚠️ Este produto está sem estoque disponível.')
        return
      }

      const novoItem = {
        ...produto,
        uniqueId: Date.now() + Math.random(),
        qtdPedido: 1,
        qtdPedidoReservado: 0,
        qtdPedidoDiferença: produto.quantidade
      }

      this.itensPedido.push(novoItem)
      this.atualizarStatusProdutos()
    },

    adicionarItemDuplo(produto) {
      // Verifica se o produto já foi adicionado
      /* if (this.itemJaSelecionado(produto.id)) {
        alert('⚠️ Este produto já foi adicionado ao pedido!')
        return
      } */
      if (this.itemJaSelecionado(produto.produtos_consumivel_id)) {
        alert('⚠️ Este produto já foi adicionado ao pedido!')
        return
      }


      if (produto.quantidade > 0) {
        this.adicionarItem(produto)
      }
    },

    onDrop(event) {
      event.preventDefault()
      this.isDragging = false

      try {
        const data = event.dataTransfer.getData('application/json')
        if (!data) return

        const produto = JSON.parse(data)

        // Verifica se o produto já foi adicionado
        if (this.itemJaSelecionado(produto.id)) {
          alert('⚠️ Este produto já foi adicionado ao pedido!')
          return
        }

        this.adicionarItem(produto)
      } catch (err) {
        console.error('Erro ao processar drop:', err)
      }
    },

    itemJaSelecionado(produtoId) {
      return this.itensPedido.some(item =>
        parseInt(item.id) === parseInt(produtoId) ||
        parseInt(item.produtos_consumivel_id) === parseInt(produtoId)
      )
    },

    removerItem(index) {
      const item = this.itensPedido[index]
      
      // Se o item tem produtos_pedido_consumivel_id, significa que veio do banco
      // Adiciona ao array de itens removidos para deletar no backend
      if (item.produtos_pedido_consumivel_id) {
        this.itensRemovidosIds.push(item.produtos_pedido_consumivel_id)
        console.log('Item marcado para deleção:', item.produtos_pedido_consumivel_id)
      }
      
      this.itensPedido.splice(index, 1)
      this.atualizarStatusProdutos()
    },

    logger (index) {
      const item = this.itensPedido[index]
      console.log('logger -  item: ' + JSON.stringify(item));
    },
    

    podeAumentarQuantidade(index) {
      const item = this.itensPedido[index]
      let qtdPedidoDiferenca = isNaN(item.qtdPedidoDiferença) ? 0 : item.qtdPedidoDiferença
      /* console.log('pode aumentar qtd true? - ' + (
        (item.qtdPedido < item.quantidade && this.pedidoSelecionadoId == null) ||
        (this.pedidoSelecionadoId !== null &&
          (item.qtdPedidoDiferença < item.quantidade || qtdPedidoDiferenca <= 0))
      )); */
      return (
        (item.qtdPedido < item.quantidade && this.pedidoSelecionadoId == null) ||
        (this.pedidoSelecionadoId !== null &&
          (item.qtdPedidoDiferença < item.quantidade || (qtdPedidoDiferenca < 0 && item.quantidade <= 0)))
      )
    },

    aumentarQuantidade(index) {
      const item = this.itensPedido[index]
      let qtdPedidoDiferenca = isNaN(item.qtdPedidoDiferença) ? 0 : item.qtdPedidoDiferença

      console.log('inicio aumentarQuantidade logs')
      console.log('acessou aumentarQuantidade');
      console.log('podeAumentarQuantidade - ' + this.podeAumentarQuantidade(index) + " - item:" + item);
      console.log('valor de qtdPedidoDiferença ' + (isNaN(item.qtdPedidoDiferença) ? 0 : item.qtdPedidoDiferença));
      console.log('fim aumentarQuantidade logs')

      if (item && this.podeAumentarQuantidade(index)) {
        item.qtdPedido++
        item.qtdPedidoDiferença++
        console.log('aumentarQuantidade ' + item.qtdPedidoDiferença);
        this.$forceUpdate()
      }
    },

    diminuirQuantidade(index) {
      const item = this.itensPedido[index]
      let qtdPedidoDiferenca = isNaN(item.qtdPedidoDiferença) ? 0 : item.qtdPedidoDiferença
      if (item && item.qtdPedido > 1) {
        item.qtdPedido--
        item.qtdPedidoDiferença--
        console.log('diminuirQuantidade ' + item.qtdPedidoDiferença);
        this.$forceUpdate()
      }
    },

    validarQuantidade(index) {
      const item = this.itensPedido[index]
      if (!item) return

      let quantidade = item.qtdPedido

      if (typeof quantidade === 'string') {
        quantidade = parseInt(quantidade) || 1
      }
      if (!Number.isInteger(quantidade) || isNaN(quantidade)) {
        quantidade = 1
      }
      if (quantidade < 1) {
        quantidade = 1
      }

      this.itensPedido.splice(index, 1, { ...item, qtdPedido: quantidade })
    },

    corrigirQuantidadeSeNecessario(index) {
      // Método mantido para compatibilidade com eventos @blur e @keyup.enter
    },

    mostrarNotificacaoLimite(nomeItem, limite) {
      console.warn(`Quantidade ajustada para o máximo disponível: ${limite} unidades de ${nomeItem}`)
    },

    atualizarStatusProdutos() {
      this.$forceUpdate()
    },

    limparLista() {
      if (this.itensPedido.length === 0) return

      if (confirm('Deseja realmente limpar toda a lista de itens selecionados?')) {
        this.itensPedido = []
        this.atualizarStatusProdutos()
      }
    },

    async salvarOuAtualizarPedido() {
      if (this.pedidoSelecionadoId !== null) {
        await this.atualizarPedido()
      } else {
        await this.salvarPedido()
      }
    },

    async salvarPedido() {
      if (this.itensPedido.length === 0) {
        alert('⚠️ Adicione pelo menos um item à lista antes de salvar.')
        return
      }

      if (this.temItensComErro) {
        alert('⚠️ Corrija as quantidades inválidas antes de salvar.')
        return
      }

      this.salvando = true

      try {
        const payload = {
          observacao: this.observacoes.trim() || null,
          itens: this.itensPedido.map(item => ({
            id: parseInt(item.produtos_consumivel_id),
            quantidade: parseInt(item.qtdPedido),
          }))
        }

        console.log('Enviando payload para criar pedido completo:', payload)

        const response = await axios.post('/criar-pedido-com-itens-consumivel', payload)

        console.log('Resposta do servidor:', response.data)

        this.modalResultado = {
          mostrar: true,
          tipo: 'sucesso',
          titulo: 'Pedido Criado com Sucesso! 🎉',
          mensagem: 'O pedido foi salvo e o estoque foi atualizado automaticamente.',
          detalhes: {
            pedidoId: response.data.pedido_consumivel_id,
            totalItens: this.itensPedido.length,
            status: 'Pendente'
          }
        }

        this.limparFormulario()

      } catch (error) {
        console.error('Erro ao salvar pedido:', error)

        let mensagemErro = 'Erro desconhecido ao salvar pedido.'
        let detalhesErro = {}

        if (error.response?.status === 422) {
          mensagemErro = 'Dados inválidos. Verifique os itens selecionados.'
          detalhesErro = error.response.data.detalhes || {}
        } else if (error.response?.data?.erro) {
          mensagemErro = error.response.data.erro
        } else if (error.response?.status === 500) {
          mensagemErro = 'Erro interno do servidor. Tente novamente.'
        } else if (error.message) {
          mensagemErro = error.message
        }

        this.modalResultado = {
          mostrar: true,
          tipo: 'erro',
          titulo: 'Erro ao Salvar Pedido ❌',
          mensagem: mensagemErro,
          detalhes: detalhesErro
        }

      } finally {
        this.salvando = false
      }
    },

    async atualizarPedido() {
      // Permite exclusão: zero itens selecionados, mas há itens removidos
      if (this.itensPedido.length === 0 && this.itensRemovidosIds.length === 0) {
        alert('⚠️ Adicione pelo menos um item à lista antes de atualizar.')
        return
      }

      if (this.itensPedido.length === 0 && this.itensRemovidosIds.length > 0) {
        if (!confirm('⚠️ O pedido será cancelado. Deseja CANCELAR o pedido?')) {
          return
        }
      } else {
        if (this.temItensComErro) {
          alert('⚠️ Corrija as quantidades inválidas antes de atualizar.')
          return
        }
        if (!confirm('Deseja realmente atualizar este pedido? Esta ação irá substituir os itens existentes.')) {
          return
        }
      }

      this.salvando = true

      try {
        const payload = {
          observacao: this.observacoes.trim() || null,
          itens: this.itensPedido.map(item => ({
            id: parseInt(item.produtos_consumivel_id),
            quantidade: parseInt(item.qtdPedido),
          })),
          itens_deletados: this.itensRemovidosIds
        }

        const response = await axios.put(`/editar-pedido-consumivel/${this.pedidoSelecionadoId}`, payload)

        if (response.data?.pedido_excluido) {
          this.modalResultado = {
            mostrar: true,
            tipo: 'sucesso',
            titulo: 'Pedido Excluído com Sucesso! 🗑️',
            mensagem: 'O pedido foi cancelado conforme requisitado.',
            detalhes: {
              pedidoId: this.pedidoSelecionadoId,
              totalItensRemovidos: this.itensRemovidosIds.length,
              status: 'Cancelado'
            }
          }
        } else {
          this.modalResultado = {
            mostrar: true,
            tipo: 'sucesso',
            titulo: 'Pedido Atualizado com Sucesso! 🎉',
            mensagem: 'O pedido foi atualizado e o estoque foi ajustado automaticamente.',
            detalhes: {
              pedidoId: this.pedidoSelecionadoId,
              totalItens: this.itensPedido.length,
              itensRemovidos: this.itensRemovidosIds.length,
              status: 'Atualizado'
            }
          }
        }

        this.limparFormulario()

      } catch (error) {
        console.error('Erro ao atualizar pedido:', error)
        let mensagemErro = 'Erro desconhecido ao atualizar pedido.'
        let detalhesErro = {}
        if (error.response?.status === 422) {
          mensagemErro = 'Dados inválidos. Verifique os itens selecionados.'
          detalhesErro = error.response.data.detalhes || {}
        } else if (error.response?.data?.erro) {
          mensagemErro = error.response.data.erro
        } else if (error.response?.status === 404) {
          mensagemErro = 'Pedido não encontrado.'
        } else if (error.response?.status === 500) {
          mensagemErro = 'Erro interno do servidor. Tente novamente.'
        } else if (error.message) {
          mensagemErro = error.message
        }
        this.modalResultado = { mostrar: true, tipo: 'erro', titulo: 'Erro ao Atualizar Pedido ❌', mensagem: mensagemErro, detalhes: detalhesErro }
      } finally {
        this.salvando = false
      }
    },

    confirmarModalResultado() {
      this.modalResultado.mostrar = false
      if (this.modalResultado.tipo === 'sucesso') {
        this.novoEvento()
      }
    },

    limparFormulario() {
      this.observacoes = ''
      this.itensPedido = []
      this.itensRemovidosIds = [] // Limpa array de itens removidos
      this.filtros.searchProduto = ''
      this.paginaAtual = 1
      this.pedidoSelecionadoId = null
      this.$nextTick(() => {
        this.carregarProdutos()
      })
    },

    tentarNovamente() {
      this.modalResultado.mostrar = false
    },

    atualizarPagina(novaPagina) {
      if (novaPagina < 1 || novaPagina > this.totalPaginas) return
      this.paginaAtual = novaPagina
      this.carregarProdutos()
    },

    async carregarProdutos() {
      this.carregando = true

      try {
        const params = {
          page: this.paginaAtual,
          per_page: this.itensPorPagina
        }

        if (this.filtros.searchProduto && this.filtros.searchProduto.trim()) {
          params.nome = this.filtros.searchProduto.trim()
        }

        const response = await axios.get('/produtos-consumivel', { params })
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
      this.carregando = true
      this.error = null
      
      try {
        const response = await axios.get('/pedido-consumivel')
        this.pedidos = response.data.data || []
      } catch (error) {
        console.error("Erro ao buscar pedidos:", error)
        this.error = "Não foi possível carregar os pedidos. Tente novamente mais tarde."
      } finally {
        this.carregando = false
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
        this.itensPedido = this.mapItensPedidoParaitensPedido(itens)
      
        this.observacoes  = pedidoDetalhe.observacoes || ''
        this.statusPedido = pedidoDetalhe.status || ''
        this.pedidoSelecionadoId = pedidoDetalhe.id || pedidoDetalhe.pedido_consumivel_id || null
        this.itensRemovidosIds   = [] // Reseta array de itens removidos ao carregar novo pedido

        this.$nextTick(() => this.atualizarStatusProdutos())
      } catch (e) {
        console.error('Falha ao carregar itens do pedido selecionado:', e)
        alert('Não foi possível carregar os itens do pedido selecionado.')
      } finally {
        this.mostrarModalHistorico = false
      }
    },

    mapItensPedidoParaitensPedido(itens) {
      return itens.map((i) => {
        const produto           = i.produto || i.consumivel || i.item || {}
        const produtoId         = parseInt(i.produtos_consumivel_id ?? produto.id ?? i.id)
        const qtdSolicitada     = parseInt(i.quantidade_solicitada ?? i.quantidade ?? i.qtd ?? 1)
        const estoqueDisponivel = parseInt(
          produto.quantidade ?? produto.estoque ?? i.quantidade_disponivel ?? i.disponivel ?? qtdSolicitada
        )
      console.log('mapItensPedidoParaitensPedido - produtoId:', produtoId)
        return {
          id: produtoId,
          produtos_consumivel_id: produtoId,
          produtos_pedido_consumivel_id: i.produtos_pedido_consumivel_id || null, // ID do item no banco
          nome: produto.nome || i.nome || `Produto ${produtoId}`,
          foto: produto.foto || null,
          quantidade: isNaN(estoqueDisponivel) ? qtdSolicitada : estoqueDisponivel,
          qtdPedido: isNaN(qtdSolicitada) ? 1 : qtdSolicitada,
          qtdPedidoReservado: isNaN(qtdSolicitada) ? 1 : qtdSolicitada,
          // Ao editar o produto, variavel captura quanto vai ser adicionado/removido ao item, pra checar se tem no estoque
          qtdPedidoDiferença: 0,
          valor_locacao: produto.valor_locacao ?? i.valor_locacao ?? 0,
          uniqueId: Date.now() + Math.random()
        }
      })
    },

    limparDados() {
      if (this.itensPedido.length > 0 || this.observacoes || this.pedidoSelecionadoId !== null) {
        if (confirm('Deseja realmente limpar todos os dados do formulário?')) {
          this.pedidoSelecionadoId = null
          this.observacoes = ''
          this.statusPedido = ''
          this.itensPedido = []
          this.itensRemovidosIds = [] // Limpa array de itens removidos
          this.atualizarStatusProdutos()
          console.log('Dados limpos com sucesso')
        }
      }
    },
  },
  mounted() {
    this.carregarProdutos()
    this.buscarPedidos()
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
 
/*  */
/* Remove arrows (Chrome, Safari, Edge, Opera) */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Remove arrows (Firefox) */
input[type="number"] {
  appearance: textfield;
  -moz-appearance: textfield;
}

/* Normalize appearance */
/* input {
  appearance: textfield;
} */
/*  */

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