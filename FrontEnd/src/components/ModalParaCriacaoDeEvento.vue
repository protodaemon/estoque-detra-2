<template>
  <div v-if="mostrar" class="fixed inset-0 flex items-center justify-center z-50">
    <!-- Overlay só visual, sem fechar -->
    <div class="absolute inset-0 bg-black/20 backdrop-blur-md transition-opacity"></div>

    <!-- Conteúdo do Modal -->
    <div class="relative bg-white rounded-2xl p-8 w-[500px] max-w-[90vw] shadow-2xl z-10">
      <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-pink-700 mb-2">Novo Evento</h2>
        <p class="text-gray-600 text-sm">Preencha os dados do evento para criar a lista de locação</p>
      </div>

      <div class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Nome do Cliente *</label>
          <input v-model="dadosEvento.nome" type="text" placeholder="Digite o nome completo do cliente"
            class="w-full px-4 py-3 border rounded-xl border-gray-300 focus:outline-none focus:ring-2 focus:ring-pink-400 focus:border-transparent transition-all" />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Contato *</label>
          <input v-model="dadosEvento.contato" type="text" placeholder="Telefone ou e-mail do cliente"
            class="w-full px-4 py-3 border rounded-xl border-gray-300 focus:outline-none focus:ring-2 focus:ring-pink-400 focus:border-transparent transition-all" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Data do Evento *</label>
            <input v-model="dadosEvento.data" type="date"
              class="w-full px-4 py-3 border rounded-xl border-gray-300 focus:outline-none focus:ring-2 focus:ring-pink-400 focus:border-transparent transition-all" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Data de Retorno</label>
            <input v-model="dadosEvento.dataRetorno" type="date" :min="dadosEvento.data || ''" :class="[
              'w-full px-4 py-3 border rounded-xl transition-all',
              periodoInvalido
                ? 'border-red-300 focus:ring-2 focus:ring-red-400 focus:border-transparent'
                : 'border-gray-300 focus:outline-none focus:ring-2 focus:ring-pink-400 focus:border-transparent'
            ]" />
            <p class="text-xs text-gray-500 mt-1">Data prevista para devolução dos itens</p>
            <p v-if="periodoInvalido" class="text-xs text-red-500 mt-1 flex items-center">
              <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                  d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                  clip-rule="evenodd" />
              </svg>
              A data de retorno deve ser posterior à data do evento
            </p>
          </div>
        </div>

        <!-- Alerta geral de validação de período -->
        <div v-if="periodoInvalido" class="bg-red-50 border border-red-200 rounded-lg p-3">
          <div class="flex items-center">
            <svg class="w-5 h-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                clip-rule="evenodd" />
            </svg>
            <span class="text-sm text-red-700 font-medium">Período de datas inválido</span>
          </div>
          <p class="text-sm text-red-600 mt-1 ml-7">
            Verifique se a data de retorno é posterior à data do evento.
          </p>
        </div>

        <div class="flex gap-3 pt-4">
          <button @click="cancelar"
            class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-3 rounded-xl transition-all">
            Cancelar
          </button>

          <button @click="confirmar" :disabled="!dadosValidos" :class="[
            'flex-1 font-semibold py-3 rounded-xl transition-all',
            dadosValidos
              ? 'bg-pink-600 hover:bg-pink-700 text-white'
              : 'bg-gray-300 text-gray-500 cursor-not-allowed'
          ]">
            Criar Lista de Locação
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ModalParaCriacaoDeEvento',
  props: {
    mostrar: {
      type: Boolean,
      default: false
    },
    dados: {
      type: Object,
      default: () => ({
        nome: '',
        contato: '',
        data: '',
        dataRetorno: null
      })
    }
  },
  emits: ['confirmar', 'cancelar'],
  data() {
    return {
      dadosEvento: { ...this.dados }
    }
  },
  computed: {
    periodoInvalido() {
      if (!this.dadosEvento.data || !this.dadosEvento.dataRetorno) {
        return false
      }

      const dataEvento = new Date(this.dadosEvento.data)
      const dataRetorno = new Date(this.dadosEvento.dataRetorno)

      return dataRetorno < dataEvento
    },

    dadosValidos() {
      const camposObrigatoriosPreenchidos = this.dadosEvento.nome.trim() &&
        this.dadosEvento.contato.trim() &&
        this.dadosEvento.data

      // Se não há data de retorno, só valida campos obrigatórios
      if (!this.dadosEvento.dataRetorno) {
        return camposObrigatoriosPreenchidos
      }

      // Se há data de retorno, também valida o período
      return camposObrigatoriosPreenchidos && !this.periodoInvalido
    }
  },
  watch: {
    dados: {
      handler(newVal) {
        this.dadosEvento = { ...newVal }
      },
      deep: true
    },
    mostrar(newVal) {
      if (newVal) {
        this.dadosEvento = { ...this.dados }
      }
    }
  },
  methods: {
    confirmar() {
      if (!this.dadosValidos) {
        if (this.periodoInvalido) {
          alert('A data de retorno deve ser posterior à data do evento.')
        } else {
          alert('Preencha todos os campos obrigatórios.')
        }
        return
      }

      // Emite os dados no formato correto
      this.$emit('confirmar', {
        nome: this.dadosEvento.nome,
        contato: this.dadosEvento.contato,
        evento: this.dadosEvento.evento,
        data: this.dadosEvento.data,
        dataRetorno: this.dadosEvento.dataRetorno
      })
    },
    cancelar() {
      this.$emit('cancelar')
    }
  }
}
</script>

<style scoped>
.fixed {
  animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }

  to {
    opacity: 1;
  }
}

.bg-white {
  animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
  from {
    transform: translateY(-20px);
    opacity: 0;
  }

  to {
    transform: translateY(0);
    opacity: 1;
  }
}
</style>