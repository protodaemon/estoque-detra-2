<template>
  <!-- Header/Breadcrumb -->
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-pink-50 to-purple-50">
  <div class="w-full bg-white/80 backdrop-blur-sm border-b border-gray-200 sticky top-0 z-10">
    <div class="max-w-7xl mx-auto px-6 py-4">
      <div class="flex items-center justify-between">
        
        <div class="flex items-center space-x-4">
          <button @click="this.$router.push('/menu')" class="text-gray-500 hover:text-gray-700">
            <ArrowLeft class="w-5 h-5" />
          </button>
          <div>
            <h1 class="text-2xl font-bold text-gray-800">Gerenciar Consumíveis</h1>
            <div class="flex items-center text-sm text-gray-600 mt-1">
              <span class="text-gray-400">Menu</span>
              <ChevronRight class="w-4 h-4 mx-1" />
              <span class="text-blue-600">Menu Consumível</span>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- Main Content -->
  <div class="flex items-center justify-center min-h-[calc(90vh-80px)] p-6">
    <div class="w-full max-w-6xl">
      <!-- Page Title -->
      <div class="text-center mb-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-2">Escolha uma opção</h2>
        <p class="text-gray-600">Selecione o módulo que deseja acessar</p>
      </div>

      <!-- Cards Grid -->
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-6"> <!-- lg:grid-cols-3 sm:grid-cols-3-->
        <div 
          v-for="(opcao, index) in opcoes" 
          :key="opcao.titulo" 
          @click="navegar(opcao.rota)" 
          :class="[
            'card-hover fade-in-up cursor-pointer bg-white rounded-2xl border border-gray-200',
            'shadow-lg hover:shadow-xl p-6 flex flex-col items-center justify-center',
            'text-center space-y-4 group relative overflow-hidden transition-all duration-300',
            getCorClasses(opcao.cor).hover
          ]" 
          :style="{ 'animation-delay': `${index * 150}ms` }"
        >
          <!-- Badge -->
          <div 
            v-if="opcao.badge"
            :class="[
              'absolute top-3 right-3 px-2 py-1 rounded-full text-xs font-medium',
              getCorClasses(opcao.cor).badge
            ]"
          >
            {{ opcao.badge }}
          </div>
          
          <!-- Ícone -->
          <div :class="[
            'group-hover:scale-110 transition-transform duration-300',
            getCorClasses(opcao.cor).icon
          ]">
            <component :is="opcao.icone" class="w-12 h-12" />
          </div>
          
          <!-- Título -->
          <div class="text-xl font-bold text-gray-800 group-hover:text-gray-900 transition-colors">
            {{ opcao.titulo }}
          </div>
          
          <!-- Descrição -->
          <div class="text-sm text-gray-600 leading-relaxed">
            {{ opcao.descricao }}
          </div>
          
          <!-- Indicador de ação -->
          <div class="text-xs text-gray-400 group-hover:text-gray-500 transition-colors mt-2 
                      opacity-0 group-hover:opacity-100">
            Clique para acessar →
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Notificação -->
  <Transition name="notification">
    <div 
      v-if="showNotification"
      class="fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50"
    >
      {{ notificationMessage }}
    </div>
  </Transition>

  <!-- Modal de Senha -->
  <Transition name="modal">
    <div 
      v-if="showPasswordModal"
      class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4"
      @click.self="fecharModal"
    >
      <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 space-y-4 transform transition-all">
        <div class="text-center">
          <div class="mx-auto w-12 h-12 bg-rose-100 rounded-full flex items-center justify-center mb-4">
            <PackageOpen class="w-6 h-6 text-rose-500" />
          </div>
          <h3 class="text-xl font-bold text-gray-800 mb-2">Acesso Restrito</h3>
          <p class="text-sm text-gray-600">Digite a senha para acessar esta área</p>
        </div>

        <div>
          <input
            v-model="senhaDigitada"
            type="password"
            placeholder="Digite a senha"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-transparent outline-none transition-all"
            @keyup.enter="verificarSenha"
            ref="inputSenha"
          />
          <p v-if="senhaErrada" class="text-red-500 text-sm mt-2">Senha incorreta. Tente novamente.</p>
        </div>

        <div class="flex gap-3">
          <button
            @click="fecharModal"
            class="flex-1 px-4 py-3 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors"
          >
            Cancelar
          </button>
          <button
            @click="verificarSenha"
            class="flex-1 px-4 py-3 bg-rose-500 text-white rounded-lg font-medium hover:bg-rose-600 transition-colors"
          >
            Confirmar
          </button>
        </div>
      </div>
    </div>
  </Transition>
</div>
</template>

<script>
import { PackageOpen, Package, Palette, ClipboardList, UserPlus, Plus, Truck, Inbox, ArrowLeft, ChevronRight } from 'lucide-vue-next'

