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
      <div class="bg-gradient-to-r from-green-400 to-green-600 p-6 text-white relative">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
        
        <div class="relative z-10 flex items-center justify-between">
          <div class="flex items-center gap-4">
            <div class="p-3 bg-white/20 rounded-xl">
              <History class="w-6 h-6" />
            </div>
            <div>
              <h2 class="text-2xl font-bold">Histórico de Entradas</h2>
              <p class="text-white/80 text-sm">Visualize todas as entradas registradas no estoque</p>
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
            @click="activeTab = 'patrimonio'; carregarHistorico()"
            :class="[
              'flex-1 py-4 px-6 text-sm font-medium transition-colors border-b-2',
              activeTab === 'patrimonio' 
                ? 'text-blue-600 border-blue-500 bg-blue-50' 
                : 'text-gray-500 border-transparent hover:text-gray-700 hover:bg-gray-50'
            ]"
          >
            <div class="flex items-center justify-center gap-2">
              <Flower class="w-4 h-4" />
              <span>Decorações</span>
            </div>
          </button>

        </div>
      </div>

      <!-- Conteúdo do Modal -->
      <div class="p-6">
        
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
                      v-if="entrada.produto.foto"
                      :src="entrada.produto.foto"
                      :alt="entrada.produto.nome"
                      class="w-full h-full object-cover rounded-lg"
                    />
                    <Package v-else class="w-4 h-4 text-gray-400" />
                  </div>
                  <div class="min-w-0">
                    <p class="font-medium text-gray-800 truncate">{{ entrada.produto.nome }}</p>
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
                <div class="text-sm">
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

        <div class="flex gap-3">
          <button
            @click="closeModal"
            class="px-6 py-3 bg-green-600 text-white hover:bg-green-700 transition-colors rounded-xl"
          >
            Fechar
          </button>
        </div>
      </div>

    </div>
  </div>
</template>

<script>
import axios from 'axios'
import { History, X, Search, Package, Plus, Flower, Palette } from 'lucide-vue-next'
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
  name: 'ModalHistoricoEntradas',
  components: {
    History,
    X,
    Search,
    Package,
    Plus,
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
      loadingHistorico: false,
      
      // Dados da paginação
      historico: [],
      currentPage: 1,
      lastPage: 1,
      total: 0,
      perPage: 10,
      
      // Debounced search function
      debouncedCarregarHistorico: () => { },
    };
  },
  mounted() {
    this.carregarHistorico();
    console.log(this.historico)
    
    // Setup debounced search
    this.debouncedCarregarHistorico = debounce(() => {
      this.carregarHistorico(1);
    }, 500);
  },
  watch: {
    searchTerm() {
      if (typeof this.debouncedCarregarHistorico === 'function') {
        this.debouncedCarregarHistorico();
      }
    },
    
    isOpen(newVal) {
      if (newVal) {
        this.carregarHistorico();
      }
    }
  },
  methods: {
    async carregarHistorico(page = 1) {
      this.loadingHistorico = true;
      
      try {
        const params = {
          page: page,
          per_page: this.perPage,
        };

        if (this.searchTerm.trim()) {
          params.produto = this.searchTerm.trim();
        }

        const response = await axios.get(`/entrada-${this.activeTab}`, { params });

        this.historico = response.data.data;
        console.log(this.historico);
        this.currentPage = response.data.current_page;
        this.lastPage = response.data.last_page;
        this.total = response.data.total;

      } catch (error) {
        console.error("Erro ao carregar histórico:", error);
        this.historico = [];
      } finally {
        this.loadingHistorico = false;
      }
    },

    mudarPagina(page) {
      if (page >= 1 && page <= this.lastPage) {
        this.currentPage = page;
        this.carregarHistorico(page);
      }
    },

    formatPreco(valor) {
      return parseFloat(valor).toLocaleString('pt-BR', { minimumFractionDigits: 2 });
    },

    formatarData(data) {
      return new Date(data).toLocaleDateString('pt-BR', {timeZone: 'UTC'});
    },   
    closeModal() {
      this.$emit('close');
    }
  }
};
</script>