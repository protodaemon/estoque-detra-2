<template>
  <!-- Modal Overlay -->
  <div 
    class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4"
    @click="closeModal"
  >
    <!-- Modal Container -->
    <div 
      class="bg-white rounded-2xl shadow-2xl w-full max-w-[85vw] max-h-[95vh] overflow-y-auto overflow-x-hidden"
      @click.stop
    >
      
      <!-- Header do Modal -->
      <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-6 text-white relative">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
        
        <div class="relative z-10 flex items-center justify-between">
          <div class="flex items-center gap-4">
            <div class="p-3 bg-white/20 rounded-xl">
              <TrendingUp class="w-6 h-6" />
            </div>
            <div>
              <h2 class="text-2xl font-bold">Histórico de Movimentações</h2>
              <p class="text-white/80 text-sm">Acompanhe todas as movimentações dos produtos do patrimônio</p>
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

      <!-- Conteúdo do Modal -->
      <div class="p-6">
        
        <!-- Filtros -->
        <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
          <!-- Busca por produto -->
          <div class="relative">
            <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-4 h-4" />
            <input
              v-model="searchTerm"
              type="text"
              placeholder="Buscar por produto..."
              class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-400 focus:ring-4 focus:ring-blue-100 transition-all"
            />
          </div>

          <!-- Filtro por tipo -->
          <select
            v-model="tipoFiltro"
            class="px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-400 focus:ring-4 focus:ring-blue-100 transition-all"
          >
            <option value="">Todos os tipos</option>
            <option value="criacao">Criação</option>
            <option value="exclusao">Exclusão</option>
            <option value="mudanca_localizacao">Mudança de Localização</option>
          </select>

          <!-- Filtro por data -->
          <input
            v-model="dataFiltro"
            type="date"
            class="px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-400 focus:ring-4 focus:ring-blue-100 transition-all"
          />
        </div>

        <!-- Loading -->
        <div v-if="loadingHistorico" class="flex justify-center items-center py-12">
          <div class="w-8 h-8 border-4 border-blue-200 border-t-blue-500 rounded-full animate-spin"></div>
        </div>

        <!-- Lista de Movimentações -->
        <div v-else class="bg-white rounded-xl border border-gray-200 overflow-hidden">
          <!-- Header da tabela -->
          <div class="bg-gray-50 p-4 border-b border-gray-200">
            <div class="grid grid-cols-12 gap-4 text-sm font-semibold text-gray-600">
              <div class="col-span-2">Data/Hora</div>
              <div class="col-span-2">Responsável</div>
              <div class="col-span-2">Produto</div>
              <div class="col-span-2">Tipo</div>
              <div class="col-span-2">Localização Anterior</div>
              <div class="col-span-2">Localização Nova</div>
              <div class="col-span-2">Observações</div>
            </div>
          </div>

          <!-- Linhas do histórico -->
          <div class="max-h-[500px] overflow-y-auto">
            <div
              v-for="movimentacao in movimentacoes"
              :key="movimentacao.movimentacao_id"
              class="p-4 border-b border-gray-100 hover:bg-gray-50 transition-colors"
            >
              <div class="grid grid-cols-12 gap-4 items-center">
                <!-- Data/Hora -->
                <div class="col-span-2 text-sm">
                  <p class="font-medium text-gray-800">{{ formatarData(movimentacao.data_movimentacao) }}</p>
                  <p class="text-xs text-gray-500">{{ formatarHora(movimentacao.data_movimentacao) }}</p>
                </div>

                <div class="col-span-2 text-sm flex items-center gap-2">
                    
                    <span class="text-gray-700 truncate" :title="movimentacao.responsavel?.name">
                        {{ movimentacao.responsavel?.nome || 'Sistema' }}
                    </span>
                </div>

                <!-- Produto -->
                <div class="col-span-2 flex items-center gap-3">
                  <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <img
                      v-if="movimentacao.produto.foto"
                      :src="movimentacao.produto.foto"
                      :alt="movimentacao.produto.nome"
                      class="w-full h-full object-cover rounded-lg"
                    />
                    <Package v-else class="w-4 h-4 text-gray-400" />
                  </div>
                  <div class="min-w-0">
                    <p class="font-medium text-gray-800 truncate">{{ movimentacao.produto.nome }}</p>
                    <p class="text-xs text-gray-500">{{ movimentacao.produto.numero_identificacao }}</p>
                  </div>
                </div>

                <!-- Tipo de Movimentação -->
                <div class="col-span-2 text-sm">
                  <span 
                    :class="getBadgeClass(movimentacao.tipo_movimentacao)"
                    class="inline-flex items-center gap-1 px-3 py-1 rounded-full font-medium text-xs"
                  >
                    <component :is="getIcon(movimentacao.tipo_movimentacao)" class="w-3 h-3" />
                    {{ getTipoLabel(movimentacao.tipo_movimentacao) }}
                  </span>
                </div>

                <!-- Localização Anterior -->
                <div class="col-span-2 text-sm">
                  <div v-if="movimentacao.localizacao_anterior" class="flex items-center gap-2">
                    <MapPin class="w-3 h-3 text-gray-400" />
                    <span class="text-gray-700">{{ movimentacao.localizacao_anterior.nome }}</span>
                  </div>
                  <span v-else class="text-gray-400">-</span>
                </div>

                <!-- Localização Nova -->
                <div class="col-span-2 text-sm">
                  <div v-if="movimentacao.localizacao_nova" class="flex items-center gap-2">
                    <MapPin class="w-3 h-3 text-green-500" />
                    <span class="text-gray-700 font-medium">{{ movimentacao.localizacao_nova.nome }}</span>
                  </div>
                  <span v-else class="text-gray-400">-</span>
                </div>

                <!-- Observações -->
                <div class="col-span-2 text-sm">
                  <p class="text-gray-600 break-words" :title="movimentacao.observacao">
                    {{ movimentacao.observacao || '-' }}
                  </p>
                </div>
              </div>
            </div>
          </div>

          <!-- Mensagem quando não há movimentações -->
          <div v-if="movimentacoes.length === 0 && !loadingHistorico" class="text-center py-12">
            <TrendingUp class="w-12 h-12 text-gray-300 mx-auto mb-4" />
            <p class="text-gray-500 mb-2">
              {{ searchTerm || tipoFiltro || dataFiltro ? 'Nenhuma movimentação encontrada' : 'Nenhuma movimentação registrada' }}
            </p>
            <p class="text-sm text-gray-400">
              {{ searchTerm || tipoFiltro || dataFiltro ? 'Tente ajustar os filtros' : 'As movimentações aparecerão aqui quando forem registradas' }}
            </p>
          </div>
        </div>

        <!-- Estatísticas -->
        <div v-if="!loadingHistorico && movimentacoes.length > 0" class="mt-6 grid grid-cols-3 gap-4">
          <div class="bg-green-50 border border-green-200 rounded-xl p-4">
            <div class="flex items-center gap-3">
              <div class="p-2 bg-green-100 rounded-lg">
                <PlusCircle class="w-5 h-5 text-green-600" />
              </div>
              <div>
                <p class="text-sm text-gray-600">Total de Criações</p>
                <p class="text-2xl font-bold text-green-700">{{ estatisticas.criacoes }}</p>
              </div>
            </div>
          </div>

          <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
            <div class="flex items-center gap-3">
              <div class="p-2 bg-blue-100 rounded-lg">
                <MoveHorizontal class="w-5 h-5 text-blue-600" />
              </div>
              <div>
                <p class="text-sm text-gray-600">Mudanças de Local</p>
                <p class="text-2xl font-bold text-blue-700">{{ estatisticas.mudancas }}</p>
              </div>
            </div>
          </div>

          <div class="bg-red-50 border border-red-200 rounded-xl p-4">
            <div class="flex items-center gap-3">
              <div class="p-2 bg-red-100 rounded-lg">
                <Trash2 class="w-5 h-5 text-red-600" />
              </div>
              <div>
                <p class="text-sm text-gray-600">Total de Exclusões</p>
                <p class="text-2xl font-bold text-red-700">{{ estatisticas.exclusoes }}</p>
              </div>
            </div>
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

        <div class="flex gap-3 ml-auto">
          
          <button
            @click="closeModal"
            class="px-6 py-3 bg-blue-600 text-white hover:bg-blue-700 transition-colors rounded-xl font-medium"
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
import { 
  TrendingUp, 
  X, 
  Search, 
  Package, 
  MapPin,
  PlusCircle,
  Trash2,
  MoveHorizontal,
  Download
} from 'lucide-vue-next'
import Paginacao from '@/components/Paginacao.vue'

