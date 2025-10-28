<template>
  <!-- Modal Overlay -->
  <div 
    class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4"
    @click="closeModal"
  >
    <!-- Modal Container -->
    <div 
      class="bg-white rounded-2xl shadow-2xl w-full max-w-[70vw] max-h-[95vh] overflow-y-auto overflow-x-hidden"
      @click.stop
    >
      
      <!-- Header do Modal -->
      <div class="bg-gradient-to-r from-blue-400 to-blue-600 p-6 text-white relative">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
        
        <div class="relative z-10 flex items-center justify-between">
          <div class="flex items-center gap-4">
            <div class="p-3 bg-white/20 rounded-xl">
              <Plus class="w-6 h-6" />
            </div>
            <div>
              <h2 class="text-2xl font-bold">Registrar Entrada</h2>
              <p class="text-white/80 text-sm">Selecione o produto para dar entrada</p>
            </div>
          </div>
          
          <button 
            @click="closeModal"
            class="p-2 hover:bg-white/10 rounded-lg transition-colors"
          >
            <X class="w-6 h-6" />
          </button>
        </div>
      </div>

      <!-- Tabs de Categoria -->
      <div class="border-b border-gray-200">
        <div class="flex">
          
          
          <button
            @click="activeTab = 'patrimonio'; carregarProdutos()"
            :class="[
              'flex-1 py-4 px-6 text-sm font-medium transition-colors border-b-2',
              activeTab === 'patrimonio' 
                ? 'text-green-600 border-green-500 bg-green-50' 
                : 'text-gray-500 border-transparent hover:text-gray-700 hover:bg-gray-50'
            ]"
          >
            <div class="flex items-center justify-center gap-2">
              <Palette class="w-4 h-4" />
              <span>Patrimônios</span>
            </div>
          </button>
        </div>
      </div>

      <!-- Conteúdo do Modal -->
      <div class="p-6">
        
        <!-- Busca de Produtos -->
        <div class="mb-6">
          <div class="relative">
            <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-4 h-4" />
            <input
              v-model="searchTerm"
              type="text"
              placeholder="Buscar produto por nome..."
              class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-400 focus:ring-4 focus:ring-blue-100 transition-all"
            />
          </div>
        </div>

        <!-- Loading -->
        <div v-if="loadingProdutos" class="flex justify-center items-center py-12">
          <div class="w-8 h-8 border-4 border-blue-200 border-t-blue-500 rounded-full animate-spin"></div>
        </div>

        <!-- Lista de Produtos -->
        <div v-else class="max-h-64 overflow-y-auto mb-6">
          <div class="grid grid-cols-1 gap-3">
            <div
              v-for="produto in produtos"
              :key="getProdutoId(produto)"
              :class="[
                'border-2 rounded-xl p-4 cursor-pointer transition-all duration-200 flex items-center gap-4',
                selectedProduto && getProdutoId(selectedProduto) === getProdutoId(produto)
                  ? 'border-blue-400 bg-blue-50 shadow-md'
                  : 'border-gray-200 hover:border-gray-300 hover:shadow-sm'
              ]"
              @click="selectProduto(produto)"
            >
              <!-- Imagem do Produto -->
              <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <img
                  v-if="produto.foto"
                  :src="produto.foto"
                  :alt="produto.nome"
                  class="w-full h-full object-cover rounded-lg"
                />
                <Package v-else class="w-6 h-6 text-gray-400" />
              </div>
              
              <!-- Informações do Produto -->
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

          <!-- Mensagem quando não há produtos -->
          <div v-if="produtos.length === 0 && !loadingProdutos" class="text-center py-12">
            <Package class="w-12 h-12 text-gray-300 mx-auto mb-4" />
            <p class="text-gray-500">
              {{ searchTerm ? 'Nenhum produto encontrado' : 'Nenhum produto disponível' }}
            </p>
          </div>
        </div>
      </div>

      <!-- Seção de Entrada (aparece quando produto selecionado) -->
