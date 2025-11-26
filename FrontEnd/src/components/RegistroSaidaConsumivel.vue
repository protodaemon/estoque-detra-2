<template>
  <div class="min-h-screen bg-gray-50">
    <header class="bg-gradient-to-r from-blue-400 to-blue-600 p-6 text-white">
      <div class="max-w-7xl mx-auto flex items-center justify-between">
        <div class="flex items-center gap-4">
<!--           <button @click="goBack"
                  class="p-2 bg-white/10 rounded-lg hover:bg-white/20 transition-colors">
            <X class="w-5 h-5" />
          </button> -->
          <div>
            <h1 class="text-2xl font-bold">Registrar Saida</h1>
            <p class="text-white/80 text-sm">Selecione o produto para registrar saída</p>
          </div>
        </div>
      </div>
    </header>

    <main class="max-w-7xl mx-auto p-6"> <!-- pb-32 evita que o footer fixe sobreponha o conteúdo -->
      <!-- Tabs -->
<!--       <div class="mb-6 border-b border-gray-200">
        <div class="flex relative">
          <button
            @click="setTab('consumivel')"
            :class="tabClass('consumivel')"
            class="flex-1 py-4 px-6 text-sm font-medium transition-colors border-b-2 rounded-tr-xl"
          >
            <div class="flex items-center justify-center gap-2">
              <Palette class="w-4 h-4" />
              <span>Consumível</span>
            </div>
          </button>

          <div class="w-px bg-gray-300"></div>

          <button
            @click="setTab('historico')"
            :class="tabClass('historico')"
            class="flex-1 py-4 px-6 text-sm font-medium transition-colors border-b-2 rounded-tr-xl"
          >
            <div class="flex items-center justify-center gap-2">
              <Palette class="w-4 h-4" />
              <span>Histórico</span>
            </div>
          </button>
        </div>
      </div> -->

      <!-- Search -->
      <div class="mb-6">
        <div class="relative max-w-2xl">
          <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-4 h-4" />
          <input
            v-model="searchTerm"
            type="text"
            placeholder="Buscar produto por nome..."
            class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-400 focus:ring-4 focus:ring-blue-100 transition-all"
          />
        </div>
      </div>

      <!-- Produtos / Loading -->
      <div v-if="loadingProdutos" class="flex justify-center items-center py-12">
        <div class="w-8 h-8 border-4 border-blue-200 border-t-blue-500 rounded-full animate-spin"></div>
      </div>

      <div v-else class="grid grid-cols-1 gap-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">
          <div
            v-for="produto in produtos"
            :key="getProdutoId(produto)"
            @click="selectProduto(produto)"
            :class="produtoCardClass(produto)"
            class="border-2 rounded-xl p-4 cursor-pointer transition-all duration-200 flex items-center gap-4"
          >
            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
              <img v-if="produto.foto" :src="produto.foto" :alt="produto.nome" class="w-full h-full object-cover rounded-lg" />
              <Package v-else class="w-6 h-6 text-gray-400" />
            </div>

            <div class="flex-1">
              <h3 class="font-semibold text-gray-800">{{ produto.nome }}</h3>
              <p class="text-sm text-gray-500 mb-2">{{ produto.categoria_nome }}</p>
              <div class="flex items-center gap-3">
                <span v-if="activeTab === 'decoracao'" class="text-xs text-gray-500">
                  R$ {{ formatPreco(produto.valor_locacao) }}
                </span>
              </div>
            </div>
          </div>
        </div>

        <div v-if="produtos.length === 0 && !loadingProdutos" class="text-center py-12">
          <Package class="w-12 h-12 text-gray-300 mx-auto mb-4" />
          <p class="text-gray-500">
            {{ searchTerm ? 'Nenhum produto encontrado' : 'Nenhum produto disponível' }}
          </p>
        </div>
      </div>

      <!-- Seção de Saida -->
      <div v-if="selectedProduto" class="mt-6 border-t border-gray-200 bg-gray-50 p-6 rounded-xl">
        <div class="flex items-center justify-between mb-4">
          <div>
            <h4 class="font-semibold text-gray-800">{{ selectedProduto.nome }}</h4>
            <p class="text-sm text-gray-500">Registrar saida no estoque</p>
          </div>
          <div class="text-right">
            <p class="text-sm text-gray-500">Estoque atual</p>
            <p class="font-semibold text-gray-800">{{ selectedProduto.quantidade }} unidades</p>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Quantidade a retirar</label>
            <div class="flex items-center gap-2">
              <button @click="decrementQuantity" :disabled="quantidade <= 1"
                      class="p-2 border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                <Minus class="w-4 h-4" />
              </button>

              <input v-model.number="quantidade" type="number" min="1"
                     class="flex-1 text-center py-2 px-4 border-2 border-gray-200 rounded-lg focus:border-blue-400 focus:ring-4 focus:ring-blue-100 transition-all" />

              <button @click="incrementQuantity" class="p-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                <Plus class="w-4 h-4" />
              </button>
            </div>
            <p class="text-xs text-gray-500 mt-1">Novo estoque: {{ selectedProduto.quantidade + quantidade }} unidades</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Data da saida</label>
            <input v-model="dataSaida" type="date" class="w-full py-2 px-4 border-2 border-gray-200 rounded-lg focus:border-blue-400 focus:ring-4 focus:ring-blue-100 transition-all" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Observações (opcional)</label>
            <input v-model="observacoes" type="text" placeholder="Ex: Fornecedor X, lote Y..." class="w-full py-2 px-4 border-2 border-gray-200 rounded-lg focus:border-blue-400 focus:ring-4 focus:ring-blue-100 transition-all" />
          </div>
        </div>

        <!-- moved to fixed footer -->
      </div>
    </main>

    <!-- Footer fixo com paginação e botão de ação -->
    <footer class="bottom-0 left-0 right-0 bg-white border-t border-gray-200 py-4 z-40">
      <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center justify-center gap-4">
        <Paginacao
          v-if="lastPage > 1"
          :paginaAtual="currentPage"
          :totalPaginas="lastPage"
          @atualizar-pagina="mudarPagina"
          class="!static !bottom-auto !right-auto !shadow-none"
        />

        <button
          @click="registrarSaida"
          :disabled="!selectedProduto || loading"
          class="px-8 py-3 bg-gradient-to-r from-blue-400 to-blue-600 text-white font-semibold rounded-xl hover:from-blue-500 hover:to-blue-700 disabled:opacity-60 disabled:cursor-not-allowed transition-all duration-200 transform hover:scale-105 active:scale-95 flex items-center gap-2"
        >
          <div v-if="loading" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
          <Check v-else class="w-4 h-4" />
          {{ loading ? 'Registrando...' : 'Registrar Saida' }}
        </button>
      </div>
    </footer>
  </div>
