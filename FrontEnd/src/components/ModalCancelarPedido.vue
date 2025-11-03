<template>
  <transition name="fade">
    <div v-if="mostrar" class="fixed inset-0 z-50 flex items-center justify-center px-4">
      <div class="fixed inset-0 bg-black/50" @click="fechar"></div>
      
      <div class="relative bg-white rounded-xl shadow-2xl max-w-md w-full p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Cancelar Pedido</h2>
        
        <p class="text-gray-600 mb-4">
          Deseja realmente cancelar o pedido #{{ pedidoId }}?
        </p>

        <div class="mb-6">
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Motivo do Cancelamento (opcional)
          </label>
          <textarea
            v-model="observacao"
            rows="3"
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
            placeholder="Descreva o motivo do cancelamento..."
          ></textarea>
        </div>

        <div class="flex gap-3 justify-end">
          <button
            @click="fechar"
            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors"
          >
            Voltar
          </button>
          <button
            @click="confirmarCancelamento"
            :disabled="cancelando"
            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors disabled:opacity-50"
          >
            {{ cancelando ? 'Cancelando...' : 'Confirmar Cancelamento' }}
          </button>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
export default {
  name: 'ModalCancelarPedido',
  props: {
    mostrar: { type: Boolean, default: false },
    pedidoId: { type: Number, required: true }
  },
  data() {
    return {
      observacao: '',
      cancelando: false
    }
  },
  methods: {
    fechar() {
      this.observacao = ''
      this.$emit('fechar')
    },
    async confirmarCancelamento() {
      this.cancelando = true
      this.$emit('confirmar', {
        pedidoId: this.pedidoId,
        observacao: this.observacao.trim() || null
      })
      this.cancelando = false
      this.observacao = ''
    }
  }
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.2s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>