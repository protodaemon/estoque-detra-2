import { defineConfig, loadEnv } from 'vite'
import vue from '@vitejs/plugin-vue'
import tailwindcss from '@tailwindcss/vite'
import path from 'path'

// Note que agora exportamos uma FUNÇÃO que recebe 'mode'
export default defineConfig(({ mode }) => {
  // Carrega as variáveis de ambiente baseadas no modo (development ou production)
  const env = loadEnv(mode, process.cwd(), '')

  return {
    plugins: [vue(), tailwindcss()],
    resolve: {
      alias: {
        '@': path.resolve(__dirname, './src'),
      },
    },
    // Agora a variável 'env' existe e podemos usar o VITE_BASE_PATH
    base: env.VITE_BASE_PATH,
  }
})