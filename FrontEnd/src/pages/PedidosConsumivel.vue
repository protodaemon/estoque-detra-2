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
              <h1 class="text-2xl font-bold text-gray-800">Gerenciamento de Entradas</h1>
              
              <!-- Breadcrumbs -->
              <div class="flex items-center text-sm text-gray-600 mt-1 space-x-2">
                <span class="text-gray-400">Menu</span>
                <ChevronRight class="w-4 h-4" />
                <span class="text-gray-400">Menu Consumível</span>
                <ChevronRight class="w-4 h-4" />
                <span class="text-blue-600 font-medium">Pedidos</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Conteúdo Principal -->
    <div class="flex items-center justify-center min-h-[calc(90vh-80px)] p-6">
      <!-- Cards Principais Centralizados -->
      <div class="max-w-4xl w-full grid md:grid-cols-2 gap-8">
        
        <!-- Card Registrar Entrada -->
        <div 
          @click="controleRegistro = true"
          class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 cursor-pointer transform hover:scale-105 overflow-hidden group"
        >
          <!-- Header do Card -->
          <div class="bg-gradient-to-r from-blue-400 to-blue-600 p-8 text-white relative">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
            <div class="absolute bottom-0 left-0 w-20 h-20 bg-white/10 rounded-full translate-y-10 -translate-x-10"></div>
            
            <div class="relative z-10">
              <div class="inline-flex items-center justify-center w-16 h-16 bg-white/20 rounded-2xl mb-4">
                <Plus class="w-8 h-8" />
              </div>
              <h2 class="text-2xl font-bold mb-2">Registrar Pedido</h2>
              <p class="text-white/80">Realizar novo pedido de consumiveis</p>
            </div>
          </div>
          
          <!-- Conteúdo do Card -->
          <div class="p-8">
            <div class="flex items-center justify-between text-gray-600">
              <div>
                <p class="text-sm font-medium mb-1">Clique para registrar</p>
                <p class="text-xs text-gray-400">Adição de pedido</p>
              </div>
              <ArrowRight class="w-6 h-6 group-hover:translate-x-2 transition-transform text-blue-500" />
            </div>
          </div>
        </div>

        <!-- Card Histórico -->
        <div 
          @click="controleEntrada = true"
          class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 cursor-pointer transform hover:scale-105 overflow-hidden group"
        >
          <!-- Header do Card -->
          <div class="bg-gradient-to-r from-green-400 to-green-600 p-8 text-white relative">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
            <div class="absolute bottom-0 left-0 w-20 h-20 bg-white/10 rounded-full translate-y-10 -translate-x-10"></div>
            
            <div class="relative z-10">
              <div class="inline-flex items-center justify-center w-16 h-16 bg-white/20 rounded-2xl mb-4">
                <History class="w-8 h-8" />
              </div>
              <h2 class="text-2xl font-bold mb-2">Histórico de Pedidos</h2>
              <p class="text-white/80">Consultar pedidos realizados</p>
            </div>
          </div>
          
          <!-- Conteúdo do Card -->
          <div class="p-8">
            <div class="flex items-center justify-between text-gray-600">
              <div>
                <p class="text-sm font-medium mb-1">Ver histórico completo</p>
                <p class="text-xs text-gray-400">Filtrar e consultar</p>
              </div>
              <ArrowRight class="w-6 h-6 group-hover:translate-x-2 transition-transform text-green-500" />
            </div>
          </div>
        </div>

      </div>
    </div>
<RegistroEntrada v-if="controleRegistro" @close="FecharModalEntrada"/>
<ModalHistoricoEntrada v-if="controleEntrada" @close="FecharModalHistorico" />
  </div>
</template>

<script>
import { Plus, History, ArrowRight, ArrowLeft, Home, ChevronRight } from 'lucide-vue-next'
import RegistroEntrada from '../components/RegistroEntrada.vue';
import ModalHistoricoEntrada from '../components/ModalHistoricoEntrada.vue';
export default {
  name: 'Entrada',
  components: {
    Plus,
    History,
    ArrowRight,
    ArrowLeft,
    Home,
    ChevronRight,
    RegistroEntrada,
    ModalHistoricoEntrada
  },
  data() {
    return {
      controleRegistro: false,
      controleEntrada: false,
    };
  },
  methods: { 
    FecharModalEntrada(){
      this.controleRegistro = false;
    },
    FecharModalHistorico(){
      this.controleEntrada = false;
    }
  }
};
</script>