<template>
  <transition name="fade">
    <div v-if="mostrar" class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6">
      <div class="fixed inset-0 bg-black/40" @click.self="fechar"></div>

      <div class="relative w-full max-w-4xl bg-white rounded-xl shadow-xl overflow-hidden">
        <header class="bg-gradient-to-r from-green-500 to-green-600 p-6 text-white flex items-center justify-between">
          <div>
            <h1 class="text-2xl font-bold">Pedidos Realizados</h1>
            <p class="text-white/80 text-sm">Selecione um pedido para recuperar seus itens</p>
          </div>
          <button @click="fechar" class="p-2 bg-white/10 rounded-lg hover:bg-white/20 transition-colors">
            <X class="w-5 h-5" />
          </button>
        </header>

        <main class="max-h-[70vh] overflow-auto p-6">
          <div v-if="loading" class="flex justify-center items-center py-12">
            <div class="w-8 h-8 border-4 border-green-200 border-t-green-600 rounded-full animate-spin"></div>
          </div>

          <div v-else>
            <div class="bg-gray-50 p-4 border border-gray-200 rounded-t-xl text-sm font-semibold text-gray-600 grid grid-cols-6 gap-4">
              <div>Data</div>
              <div>ID</div>
              <div class="col-span-2">Observação</div>
              <div>Itens</div>
              <div>Status</div>
            </div>

            <div class="border-x border-b border-gray-200 rounded-b-xl overflow-y-auto">
              <button
                v-for="pedido in pedidosFiltrados"
                :key="pedido.id || pedido.pedido_consumivel_id"
                @click="selecionarPedido(pedido)"
                class="w-full text-left p-4 grid grid-cols-6 gap-4 items-center hover:bg-gray-50 transition-colors border-b last:border-b-0"
              >
                <div class="text-sm text-gray-800">
                  {{ formatarData(pedido.data_pedido) }}
                </div>
                <div class="text-sm font-medium text-gray-800">
                  #{{ pedido.id || pedido.pedido_consumivel_id }}
                </div>
                <div class="text-sm col-span-2 text-gray-600 truncate" :title="pedido.observacao || '-'">
                  {{ pedido.observacoes || '-' }}
                </div>
                <div class="text-sm">
                  <span class="inline-flex items-center gap-2 px-2 py-1 bg-green-100 text-green-700 rounded-full font-medium">
                    {{ (pedido.itens && pedido.itens.length) ? pedido.itens.length : (pedido.total_itens || 0) }}
                  </span>
                </div>
                <div class="text-sm">
                  <span class="px-2 py-1 rounded-full"
                        :class="{
                          'bg-yellow-100 text-yellow-700': pedido.status === 'Pendente',
                          'bg-green-100 text-green-700': pedido.status === 'Concluído',
                          'bg-blue-100 text-blue-700': !pedido.status || (pedido.status !== 'Pendente' && pedido.status !== 'Concluído' && pedido.status !== 'Cancelado')
                        }">
                    {{ pedido.status || 'Pendente' }}
                  </span>
                </div>
              </button>

              <div v-if="pedidosFiltrados.length === 0" class="text-center py-12">
                <History class="w-12 h-12 text-gray-300 mx-auto mb-4" />
                <p class="text-gray-500">Nenhum pedido encontrado</p>
              </div>
            </div>
          </div>
        </main>

        <div class="border-t border-gray-200 p-6 flex justify-end">
          <button @click="fechar" class="px-6 py-3 bg-green-600 text-white hover:bg-green-700 transition-colors rounded-xl">
            Fechar
          </button>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
import axios from 'axios'
import { History, X } from 'lucide-vue-next'

export default {
  name: 'ModalHistoricoPedidos',
  components: { History, X },
  props: {
    mostrar: { type: Boolean, default: false },
    pedidos: { type: Array, default: () => [] }
  },
  data() {
    return {
      loading: false,
      lista: []
    }
  },
  computed: {
    pedidosFiltrados() {
      // Filtra pedidos cancelados
      return this.lista.filter(pedido => {
        const status = (pedido.status || 'Pendente').toLowerCase()
        return status !== 'cancelado'
      })
    }
  },
  watch: {
    mostrar(val) {
      if (val) {
        this.carregarPedidos()
      }
    },
    pedidos: {
      immediate: true,
      handler(novos) {
        if (Array.isArray(novos) && novos.length && !this.loading) {
          this.lista = novos
        }
      }
    }
  },
  methods: {
    fechar() {
      this.$emit('fechar')
    },
    async carregarPedidos() {
      this.loading = true
      try {
        const { data } = await axios.get('/pedido-consumivel', {
          params: { 
            per_page: 10000 // Busca até 10.000 pedidos para garantir todos os dados
          }
        })
        this.lista = Array.isArray(data.data) ? data.data : []
        // Ordena pelo ID em ordem descendente (maior para menor)
        this.lista.sort((a, b) => (b.id || b.pedido_consumivel_id || 0) - (a.id || a.pedido_consumivel_id || 0))
      } catch (e) {
        console.error('Erro ao carregar pedidos:', e)
        this.lista = Array.isArray(this.pedidos) ? this.pedidos : []
        // Ordena também o fallback
        this.lista.sort((a, b) => (b.id || b.pedido_consumivel_id || 0) - (a.id || a.pedido_consumivel_id || 0))
      } finally {
        this.loading = false
      }
    },
    formatarData(data) {
      if (!data) return '-'
      try {
        return new Date(data).toLocaleDateString('pt-BR')
      } catch {
        return String(data)
      }
    },
    selecionarPedido(pedido) {
      this.$emit('selecionar-pedido', pedido)
      this.fechar()
    }
  }
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity .2s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>