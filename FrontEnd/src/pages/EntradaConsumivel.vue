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
                <span class="text-blue-600 font-medium">Entradas</span>
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
        
        <!-- Card Entradas -->
        <div 
          @click="navegar('/menu-entradas-consumivel')"
          class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 cursor-pointer transform hover:scale-105 overflow-hidden group"
        >
          <!-- Header do Card -->
          <div class="bg-gradient-to-r from-green-500 to-green-700 p-8 text-white relative">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
            <div class="absolute bottom-0 left-0 w-20 h-20 bg-white/10 rounded-full translate-y-10 -translate-x-10"></div>
            
            <div class="relative z-10">
              <div class="inline-flex items-center justify-center w-16 h-16 bg-white/20 rounded-2xl mb-4">
                <Plus class="w-8 h-8" />
              </div>
              <h2 class="text-2xl font-bold mb-2">Registrar Entrada</h2>
              <p class="text-white/80">Novas entradas de produtos consumíveis e consulta de histórico</p>
            </div>
          </div>
          
          <!-- Conteúdo do Card -->
          <div class="p-8">
            <div class="flex items-center justify-between text-gray-600">
              <div>
                <p class="text-sm font-medium mb-1">Clique para entrar</p>
                <p class="text-xs text-gray-400">Adição de consumível</p>
              </div>
              <ArrowRight class="w-6 h-6 group-hover:translate-x-2 transition-transform text-blue-500" />
            </div>
          </div>
        </div>

        <!-- Card Saídas -->
        <div 
          @click="navegar('/menu-saida-consumivel')"
          class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 cursor-pointer transform hover:scale-105 overflow-hidden group"
        >
          <!-- Header do Card -->
          <div class="bg-gradient-to-r from-red-500 to-red-800 p-8 text-white relative">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
            <div class="absolute bottom-0 left-0 w-20 h-20 bg-white/10 rounded-full translate-y-10 -translate-x-10"></div>
            
            <div class="relative z-10">
              <div class="inline-flex items-center justify-center w-16 h-16 bg-white/20 rounded-2xl mb-4">
                <Minus class="w-8 h-8" />
              </div>
              <h2 class="text-2xl font-bold mb-2">Registrar Saídas</h2>
              <p class="text-white/80">Realizar saída de consumíveis e consultar histórico</p>
            </div>
          </div>
          
          <!-- Conteúdo do Card -->
          <div class="p-8">
            <div class="flex items-center justify-between text-gray-600">
              <div>
                <p class="text-sm font-medium mb-1">Acessar menu</p>
                <p class="text-xs text-gray-400">Saída de consumíveis</p>
              </div>
              <ArrowRight class="w-6 h-6 group-hover:translate-x-2 transition-transform text-green-500" />
            </div>
          </div>
        </div>

      </div>
    </div>
<RegistroEntradaConsumivel v-if="controleRegistro" @close="FecharModalEntrada"/>
<ModalHistoricoConsumivel v-if="controleEntrada" @close="FecharModalHistorico" />
  </div>
</template>

<script>
import { Plus, History, ArrowRight, ArrowLeft, Home, ChevronRight, Minus } from 'lucide-vue-next'
import RegistroEntradaConsumivel from '../components/RegistroEntradaConsumivel.vue';
import ModalHistoricoConsumivel from '../components/ModalHistoricoConsumivel.vue';
export default {
  name: 'Entrada',
  components: {
    Plus,
    History,
    ArrowRight,
    ArrowLeft,
    Home,
    ChevronRight,
    Minus,
    RegistroEntradaConsumivel,
    ModalHistoricoConsumivel
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
    },
    navegar(rota) 
    {
      // Feedback visual
      this.showNotification = true
      this.notificationMessage = `Navegando para ${rota}`
      
      setTimeout(() => {
        this.showNotification = false
      }, 2000)

      // Navegação real
      setTimeout(() => {
        this.$router.push(rota)
      }, 800);
    }
  }
};
</script>