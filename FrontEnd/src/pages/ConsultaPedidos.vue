<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-100 via-blue-50 to-white">
    <!-- Header com Breadcrumbs -->
    <div class="bg-white shadow-sm sticky top-0 z-20">
      <div class="max-w-7xl mx-auto px-6 py-4">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-4">
            <button @click="$router.go(-1)" class="text-gray-500 hover:text-gray-700 transition-colors">
              <ArrowLeft class="w-5 h-5" />
            </button>
            <div>
              <h1 class="text-2xl font-bold text-gray-800">Gerenciamento de Pedidos</h1>
              
              <div class="flex items-center text-sm text-gray-600 mt-1 space-x-2">
                <span class="text-gray-400">Menu</span>
                <ChevronRight class="w-4 h-4" />
                <span class="text-gray-400">Menu Consumível</span>
                <ChevronRight class="w-4 h-4" />
                <span class="text-gray-400">Menu Pedidos</span>
                <ChevronRight class="w-4 h-4" />
                <span class="text-blue-600 font-medium">Consultar Pedido</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <main class="max-h-[70vh] overflow-auto p-6">
      <div v-if="loading" class="flex justify-center items-center py-12">
        <div class="w-8 h-8 border-4 border-green-200 border-t-green-600 rounded-full animate-spin"></div>
      </div>

      <div v-else class="bg-white rounded-xl shadow-lg border border-gray-300">
        <!-- ALTERADO: grid-cols-6 para grid-cols-7 e adicionado coluna Usuário -->
        <div class="bg-gray-50 p-4 border border-gray-200 rounded-t-xl text-sm font-semibold text-gray-600 grid grid-cols-7 gap-4">
          <div>Data</div>
          <div>ID</div>
          <div>Usuário</div>
          <div class="col-span-2">Observação</div>
          <div>Itens</div>
          <div>Status</div>
        </div>

        <div class="border-x border-b border-gray-200 rounded-b-xl overflow-y-auto">
          <template v-for="pedido in lista" :key="pedido.id || pedido.pedido_consumivel_id">
            <!-- ALTERADO: grid-cols-6 para grid-cols-7 -->
            <button
              @click="togglePedido(pedido)"
              class="w-full text-left p-4 grid grid-cols-7 gap-4 items-center hover:bg-white transition-colors border-b"
              :class="{ 'bg-blue-50': pedidoExpandido === (pedido.id || pedido.pedido_consumivel_id) }"
            >
              <div class="text-sm text-gray-800">
                {{ formatarData(pedido.data_pedido) }}
              </div>
              <div class="text-sm font-medium text-gray-800">
                #{{ pedido.id || pedido.pedido_consumivel_id }}
              </div>
              
              <!-- NOVO: Campo Usuário -->
              <div class="text-sm text-gray-800 truncate font-medium" :title="pedido.usuario?.nome || pedido.usuario?.name">
                {{ pedido.usuario?.nome || pedido.usuario?.name || 'N/A' }}
              </div>

              <div class="text-sm col-span-2 text-gray-600 truncate" :title="pedido.observacao || '-'">
                {{ pedido.observacoes || '-' }}
              </div>
              <div class="text-sm">
                <span class="inline-flex items-center gap-2 px-2 py-1 bg-green-100 text-green-700 rounded-full font-medium">
                  {{ (pedido.itens && pedido.itens.length) ? pedido.itens.length : (pedido.total_itens || 0) }}
                </span>
              </div>
              <div class="text-sm flex items-center justify-between">
                <span class="px-2 py-1 rounded-full"
                      :class="{
                        'bg-yellow-100 text-yellow-700': pedido.status === 'pendente',
                        'bg-green-100 text-green-700': pedido.status === 'concluido',
                        'bg-red-100 text-red-700': pedido.status === 'cancelado',
                        'bg-blue-100 text-blue-700': !pedido.status || (pedido.status !== 'pendente' && pedido.status !== 'concluido' && pedido.status !== 'cancelado')
                      }">
                  {{ pedido.status || 'pendente' }}
                </span>
                <ChevronDown v-if="pedidoExpandido !== (pedido.id || pedido.pedido_consumivel_id)"
                  class="w-4 h-4 text-gray-400 transition-transform"
                />
                <ChevronUp v-if="pedidoExpandido === (pedido.id || pedido.pedido_consumivel_id)"
                  class="w-4 h-4 text-gray-400 transition-transform"
                />
              </div>
            </button>

            <!-- Detalhes expandidos do pedido -->
            <transition name="expand">
              <div 
                v-if="pedidoExpandido === (pedido.id || pedido.pedido_consumivel_id)"
                class="bg-gray-50 border-b border-gray-200 p-6"
              >
                <div class="mb-4">
                  <div class="flex gap-4 mb-4">
                    <!-- Campo ID -->
                    <div class="flex-shrink-0">
                      <label class="block text-sm font-medium text-gray-700 mb-2">ID do Pedido</label>
                      <input
                        :value="pedido.id || pedido.pedido_consumivel_id"
                        type="text"
                        readonly
                        class="font-mono py-2 px-4 border-2 border-gray-200 rounded-lg bg-white text-gray-700 text-center"
                        :style="{ width: `${Math.max(60, String(pedido.id || pedido.pedido_consumivel_id).length * 12 + 40)}px` }"
                      />
                    </div>
                    
                    <!-- NOVO: Campo Usuário nos detalhes também -->
                    <div class="flex-shrink-0 w-1/4">
                      <label class="block text-sm font-medium text-gray-700 mb-2">Solicitante</label>
                      <input
                        :value="pedido.usuario?.nome || pedido.usuario?.name || 'N/A'"
                        type="text"
                        readonly
                        class="w-full py-2 px-4 border-2 border-gray-200 rounded-lg bg-white text-gray-700"
                      />
                    </div>

                    <!-- Campo Observações -->
                    <div class="flex-1">
                      <label class="block text-sm font-medium text-gray-700 mb-2">Observações</label>
                      <input
                        :value="pedido.observacoes || 'Sem observações'"
                        type="text"
                        readonly
                        class="w-full py-2 px-4 border-2 border-gray-200 rounded-lg bg-white text-gray-700"
                      />
                    </div>
                  </div>

                  <!-- Informações de data baseadas no status -->
                  <div v-if="pedido.status === 'cancelado'" class="mb-4">
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                      <p v-if="pedido.data_cancelamento" class="text-sm font-medium text-red-800 mb-1">
                        <span class="font-bold">Cancelado em:</span> {{ formatarDataHora(pedido.data_cancelamento) }}
                      </p>
                      <p v-if="pedido.observacao_cancelamento" class="text-sm text-red-700">
                        <span class="font-bold">Motivo:</span> {{ pedido.observacao_cancelamento }}
                      </p>
                      <p v-else class="text-sm text-red-600 italic">
                        Sem motivo informado
                      </p>
                    </div>
                  </div>

                  <div v-if="pedido.status === 'concluido'" class="mb-4">
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                      <p v-if="pedido.data_confirmacao" class="text-sm font-medium text-green-800">
                        <span class="font-bold">Confirmado em:</span> {{ formatarDataHora(pedido.data_confirmacao) }}
                      </p>
                    </div>
                  </div>

                </div>

                <!-- Lista de itens do pedido -->
                <div v-if="carregandoItens && pedidoExpandido === (pedido.id || pedido.pedido_consumivel_id)" class="text-center py-8">
                  <div class="inline-block w-6 h-6 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin"></div>
                  <p class="mt-2 text-gray-500 text-sm">Carregando itens...</p>
                </div>

                <div v-else-if="itensPedido.length > 0">
                  <h4 class="text-lg font-semibold text-gray-700 mb-4">Itens do Pedido</h4>
                  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    <div 
                      v-for="item in itensPedido" 
                      :key="item.id"
                      class="bg-white shadow-md p-4 rounded-xl border border-gray-300"
                    >
                      <div class="relative bg-gray-50 rounded-xl mb-3 overflow-hidden" style="height: 150px;">
                        <template v-if="item.foto">
                          <img :src="item.foto" :alt="item.nome" class="w-full h-full object-cover" />
                        </template>
                        <template v-else>
                          <div class="w-full h-full flex items-center justify-center text-gray-400">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 002 2z">
                              </path>
                            </svg>
                          </div>
                        </template>

                        <div class="absolute top-2 left-2 bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-medium">
                          Qtd: {{ item.qtdParaLocacao || item.quantidade || 0 }}
                        </div>
                      </div>

                      <h5 class="text-sm font-bold text-blue-700 mb-1 leading-tight">{{ item.nome }}</h5>
                      <p class="text-xs text-gray-500">ID: {{ item.produtos_consumivel_id || item.id }}</p>
                    </div>
                  </div>
                </div>

                <div v-else class="text-center py-8">
                  <p class="text-gray-500">Nenhum item encontrado neste pedido</p>
                </div>
                <br></br>

                                  <!-- Botões de Ação -->
                <div v-if="pedido.status === 'pendente'" class="flex gap-3 mb-4">
                  <button
                    @click="confirmarPedido(pedido)"
                    :disabled="processando"
                    class="flex px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors disabled:opacity-50 font-medium"
                  >
                    ✓ Confirmar Pedido
                  </button>
                  <button
                    @click="abrirModalCancelarPedido(pedido)"
                    :disabled="processando"
                    class="flex px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors disabled:opacity-50 font-medium"
                  >
                    ✕ Cancelar Pedido
                  </button>
                </div>

              </div>
            </transition>
          </template>

          <div v-if="lista.length === 0" class="text-center py-12 bg-white">
            <History class="w-12 h-12 text-gray-300 mx-auto mb-4" />
            <p class="text-gray-500">Nenhum pedido encontrado</p>
          </div>
        </div>
      </div>
    </main>

    <div class="border-t border-gray-200 p-6 flex justify-end bg-white">
      <button @click="$router.go(-1)" class="px-6 py-3 bg-gray-600 text-white hover:bg-gray-700 transition-colors rounded-xl mr-4">
        Voltar
      </button>
      <button @click="fechar" class="px-6 py-3 bg-green-600 text-white hover:bg-green-700 transition-colors rounded-xl">
        Fechar
      </button>
    </div>

    <!-- Modal de Cancelamento -->
    <ModalCancelarPedido
      :mostrar="modalCancelamento.mostrar"
      :pedido-id="modalCancelamento.pedidoId"
      @fechar="modalCancelamento.mostrar = false"
      @confirmar="handleCancelarPedido"
    />
  </div>