function debounce(fn, delay = 500) {
  let timeout;
  return function (...args) {
    clearTimeout(timeout);
    timeout = setTimeout(() => fn.apply(this, args), delay);
  };
}

export default {
  name: 'ModalHistoricoMovimentacoes',
  components: {
    TrendingUp,
    X,
    Search,
    Package,
    MapPin,
    PlusCircle,
    Trash2,
    MoveHorizontal,
    Download,
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
      searchTerm: '',
      tipoFiltro: '',
      dataFiltro: '',
      loadingHistorico: false,
      
      movimentacoes: [],
      currentPage: 1,
      lastPage: 1,
      total: 0,
      perPage: 10,
      
      estatisticas: {
        criacoes: 0,
        mudancas: 0,
        exclusoes: 0
      },
      
      debouncedCarregarHistorico: () => {},
    };
  },
  mounted() {
    this.carregarHistorico();
    
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
    
    tipoFiltro() {
      this.carregarHistorico(1);
    },
    
    dataFiltro() {
      this.carregarHistorico(1);
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

        if (this.tipoFiltro) {
          params.tipo = this.tipoFiltro;
        }

        if (this.dataFiltro) {
          params.data_inicio = this.dataFiltro;
          params.data_fim = this.dataFiltro;
        }

        const response = await axios.get('/movimentacoes-patrimonio', { params });

        this.movimentacoes = response.data.data;
        this.currentPage = response.data.current_page;
        this.lastPage = response.data.last_page;
        this.total = response.data.total;

        this.calcularEstatisticas();

      } catch (error) {
        console.error("Erro ao carregar movimentações:", error);
        this.movimentacoes = [];
      } finally {
        this.loadingHistorico = false;
      }
    },

    calcularEstatisticas() {
      this.estatisticas = {
        criacoes: this.movimentacoes.filter(m => m.tipo_movimentacao === 'criacao').length,
        mudancas: this.movimentacoes.filter(m => m.tipo_movimentacao === 'mudanca_localizacao').length,
        exclusoes: this.movimentacoes.filter(m => m.tipo_movimentacao === 'exclusao').length,
      };
    },

    mudarPagina(page) {
      if (page >= 1 && page <= this.lastPage) {
        this.currentPage = page;
        this.carregarHistorico(page);
      }
    },

    getTipoLabel(tipo) {
      const labels = {
        'criacao': 'Criação',
        'exclusao': 'Exclusão',
        'mudanca_localizacao': 'Mudança de Local'
      };
      return labels[tipo] || tipo;
    },

    getBadgeClass(tipo) {
      const classes = {
        'criacao': 'bg-green-100 text-green-700',
        'exclusao': 'bg-red-100 text-red-700',
        'mudanca_localizacao': 'bg-blue-100 text-blue-700'
      };
      return classes[tipo] || 'bg-gray-100 text-gray-700';
    },

    getIcon(tipo) {
      const icons = {
        'criacao': 'PlusCircle',
        'exclusao': 'Trash2',
        'mudanca_localizacao': 'MoveHorizontal'
      };
      return icons[tipo] || 'Package';
    },

    formatarData(data) {
      return new Date(data).toLocaleDateString('pt-BR', { timeZone: 'UTC' });
    },

    formatarHora(data) {
      return new Date(data).toLocaleTimeString('pt-BR', { 
        hour: '2-digit', 
        minute: '2-digit',
        timeZone: 'UTC'
      });
    },

    

    closeModal() {
      this.$emit('close');
    }
  }
};
</script>