<div v-if="selectedProduto" class="border-t border-gray-200 bg-gray-50 p-6">
  <div class="flex items-center justify-between mb-4">
    <div>
      <h4 class="font-semibold text-gray-800">{{ selectedProduto.nome }}</h4>
      <p class="text-sm text-gray-500">Registrar entrada no estoque</p>
    </div>
    <div class="text-right">
      <p class="text-sm text-gray-500">Estoque atual</p>
      <p class="font-semibold text-gray-800">{{ selectedProduto.qtd_disponivel }} unidades</p>
    </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Campo Quantidade -->
    <div>
      <label class="block text-sm font-medium text-gray-700 mb-2">
        Quantidade a adicionar
      </label>
      <div class="flex items-center gap-2">
        <button
          @click="decrementQuantity"
          :disabled="quantidade <= 1"
          class="p-2 border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
        >
          <Minus class="w-4 h-4" />
        </button>
        
        <input
          v-model.number="quantidade"
          type="number"
          min="1"
          class="flex-1 text-center py-2 px-4 border-2 border-gray-200 rounded-lg focus:border-blue-400 focus:ring-4 focus:ring-blue-100 transition-all"
        />
        
        <button
          @click="incrementQuantity"
          class="p-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
        >
          <Plus class="w-4 h-4" />
        </button>
      </div>
      <p class="text-xs text-gray-500 mt-1">
        Novo estoque: {{ selectedProduto.qtd_disponivel + quantidade }} unidades
      </p>
    </div>


    <!-- Campo Data -->
    <div>
      <label class="block text-sm font-medium text-gray-700 mb-2">
        Data da entrada
      </label>
      <input
        v-model="dataEntrada"
        type="date"
        class="w-full py-2 px-4 border-2 border-gray-200 rounded-lg focus:border-blue-400 focus:ring-4 focus:ring-blue-100 transition-all"
      />
    </div>

    <!-- Campo Observações -->
    <div>
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
</div>

      <!-- Footer do Modal -->
      <div class="border-t border-gray-200 p-6 flex items-center justify-between gap-4">
        <!-- Paginação -->
          <Paginacao
            v-if="lastPage > 1" 
            :paginaAtual="currentPage" 
            :totalPaginas="lastPage" 
            @atualizar-pagina="mudarPagina" 
            class="!static !bottom-auto !right-auto !shadow-none"
          />
        <button
          @click="closeModal"
          class="px-6 py-3 text-gray-600 hover:text-gray-800 transition-colors"
        >
          Cancelar
        </button>
        
        <button
          @click="registrarEntrada"
          :disabled="!selectedProduto || loading"
          class="px-8 py-3 bg-gradient-to-r from-blue-400 to-blue-600 text-white font-semibold rounded-xl hover:from-blue-500 hover:to-blue-700 disabled:opacity-60 disabled:cursor-not-allowed transition-all duration-200 transform hover:scale-105 active:scale-95 flex items-center gap-2"
        >
          <div v-if="loading" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
          <Check v-else class="w-4 h-4" />
          {{ loading ? 'Registrando...' : 'Registrar Entrada' }}
        </button>
      </div>

    </div>
  </div>
</template>

<script>
import axios from 'axios'
import { Plus, X, Search, Package, Minus, Check, Flower, Palette } from 'lucide-vue-next'
import Paginacao from '@/components/Paginacao.vue'

// Debounce function
function debounce(fn, delay = 500) {
  let timeout;
  return function (...args) {
    clearTimeout(timeout);
    timeout = setTimeout(() => fn.apply(this, args), delay);
  };
}