</template>

<script>
import axios from 'axios'
import { History, ChevronRight, ChevronDown, ChevronUp, ArrowLeft } from 'lucide-vue-next'
import ModalCancelarPedido from '../components/ModalCancelarPedido.vue'

export default {
  name: 'ConsultaPedidos',
  components: { History, ChevronRight, ChevronDown, ChevronUp, ArrowLeft, ModalCancelarPedido },
  data() {
    return {
      loading: false,
      carregandoItens: false,
      processando: false,
      lista: [],
      pedidoExpandido: null,
      itensPedido: [],
      modalCancelamento: {
        mostrar: false,
        pedidoId: null
      }
    }
  },
  mounted() {
    this.carregarPedidos()
  },
  methods: {
    fechar() {
      this.$router.push('/consumivel')
    },
    async carregarPedidos() {
      this.loading = true
      try {
        const { data } = await axios.get('/pedido-consumivel', {
          params: { per_page: 10000 }
        })
        this.lista = Array.isArray(data.data) ? data.data : []
        this.lista.sort((a, b) => (b.id || b.pedido_consumivel_id || 0) - (a.id || a.pedido_consumivel_id || 0))
      } catch (e) {
        console.error('Erro ao carregar pedidos:', e)
        this.lista = []
      } finally {
        this.loading = false
      }
      // lista.status = toLowerCase(status)
      console.log('status pedidos', this.lista)
    },

    formatarData(data) {
      if (!data) return '-'
      try {
        return new Date(data).toLocaleDateString('pt-BR')
      } catch {
        return String(data)
      }
    },
    formatarDataHora(data) {
      if (!data) return '-'
      try {
        return new Date(data).toLocaleString('pt-BR')
      } catch {
        return String(data)
      }
    },
    async togglePedido(pedido) {
      const pedidoId = pedido.id || pedido.pedido_consumivel_id
      
      if (this.pedidoExpandido === pedidoId) {
        this.pedidoExpandido = null
        this.itensPedido = []
        return
      }

      this.pedidoExpandido = pedidoId
      await this.carregarItensPedido(pedido)
    },
    async carregarItensPedido(pedidoBasico) {
      this.carregandoItens = true
      this.itensPedido = []
      
      try {
        let pedidoDetalhe = pedidoBasico
        
        if (!pedidoBasico?.itens || !pedidoBasico.itens.length) {
          const id = pedidoBasico.id || pedidoBasico.pedido_consumivel_id
          const { data } = await axios.get(`/pedido-consumivel/${id}`)
          pedidoDetalhe = data.data || data
        }

        const itens = Array.isArray(pedidoDetalhe.itens) ? pedidoDetalhe.itens : []
        this.itensPedido = this.mapItensPedidoParaItensLocacao(itens)
      } catch (e) {
        console.error('Erro ao carregar itens do pedido:', e)
        this.itensPedido = []
      } finally {
        this.carregandoItens = false
      }
    },
    mapItensPedidoParaItensLocacao(itens) {
      return itens.map((i) => {
        const produto = i.produto || i.consumivel || i.item || {}
        const produtoId = parseInt(i.produtos_consumivel_id ?? produto.id ?? i.id)
        const qtdSolicitada = parseInt(i.quantidade_solicitada ?? i.quantidade ?? i.qtd ?? 1)

        return {
          id: produtoId,
          produtos_consumivel_id: produtoId,
          nome: produto.nome || i.nome || `Produto ${produtoId}`,
          foto: produto.foto || null,
          qtdParaLocacao: isNaN(qtdSolicitada) ? 1 : qtdSolicitada,
          quantidade: qtdSolicitada,
          uniqueId: Date.now() + Math.random()
        }
      })
    },
    async confirmarPedido(pedido) {
      if (!confirm('Deseja confirmar este pedido?')) return

      this.processando = true
      try {
        const pedidoId = pedido.id || pedido.pedido_consumivel_id
        await axios.post(`/confirmar-pedido-consumivel/${pedidoId}`)
        
        alert('Pedido confirmado com sucesso!')
        await this.carregarPedidos()
        
        // Reabrir o pedido se estava expandido
        if (this.pedidoExpandido === pedidoId) {
          const pedidoAtualizado = this.lista.find(p => (p.id || p.pedido_consumivel_id) === pedidoId)
          if (pedidoAtualizado) {
            await this.carregarItensPedido(pedidoAtualizado)
          }
        }
      } catch (e) {
        console.error('Erro ao confirmar pedido:', e)
        alert(e.response?.data?.erro || 'Erro ao confirmar pedido')
      } finally {
        this.processando = false
      }
    },
    abrirModalCancelarPedido(pedido) {
      this.modalCancelamento = {
        mostrar: true,
        pedidoId: pedido.id || pedido.pedido_consumivel_id
      }
    },
    async handleCancelarPedido({ pedidoId, observacao }) {
      this.processando = true
      this.modalCancelamento.mostrar = false
      
      try {
        await axios.delete(`/deletar-pedido-consumivel/${pedidoId}, ${observacao}`
        /* , {
          data: { observacao_cancelamento: observacao }
        } */
        )
        
        alert('Pedido cancelado com sucesso!')
        await this.carregarPedidos()
        
        if (this.pedidoExpandido === pedidoId) {
          const pedidoAtualizado = this.lista.find(p => (p.id || p.pedido_consumivel_id) === pedidoId)
          if (pedidoAtualizado) {
            await this.carregarItensPedido(pedidoAtualizado)
          }
        }
      } catch (e) {
        console.error('Erro ao cancelar pedido:', e)
        alert(e.response?.data?.erro || 'Erro ao cancelar pedido')
      } finally {
        this.processando = false
      }
    }
  }
}
</script>

<style scoped>
.expand-enter-active,
.expand-leave-active {
  transition: all 0.3s ease;
  overflow: hidden;
}

.expand-enter-from,
.expand-leave-to {
  max-height: 0;
  opacity: 0;
}

.expand-enter-to,
.expand-leave-from {
  max-height: 1000px;
  opacity: 1;
}

.rotate-180 {
  transform: rotate(180deg);
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.animate-spin {
  animation: spin 1s linear infinite;
}
</style>