export default {
  name: 'MenuConsumivel',
  components: {
    PackageOpen,
    Package,
    Palette,
    ClipboardList,
    UserPlus,
    Plus,
    Truck,
    Inbox,
    ArrowLeft,
    ChevronRight
  },
  data() {
    return {
      showNotification: false,
      notificationMessage: '',
      showPasswordModal: false,
      senhaDigitada: '',
      senhaErrada: false,
      senhaCorreta: 'detra123456', // Defina a senha desejada aqui
      rotaPendente: null,
      opcoes: [
        {
          titulo: 'Estoque Consumível',
          rota: '/estoque-consumivel',
          icone: 'Palette',
          descricao: 'Consulte, adicione e gerencie itens de consumo',
          cor: 'blue',
          badge: null,
          requerSenha: true // Marcar rotas que precisam de senha
        },
        {
          titulo: 'Gerenciar Entradas',
          rota: '/entrada-consumivel',
          icone: 'Truck',
          descricao: "Adicione ou exclua produtos para consumo",
          cor: 'rose',
          badge: null,
          requerSenha: true // Marcar rotas que precisam de senha
        },
        {
          titulo: 'Realizar Pedido',
          rota: '/criar-pedido',
          icone: 'Inbox',
          descricao: "Novo pedido de produtos para consumo",
          cor: 'blue',
          badge: null,
          requerSenha: false // Esta não precisa de senha
        }
      ]
    }
  },
  mounted(){
  },
  methods: {

    navegar(rota) {
      // Verificar se a opção requer senha
      const opcao = this.opcoes.find(o => o.rota === rota);
      
      if (opcao && opcao.requerSenha) {
        this.rotaPendente = rota;
        this.showPasswordModal = true;
        this.$nextTick(() => {
          this.$refs.inputSenha?.focus();
        });
        return;
      }

      // Para rotas sem senha, navegar normalmente
      this.showNotification = true
      this.notificationMessage = `Navegando para ${rota}`
      
      setTimeout(() => {
        this.showNotification = false
      }, 2000)

      setTimeout(() => {
        this.$router.push(rota)
      }, 800);
    },

    verificarSenha() {
      if (this.senhaDigitada === this.senhaCorreta) {
        const rota = this.rotaPendente;
        this.fecharModal();
        this.showNotification = true;
        this.notificationMessage = 'Acesso autorizado!';
        
        setTimeout(() => {
          this.showNotification = false;
        }, 2000);

        setTimeout(() => {
          if (rota) {
            this.$router.push(rota);
          }
        }, 800);
      } else {
        this.senhaErrada = true;
        this.senhaDigitada = '';
        setTimeout(() => {
          this.senhaErrada = false;
        }, 3000);
      }
    },

    fecharModal() {
      this.showPasswordModal = false;
      this.senhaDigitada = '';
      this.senhaErrada = false;
      this.rotaPendente = null;
    },

    getCorClasses(cor) {
      const cores = {
        pink: {
          icon: 'text-pink-500',
          hover: 'hover:bg-pink-50 hover:border-pink-200',
          badge: 'bg-pink-100 text-pink-700'
        },
        purple: {
          icon: 'text-purple-500',
          hover: 'hover:bg-purple-50 hover:border-purple-200',
          badge: 'bg-purple-100 text-purple-700'
        },
        blue: {
          icon: 'text-blue-500',
          hover: 'hover:bg-blue-50 hover:border-blue-200',
          badge: 'bg-blue-100 text-blue-700'
        },
        green: {
          icon: 'text-green-500',
          hover: 'hover:bg-green-50 hover:border-green-200',
          badge: 'bg-green-100 text-green-700'
        },
        amber: {
          icon: 'text-amber-500',
          hover: 'hover:bg-amber-50 hover:border-amber-200',
          badge: 'bg-amber-100 text-amber-700'
        },
        rose: {
          icon: 'text-rose-500',
          hover: 'hover:bg-rose-50 hover:border-rose-200',
          badge: 'bg-rose-100 text-rose-700'
        }
      }
      return cores[cor] || cores.pink
    }
  }
}
</script>

<style scoped>
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.fade-in-up {
  animation: fadeInUp 0.6s ease-out forwards;
  opacity: 0;
}

.card-hover {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.card-hover:hover {
  transform: translateY(-4px);
}

/* Transição da notificação */
.notification-enter-active,
.notification-leave-active {
  transition: all 0.3s ease;
}

.notification-enter-from {
  opacity: 0;
  transform: translateX(100%);
}

.notification-leave-to {
  opacity: 0;
  transform: translateX(100%);
}

/* Transição do modal */
.modal-enter-active,
.modal-leave-active {
  transition: all 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}

.modal-enter-from .bg-white,
.modal-leave-to .bg-white {
  transform: scale(0.9);
}
</style>