<template>
    <!-- Loading Overlay -->
    <div 
      class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center"
    >
      <div class="bg-white rounded-full w-64 h-64 flex flex-col items-center justify-center shadow-xl relative overflow-hidden">
        
        
       
       

        <!-- Ícone central principal (ônibus) -->
        <div class="text-blue-500 mb-4 animate-bounce">
          <Bus class="w-20 h-20" />
        </div>
        
        <!-- Texto -->
        <p class="text-gray-600 text-sm font-medium flex items-center gap-1">
          <span>Carregando</span>
          <span class="flex">
            <span class="animate-bounce">.</span>
            <span class="animate-bounce" style="animation-delay: 0.1s">.</span>
            <span class="animate-bounce" style="animation-delay: 0.2s">.</span>
          </span>
        </p>

        <!-- Círculo girando no fundo -->
        <div class="absolute inset-4 border-2 border-blue-100 rounded-full animate-spin-slow opacity-30"></div>
      </div>
    </div>
</template>

<script>
import { Bus } from 'lucide-vue-next'

export default {
  name: 'LoadingDecorations',
  components: {
    Bus,
  },
  data() {
    return {
      isLoading: false
    };
  },
  methods: {
    showLoading() {
      this.isLoading = true;
      
      // Remove loading após 3 segundos
      setTimeout(() => {
        this.isLoading = false;
      }, 3000);
    }
  }
};
</script>

<style scoped>
@keyframes float {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-12px); }
}

@keyframes float-delay {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-8px); }
}

@keyframes bounce-slow {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-6px); }
}

@keyframes pulse-slow {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.5; }
}

@keyframes spin-slow {
  to { transform: rotate(360deg); }
}

.animate-float { 
  animation: float 2s ease-in-out infinite; 
}

.animate-float-delay { 
  animation: float-delay 2.5s ease-in-out infinite; 
}

.animate-bounce-slow { 
  animation: bounce-slow 1.8s ease-in-out infinite; 
}

.animate-pulse-slow { 
  animation: pulse-slow 2s ease-in-out infinite; 
}

.animate-spin-slow { 
  animation: spin-slow 4s linear infinite; 
}
</style>