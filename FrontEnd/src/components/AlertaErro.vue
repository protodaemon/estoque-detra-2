<template>
  <transition name="fade">
    <div
      v-if="mensagem"
      :class="[
        'flex items-center gap-3 p-4 rounded-xl border-l-4 shadow-md text-sm font-medium mt-2',
        tipo === 'erro' ? 'bg-red-50 border-red-500 text-red-700' :
        tipo === 'sucesso' ? 'bg-green-50 border-green-500 text-green-700' :
        'bg-blue-50 border-blue-500 text-blue-700'
      ]"
    >
      <svg v-if="tipo === 'erro'" class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M12 9v2m0 4h.01M5.07 19h13.86a2 2 0 001.73-2.99l-6.93-12a2 2 0 00-3.46 0l-6.93 12A2 2 0 005.07 19z" />
      </svg>
      <svg v-else-if="tipo === 'sucesso'" class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
      </svg>
      <svg v-else class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 19a7 7 0 110-14 7 7 0 010 14z" />
      </svg>
      <p>{{ mensagem }}</p>
    </div>
  </transition>
</template>

<script setup>
defineProps({
  mensagem: String,
  tipo: {
    type: String,
    default: 'erro' // erro | sucesso | info
  }
})
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
