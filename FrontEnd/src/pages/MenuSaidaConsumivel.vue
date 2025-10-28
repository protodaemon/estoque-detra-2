<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { Palette, History, ArrowLeft, Plus, X } from 'lucide-vue-next'
import TabConsumivel from '@/components/RegistroSaidaConsumivel.vue'
import TabHistorico from '@/components/ModalSaidaConsumivel.vue'

const router = useRouter()

// --- Tabs ---
const selectedTab = ref('consumivel')
const tabs = [
  { id: 'consumivel', label: 'Consumível', icon: Palette, component: TabConsumivel },
  { id: 'historico',  label: 'Histórico',  icon: History,  component: TabHistorico }
]

const selectedTabComponent = computed(() => {
  const t = tabs.find(t => t.id === selectedTab.value)
  return t?.component ?? null
})

// --- Navigation ---
function goBack() {
  router.push('/consumivel') // or wherever you came from
}
</script>

<template>
  <!-- FULL-PAGE LAYOUT -->
  <div class="min-h-screen bg-gradient-to-br from-blue-50 to-white">

    <!-- HEADER (sticky) -->
    <header class="bg-white shadow-sm sticky top-0 z-20">
      <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
        <div class="flex items-center gap-4">
          <button @click="goBack" class="text-gray-500 hover:text-gray-700">
            <ArrowLeft class="w-5 h-5" />
          </button>
          <div>
            <h1 class="text-2xl font-bold text-gray-800">Registrar Saida</h1>
            <nav class="flex items-center text-sm text-gray-600 mt-1 gap-2">
              <span class="text-gray-400">Menu</span>
              <ChevronRight class="w-4 h-4" />
              <span class="text-gray-400">Consumível</span>
              <ChevronRight class="w-4 h-4" />
              <span class="text-blue-600 font-medium">Saidas</span>
            </nav>
          </div>
        </div>
      </div>
    </header>

    <!-- TABS (sticky) -->
    <nav class="border-b border-gray-200 sticky top-0 bg-white z-10">
      <div class="flex max-w-7xl mx-auto">
        <button
          v-for="tab in tabs"
          :key="tab.id"
          @click="selectedTab = tab.id"
          :class="[
            'flex-1 py-4 px-6 text-sm font-medium border-b-2 flex items-center justify-center gap-2 transition-colors',
            selectedTab === tab.id
              ? 'text-green-600 border-green-500 bg-green-50'
              : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50'
          ]"
        >
          <component :is="tab.icon" class="w-4 h-4" />
          {{ tab.label }}
        </button>
      </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="max-w-7xl mx-auto">
      <component :is="selectedTabComponent" />
    </main>

    <!-- OPTIONAL: Sticky footer -->
<!--     <footer class="bg-white border-t border-gray-200 p-6 mt-auto">
      <div class="max-w-7xl mx-auto flex justify-end gap-4">
        <button @click="goBack" class="px-6 py-3 text-gray-600">Cancelar</button>
        <button class="px-8 py-3 bg-blue-600 text-white rounded-xl flex items-center gap-2">
          <Check class="w-4 h-4" />
          Salvar
        </button>
      </div>
    </footer> -->
  </div>
</template>

<style scoped>
/* Optional: smooth scroll */
html { scroll-behavior: smooth; }
</style>