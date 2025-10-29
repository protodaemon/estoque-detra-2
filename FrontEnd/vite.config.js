import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import tailwindcss from '@tailwindcss/vite'

import path from 'path'

export default defineConfig({
  plugins: [vue(), tailwindcss()],
  resolve: {
    alias: {
      '@': path.resolve(__dirname, './src'), // adiciona o alias @ para src
    },
  },
  base: '/estoque_patrimonio/patrimonio/dist/',  // Isso gera caminhos relativos em vez de absolutos
})
