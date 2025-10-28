<template>
    <!-- Loading Overlay -->
    <div 
      class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center"
    >
      <div class="bg-white rounded-full w-64 h-64 flex flex-col items-center justify-center shadow-xl relative overflow-hidden">
        
        <!-- Flores voando ao redor -->
        <div class="absolute top-8 left-8 text-pink-400 animate-float">
          <Flower class="w-6 h-6" />
        </div>
        <div class="absolute bottom-5 left-24 text-pink-400 animate-bounce">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-rose-icon lucide-rose"><path d="M17 10h-1a4 4 0 1 1 4-4v.534"/><path d="M17 6h1a4 4 0 0 1 1.42 7.74l-2.29.87a6 6 0 0 1-5.339-10.68l2.069-1.31"/><path d="M4.5 17c2.8-.5 4.4 0 5.5.8s1.8 2.2 2.3 3.7c-2 .4-3.5.4-4.8-.3-1.2-.6-2.3-1.9-3-4.2"/><path d="M9.77 12C4 15 2 22 2 22"/><circle cx="17" cy="8" r="2"/></svg>
        </div>
        <div class="absolute top-12 right-12 text-rose-400 animate-float-delay">
          <Flower2 class="w-5 h-5" />
        </div>
        <div class="absolute bottom-16 left-10 text-purple-400 animate-bounce-slow">
          <Cherry class="w-5 h-5" />
        </div>
        <div class="absolute bottom-10 right-10 text-pink-300 animate-pulse-slow">
          <Flower class="w-4 h-4" />
        </div>

        <!-- Ícone central principal -->
        <div class="text-pink-500 mb-4 animate-bounce">
          <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="80" height="80" viewBox="0 0 256 256" xml:space="preserve">
<g style="stroke: none; stroke-width: 0; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: none; fill-rule: nonzero; opacity: 1;" transform="translate(1.4065934065934016 1.4065934065934016) scale(2.81 2.81)">
	<path d="M 80.261 64.09 c 5.335 0 9.661 -4.326 9.661 -9.661 c 0 -4.624 -3.887 -8.447 -8.227 -9.403 c 4.624 -0.724 8.163 -4.728 8.163 -9.557 c 0 -5.335 -4.326 -9.661 -9.661 -9.661 c -0.994 0 -1.964 0.142 -2.867 0.426 c 0.01 -0.01 0.026 -0.026 0.04 -0.04 c 2.027 -1.769 3.189 -4.081 3.189 -6.987 c 0 -5.335 -4.326 -9.661 -9.661 -9.661 c -2.893 0 -5.528 1.498 -7.297 3.513 c 0.4 -1.046 0.607 -2.17 0.607 -3.358 c 0 -5.335 -4.326 -9.661 -9.661 -9.661 c -4.49 0 -8.257 3.051 -9.346 7.195 h -0.167 C 44.067 2.946 40.258 0 35.663 0 c -5.335 0 -9.661 4.327 -9.661 9.661 c 0 1.175 0.22 2.299 0.594 3.332 c -1.77 -2.208 -4.482 -3.617 -7.53 -3.604 c -5.335 0 -9.661 4.326 -9.661 9.661 c 0 2.906 1.279 5.502 3.293 7.271 c -0.93 -0.297 -1.925 -0.465 -2.958 -0.465 c -5.336 0.001 -9.662 4.329 -9.662 9.662 c 0 4.753 3.436 8.706 7.969 9.507 c -4.52 0.814 -7.956 4.766 -7.956 9.52 c 0 5.335 4.327 9.661 9.661 9.661 c 1.046 0 2.04 -0.155 2.984 -0.465 c 0.026 -0.013 0.039 -0.013 0.065 -0.026 c -1.964 1.77 -3.203 4.339 -3.203 7.195 c 0 5.335 4.327 9.661 9.661 9.661 c 2.906 0 5.399 -1.279 7.169 -3.293 c -0.322 0.955 -0.491 1.976 -0.504 3.061 c 0 5.335 4.327 9.661 9.661 9.661 c 4.891 0 8.818 -3.655 9.46 -8.369 h 0.02 c 0.653 4.703 4.574 8.344 9.456 8.344 c 5.335 0 9.661 -4.326 9.661 -9.661 c 0 -1.434 -0.155 -2.441 -0.711 -3.656 c 1.77 2.299 4.365 3.81 7.491 3.81 c 5.335 0 9.661 -4.326 9.661 -9.661 c 0 -2.891 -1.285 -5.5 -3.306 -7.269 v -0.089 C 78.267 63.787 79.183 64.09 80.261 64.09 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(255,118,174); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round"/>
	<circle cx="44.998999999999995" cy="44.998999999999995" r="10.209" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(255,255,120); fill-rule: nonzero; opacity: 1;" transform="  matrix(1 0 0 1 0 0) "/>
</g>
</svg>
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
        <div class="absolute inset-4 border-2 border-pink-100 rounded-full animate-spin-slow opacity-30"></div>
      </div>
    </div>
</template>

<script>
import { Heart, Flower, Flower2, Cherry } from 'lucide-vue-next'

export default {
  name: 'LoadingDecorations',
  components: {
    Heart,
    Flower,
    Flower2,
    Cherry,
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