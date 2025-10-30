<template>
  <div v-if="mostrar" class="fixed inset-0 bg-black/10 backdrop-blur-lg flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-300"
      :class="{ 'animate-bounce-in': mostrar }">
      
      <!-- Header com ícone -->
      <div class="p-6 text-center">
        <!-- Ícone de Sucesso -->
        <div v-if="tipo === 'sucesso'" class="w-20 h-20 mx-auto mb-4 bg-green-100 rounded-full flex items-center justify-center">
          <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
        </div>

        <!-- Ícone de Erro -->
        <div v-else class="w-20 h-20 mx-auto mb-4 bg-red-100 rounded-full flex items-center justify-center">
          <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
          </svg>
        </div>

        <!-- Título -->
        <h2 class="text-2xl font-bold mb-3" 
          :class="tipo === 'sucesso' ? 'text-green-700' : 'text-red-700'">
          {{ titulo }}
        </h2>

        <!-- Mensagem -->
        <p class="text-gray-600 mb-6 leading-relaxed">
          {{ mensagem }}
        </p>

        <!-- Detalhes adicionais para sucesso -->
        <div v-if="tipo === 'sucesso' && detalhes" class="bg-green-50 rounded-xl p-4 mb-6 border border-green-200">
          <div class="text-sm text-green-800">
            <div v-if="detalhes.cliente" class="mb-2">
              <span class="font-medium">Cliente:</span> {{ detalhes.cliente }}
            </div>
            <div v-if="detalhes.data" class="mb-2">
              <span class="font-medium">Data:</span> {{ detalhes.data }}
            </div>
            <div v-if="detalhes.totalItens" class="mb-2">
              <span class="font-medium">Total de itens:</span> {{ detalhes.totalItens }}
            </div>
            <div v-if="detalhes.valorTotal" class="text-lg font-bold text-green-700">
              <span class="font-medium">Valor total:</span> R$ {{ detalhes.valorTotal }}
            </div>
          </div>
        </div>

        <!-- Detalhes de erro -->
        <div v-if="tipo === 'erro' && detalhes && detalhes.erro" class="bg-red-50 rounded-xl p-4 mb-6 border border-red-200">
          <p class="text-sm text-red-800 font-mono break-words">
            {{ detalhes.erro }}
          </p>
        </div>
      </div>

      <!-- Footer com botões -->
      <div class="px-6 pb-6">
        <div class="flex gap-3">
          <button v-if="tipo === 'sucesso'" 
            @click="$emit('voltar-menu')"
            class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold px-6 py-3 rounded-xl transition-all duration-200 transform hover:scale-105">
          Voltar ao Menu
    </button>
          <!-- Botão secundário (apenas para erro) -->
          <button v-if="mostrarBotaoSecundario" @click="$emit('acao-secundaria')"
            class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold px-6 py-3 rounded-xl transition-all duration-200 transform hover:scale-105">
            {{ textoBotaoSecundario }}
          </button>

          <!-- Botão principal -->
          <button @click="$emit('confirmar')"
            class="font-semibold px-6 py-3 rounded-xl shadow-md transition-all duration-200 transform hover:scale-105"
            :class="[
              (mostrarBotaoSecundario || tipo === 'sucesso') ? 'flex-1' : 'w-full',
              tipo === 'sucesso' 
                ? 'bg-green-600 hover:bg-green-700 text-white' 
                : 'bg-red-600 hover:bg-red-700 text-white'
            ]">
            {{ textoBotaoPrincipal }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ModalResultadoLocacao',
  props: {
    mostrar: {
      type: Boolean,
      default: false
    },
    tipo: {
      type: String,
      default: 'sucesso', // 'sucesso' ou 'erro'
      validator: value => ['sucesso', 'erro'].includes(value)
    },
    titulo: {
      type: String,
      default: ''
    },
    mensagem: {
      type: String,
      default: ''
    },
    detalhes: {
      type: Object,
      default: null
    },
    textoBotaoPrincipal: {
      type: String,
      default: 'OK'
    },
    textoBotaoSecundario: {
      type: String,
      default: 'Cancelar'
    },
    mostrarBotaoSecundario: {
      type: Boolean,
      default: false
    }
  },
  computed: {
    tituloComputado() {
      if (this.titulo) return this.titulo
      return this.tipo === 'sucesso' ? 'Pedido Salvo!' : 'Erro ao Salvar'
    },
    mensagemComputada() {
      if (this.mensagem) return this.mensagem
      return this.tipo === 'sucesso' 
        ? 'O pedido foi salva com sucesso no sistema.'
        : 'Ocorreu um erro ao tentar salvar o pedido.'
    }
  },
  emits: ['confirmar', 'acao-secundaria'],
  mounted() {
    // Fecha o modal com ESC
    document.addEventListener('keydown', this.handleEscKey)
  },
  beforeUnmount() {
    document.removeEventListener('keydown', this.handleEscKey)
  },
  methods: {
    handleEscKey(event) {
      if (event.key === 'Escape' && this.mostrar) {
        this.$emit('confirmar')
      }
    }
  }
}
</script>

<style scoped>
/* Animação de entrada */
@keyframes bounce-in {
  0% {
    transform: scale(0.3) translateY(-50px);
    opacity: 0;
  }
  50% {
    transform: scale(1.05);
  }
  70% {
    transform: scale(0.9);
  }
  100% {
    transform: scale(1) translateY(0);
    opacity: 1;
  }
}

.animate-bounce-in {
  animation: bounce-in 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

/* Transições suaves */
* {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Estados focus melhorados */
button:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
}

/* Hover effects */
button:hover {
  transform: scale(1.05) translateY(-1px);
}

button:active {
  transform: scale(0.98) translateY(0);
}

/* Responsividade */
@media (max-width: 640px) {
  .max-w-md {
    max-width: calc(100vw - 2rem);
  }
}
</style>