export default {
  name: 'ModalEntradaProdutos',
  components: {
    Plus,
    X,
    Search,
    Package,
    Minus,
    Check,
    Flower,
    Palette,
    Paginacao
  },
  props: {
    isOpen: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      activeTab: 'patrimonio',
      searchTerm: '',
      selectedProduto: null,
      quantidade: 1,
      dataEntrada: new Date().toISOString().split('T')[0],
      observacoes: '',
      loading: false,
      loadingProdutos: false,
      
      // Dados da paginação
      produtos: [],
      currentPage: 1,
      lastPage: 1,
      total: 0,
      perPage: 10,
      
      // Debounced search function
      debouncedBuscarProdutos: () => { },
    };
  },
  mounted() {
    this.carregarProdutos();
    
    // Setup debounced search
    this.debouncedBuscarProdutos = debounce(() => {
      this.carregarProdutos(1);
    }, 500);
  },
  watch: {
    searchTerm() {
      if (typeof this.debouncedBuscarProdutos === 'function') {
        this.debouncedBuscarProdutos();
      }
    },
    
    isOpen(newVal) {
      if (newVal) {
        this.carregarProdutos();
      } else {
        this.resetModal();
      }
    }
  },
  methods: {
    async carregarProdutos(page = 1) {
      this.loadingProdutos = true;
      
      try {
        const params = {
          page: page,
          per_page: this.perPage,
        };

        if (this.searchTerm.trim()) {
          params.nome = this.searchTerm.trim();
        }

        const response = await axios.get(`/produtos-${this.activeTab}`, { params });

        this.produtos = response.data.data;
        this.currentPage = response.data.current_page;
        this.lastPage = response.data.last_page;
        this.total = response.data.total;
        console.log(this.produtos)

        // Se o produto selecionado não está na página atual, limpar seleção
        if (this.selectedProduto && !this.produtos.find(p => p.produtos_decoracao_id === this.selectedProduto.produtos_decoracao_id)) {
          this.selectedProduto = null;
          this.quantidade = 1;
        }

      } catch (error) {
        console.error("Erro ao carregar produtos:", error);
        this.produtos = [];
      } finally {
        this.loadingProdutos = false;
      }
    },

    mudarPagina(page) {
      if (page >= 1 && page <= this.lastPage) {
        this.currentPage = page;
        this.carregarProdutos(page);
      }
    },

    formatPreco(valor) {
      return parseFloat(valor).toLocaleString('pt-BR', { minimumFractionDigits: 2 });
    },
    formatarPrecoUnitario(e) {
    let valor = e.target.value.replace(/\D/g, '');
    if(!valor) {
      this.valorUnitarioFormatado = '';
      this.valorUnitario = 0;
      return;
    }
    valor = (parseInt(valor, 10) / 100).toFixed(2);
    let numero = parseFloat(valor);
    if(numero > 999999.99) {
      numero = 999999.99;
    }
    this.valorUnitario = numero; // valor numérico para cálculos
    this.valorUnitarioFormatado = numero.toFixed(2).replace('.', ',');
  },

  calcularTotal() {
    if (this.valorUnitario && this.quantidade) {
      return (this.valorUnitario * this.quantidade).toFixed(2).replace('.', ',');
    }
    return '0,00';
  },
    
    selectProduto(produto) {
      this.selectedProduto = produto;
      this.quantidade = 1;
    },
    
    incrementQuantity() {
      this.quantidade++;
    },
    
    decrementQuantity() {
      if (this.quantidade > 1) {
        this.quantidade--;
      }
    },
    closeModal(){
      this.$emit('close')
    },
    async registrarEntrada() {
      if (!this.selectedProduto) return;
      
      this.loading = true;
      
      try {
        // Dados para enviar à API
        let entradaData = {}
        if(this.activeTab === 'decoracao'){
         entradaData = {
          produto_decoracao_id: this.selectedProduto.produtos_decoracao_id,
          quantidade: this.quantidade,
          valor_unitario: this.valorUnitario,
          data_entrada: this.dataEntrada,
          observacao: this.observacoes,
        };
      }else{
         entradaData = {
          produto_patrimonio_id: this.selectedProduto.produtos_patrimonio_id,
          quantidade: this.quantidade,
          data_entrada: this.dataEntrada,
          observacao: this.observacoes,
        };
      }
        console.log(entradaData)
        const response = await axios.post(`/entrada-${this.activeTab}`, entradaData);
        
        console.log('Registrando entrada:', response.data);
        // Fechar modal
        this.closeModal();
      } catch (error) {
        console.error('Erro ao registrar entrada:', error);
      } finally {
        this.loading = false;
      }
    },
    
    resetModal() {
      this.searchTerm = '';
      this.selectedProduto = null;
      this.quantidade = 1;
      this.observacoes = '';
      this.loading = false;
      this.currentPage = 1;
    }
  },
computed: {
  getProdutoId() {
    return (produto) => {
      return this.activeTab === 'decoracao' 
        ? produto.produtos_decoracao_id 
        : produto.produtos_patrimonio_id
    }
  },
}
};
</script>

<style scoped>
</style>