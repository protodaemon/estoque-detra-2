<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-gradient-to-r from-green-400 to-green-600 p-6 text-white">
      <div class="max-w-7xl mx-auto flex items-center justify-between">
        <div class="flex items-center gap-4">
<!--           <button @click="goBack" class="p-2 bg-white/10 rounded-lg hover:bg-white/20 transition-colors">
            <X class="w-5 h-5" />
          </button> -->
          <div>
            <h1 class="text-2xl font-bold">Histórico de Entradas</h1>
            <p class="text-white/80 text-sm">Visualize todas as entradas registradas no estoque</p>
          </div>
        </div>
      </div>
    </header>

    <!-- Conteúdo -->
    <main class="max-w-7xl mx-auto p-6">
      <!-- Tabs -->
<!--       <div class="mb-6 border-b border-gray-200">
        <div class="flex">
          <button
            @click="setTab('consumivel')"
            :class="[
              'flex-1 py-4 px-6 text-sm font-medium transition-colors border-b-2',
              activeTab === 'consumivel'
                ? 'text-green-600 border-green-500 bg-green-50'
                : 'text-gray-500 border-transparent hover:text-gray-700 hover:bg-gray-50'
            ]"
          >
            <div class="flex items-center justify-center gap-2">
              <Flower class="w-4 h-4" />
              <span>Consumíveis</span>
            </div>
          </button>
        </div>
      </div> -->

      <!-- Filtro de Busca -->
      <div class="mb-6">
        <div class="relative max-w-md">
          <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-4 h-4" />
          <input
            v-model="searchTerm"
            type="text"
            placeholder="Buscar por produto..."
            class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-green-400 focus:ring-4 focus:ring-green-100 transition-all"
          />
        </div>
      </div>

      <!-- Loading -->
      <div v-if="loadingHistorico" class="flex justify-center items-center py-12">
        <div class="w-8 h-8 border-4 border-green-200 border-t-green-500 rounded-full animate-spin"></div>
      </div>

      <!-- Lista de Histórico -->
      <div v-else class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <!-- Header da tabela -->
        <div class="bg-gray-50 p-4 border-b border-gray-200">
          <div class="grid grid-cols-6 gap-4 text-sm font-semibold text-gray-600">
            <div>Data</div>
            <div>Produto</div>
            <div>Quantidade</div>
            <div>Observações</div>
          </div>
        </div>

        <!-- Linhas do histórico -->
        <div class="max-h-96 overflow-y-auto">
          <div
            v-for="entrada in historico"
            :key="entrada.id"
            class="p-4 border-b border-gray-100 hover:bg-gray-50 transition-colors"
          >
            <div class="grid grid-cols-6 gap-4 items-center">
              <!-- Data -->
              <div class="text-sm">
                <p class="font-medium text-gray-800">{{ formatarData(entrada.data_entrada) }}</p>
              </div>

              <!-- Produto -->
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                  <img
                    v-if="entrada.produto?.foto"
                    :src="entrada.produto.foto"
                    :alt="entrada.produto.nome"
                    class="w-full h-full object-cover rounded-lg"
                  />
                  <Package v-else class="w-4 h-4 text-gray-400" />
                </div>
                <div class="min-w-0">
                  <p class="font-medium text-gray-800 truncate">{{ entrada.produto?.nome }}</p>
                  <p class="text-xs text-gray-500">{{ entrada.categoria_nome }}</p>
                </div>
              </div>

              <!-- Quantidade -->
              <div class="text-sm">
                <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 text-green-700 rounded-full font-medium">
                  <Plus class="w-3 h-3" />
                  {{ entrada.quantidade }}
                </span>
              </div>

              <!-- Observações -->
              <div class="text-sm col-span-2">
                <p class="text-gray-600 truncate" :title="entrada.observacao">
                  {{ entrada.observacao || '-' }}
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- Mensagem quando não há histórico -->
        <div v-if="historico.length === 0 && !loadingHistorico" class="text-center py-12">
          <History class="w-12 h-12 text-gray-300 mx-auto mb-4" />
          <p class="text-gray-500 mb-2">
            {{ searchTerm ? 'Nenhuma entrada encontrada' : 'Nenhuma entrada registrada' }}
          </p>
          <p class="text-sm text-gray-400">
            {{ searchTerm ? 'Tente ajustar o termo de busca' : 'As entradas aparecerão aqui quando forem registradas' }}
          </p>
        </div>
      </div>

      <!-- Footer com paginação e voltar -->
      <div class="border-t border-gray-200 p-6 flex items-center justify-between gap-4 mt-6">
        <Paginacao
          v-if="lastPage > 1"
          :paginaAtual="currentPage"
          :totalPaginas="lastPage"
          @atualizar-pagina="mudarPagina"
          class="!static !bottom-auto !right-auto !shadow-none"
        />

        <div class="flex gap-3">
          <button
            @click="goBack"
            class="px-6 py-3 bg-green-600 text-white hover:bg-green-700 transition-colors rounded-xl"
          >
            Voltar
          </button>
        </div>
      </div>
    </main>
  </div>
</template>

<script>
import axios from 'axios'
import { History, X, Search, Package, Plus, Flower } from 'lucide-vue-next'
import Paginacao from '@/components/Paginacao.vue'

function debounce(fn, delay = 500) {
  let timeout
  return function (...args) {
    clearTimeout(timeout)
    timeout = setTimeout(() => fn.apply(this, args), delay)
  }
}

export default {
  name: 'HistoricoEntradasPage',
  components: {
    History,
    X,
    Search,
    Package,
    Plus,
    Flower,
    Paginacao
  },
  data() {
    return {
      activeTab: 'consumivel',
      searchTerm: '',
      loadingHistorico: false,
      historico: [],
      currentPage: 1,
      lastPage: 1,
      total: 0,
      perPage: 10,
      debouncedCarregarHistorico: () => {}
    }
  },
  mounted() {
    this.carregarHistorico()
    this.debouncedCarregarHistorico = debounce(() => {
      this.carregarHistorico(1)
    }, 500)
  },
  watch: {
    searchTerm() {
      if (typeof this.debouncedCarregarHistorico === 'function') {
        this.debouncedCarregarHistorico()
      }
    }
  },
  methods: {
    goBack() {
      this.$router.back()
    },
    setTab(tab) {
      this.activeTab = tab === 'consumivel' ? 'consumivel' : 'consumivel'
      this.carregarHistorico(1)
    },
    async carregarHistorico(page = 1) {
      this.loadingHistorico = true
      try {
        const params = { page, per_page: this.perPage }
        if (this.searchTerm.trim()) params.produto = this.searchTerm.trim()

        const response = await axios.get(`/entrada-${this.activeTab}`, { params })
        this.historico = response.data.data || []
        this.currentPage = response.data.current_page || 1
        this.lastPage = response.data.last_page || 1
        this.total = response.data.total || 0
      } catch (error) {
        console.error('Erro ao carregar histórico:', error)
        this.historico = []
      } finally {
        this.loadingHistorico = false
      }
    },
    mudarPagina(page) {
      if (page >= 1 && page <= this.lastPage) {
        this.currentPage = page
        this.carregarHistorico(page)
      }
    },
    formatarData(data) {
      return new Date(data).toLocaleDateString('pt-BR', { timeZone: 'UTC' })
    }
  }
}
</script>

<style scoped>
/* small helpers */
.flex > .w-px { height: 100% }
button:hover { background-color: rgba(0, 0, 0, 0.02); }
</style>