</template>

<script>
import axios from 'axios'
import { Plus, X, Search, Package, Minus, Check, Flower, Palette } from 'lucide-vue-next'
import Paginacao from '@/components/Paginacao.vue'

// Debounce util
function debounce(fn, delay = 500) {
  let timeout
  return function (...args) {
    clearTimeout(timeout)
    timeout = setTimeout(() => fn.apply(this, args), delay)
  }
}

export default {
  name: 'RegistroSaidaConsumivelPage',
  components: { Plus, X, Search, Package, Minus, Check, Flower, Palette, Paginacao },
  data() {
    return {
      activeTab: 'consumivel',
      searchTerm: '',
      selectedProduto: null,
      quantidade: 1,
      dataSaida: new Date().toISOString().split('T')[0],
      observacoes: '',
      loading: false,
      loadingProdutos: false,
      produtos: [],
      currentPage: 1,
      lastPage: 1,
      total: 0,
      perPage: 10,
      debouncedBuscarProdutos: () => {}
    }
  },
  mounted() {
    this.carregarProdutos()
    this.debouncedBuscarProdutos = debounce(() => this.carregarProdutos(1), 500)
  },
  watch: {
    searchTerm() {
      if (typeof this.debouncedBuscarProdutos === 'function') this.debouncedBuscarProdutos()
    }
  },
  methods: {
    goBack() {
      this.$router.back()
    },
    setTab(tab) {
      this.activeTab = tab === 'historico' ? 'historico' : 'consumivel'
      this.carregarProdutos(1)
    },
    tabClass(tab) {
      return this.activeTab === tab
        ? 'text-green-600 border-green-500 bg-green-50 rounded-tl-xl'
        : 'text-gray-500 border-transparent hover:text-gray-700 hover:bg-gray-50'
    },
    produtoCardClass(produto) {
      const selected = this.selectedProduto && this.getProdutoId(this.selectedProduto) === this.getProdutoId(produto)
      return selected ? 'border-blue-400 bg-blue-50 shadow-md' : 'border-gray-200 hover:border-gray-300 hover:shadow-sm'
    },
    async carregarProdutos(page = 1) {
      this.loadingProdutos = true
      try {
        const params = { page, per_page: this.perPage }
        if (this.searchTerm.trim()) params.nome = this.searchTerm.trim()

        let response = null
        if (this.activeTab === 'consumivel') {
          response = await axios.get(`/produtos-${this.activeTab}`, { params })
        } else if (this.activeTab === 'historico') {
          response = await axios.get(`/produtos-decoracao`, { params })
        }

        if (response && response.data) {
          this.produtos = response.data.data || []
          this.currentPage = response.data.current_page || 1
          this.lastPage = response.data.last_page || 1
          this.total = response.data.total || 0
        } else {
          this.produtos = []
        }

        if (this.selectedProduto) {
          const idField = this.activeTab === 'decoracao' ? 'produtos_decoracao_id' : 'produtos_consumivel_id'
          if (!this.produtos.find(p => p[idField] === this.selectedProduto[idField])) {
            this.selectedProduto = null
            this.quantidade = 1
          }
        }
      } catch (error) {
        console.error('Erro ao carregar produtos:', error)
        this.produtos = []
      } finally {
        this.loadingProdutos = false
      }
    },
    mudarPagina(page) {
      if (page >= 1 && page <= this.lastPage) {
        this.currentPage = page
        this.carregarProdutos(page)
      }
    },
    formatPreco(valor) {
      return parseFloat(valor).toLocaleString('pt-BR', { minimumFractionDigits: 2 })
    },
    selectProduto(produto) {
      this.selectedProduto = produto
      this.quantidade = 1
    },
    incrementQuantity() { this.quantidade++ },
    decrementQuantity() { if (this.quantidade > 1) this.quantidade-- },
    async registrarSaida() {
      if (!this.selectedProduto) return
      this.loading = true
      try {
        const saidaData = this.activeTab === 'decoracao'
          ? {
              produto_decoracao_id: this.selectedProduto.produtos_decoracao_id,
              quantidade: this.quantidade,
              valor_unitario: this.valorUnitario,
              data_saida: this.dataSaida,
              observacao: this.observacoes
            }
          : {
              produto_consumivel_id: this.selectedProduto.produtos_consumivel_id,
              quantidade: this.quantidade,
              data_saida: this.dataSaida,
              observacao: this.observacoes
            }

        const response = await axios.post(`/saida-${this.activeTab}`, saidaData)
        console.log('Registrando saida:', response.data)
        // this.$router.back()
      } catch (error) {
        console.error('Erro ao registrar saida:', error)
      } finally {
        this.loading = false
      }
    }
  },
  computed: {
    getProdutoId() {
      return produto => this.activeTab === 'decoracao' ? produto.produtos_decoracao_id : produto.produtos_consumivel_id
    }
  }
}
</script>

<style scoped>
.flex > .w-px { height: 100% }
button:hover { background-color: rgba(0, 0, 0, 0.02); }
</style>