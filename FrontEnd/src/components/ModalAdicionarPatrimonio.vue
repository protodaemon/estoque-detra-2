<template>
  <div class="fixed inset-0 backdrop-blur-md bg-pink-200/20 flex items-center justify-center z-50 p-4"
    @click.self="$emit('fechar')">
    <div class="bg-white rounded-2xl w-full max-w-lg shadow-2xl relative transform transition-all
                max-h-[90vh] flex flex-col">
      
      <!-- Header fixo -->
      <div class="bg-gradient-to-r from-blue-300 to-blue-600 p-6 text-white relative shrink-0 rounded-2xl">
        <button @click="$emit('fechar')"
          class="absolute top-4 right-4 text-white/80 hover:text-white hover:bg-white/20 rounded-full p-2 transition-all duration-200 z-20">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
        
        <div class="relative z-10">
          <div class="flex items-center gap-3 mb-2">
            <div class="p-2 bg-white/20 rounded-lg">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M12 11V1"></path>
              </svg>
            </div>
            <h2 class="text-2xl font-bold">Registro de Patrimônio</h2>
          </div>
          <p class="text-white/80 text-sm">Adicione um novo patrimônio público</p>
        </div>
      </div>

      <!-- Conteúdo rolável -->
      <div class="p-6 space-y-5 overflow-y-auto flex-1">
        <!-- Categoria -->
        <div class="space-y-2">
          <label class="flex items-center gap-2 text-sm font-semibold text-gray-700">
            <svg class="w-4 h-4 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
            </svg>
            Categoria
          </label>
          <select v-model="form.categoria_patrimonio" 
            class="w-full p-3 border-2 border-gray-200 rounded-xl focus:border-blue-400 focus:ring-4 focus:ring-pink-100 transition-all duration-200 bg-gray-50 hover:bg-white">
            <option disabled value="">Selecione a categoria</option>
            <option v-for="cat in categorias" :key="cat.categoria_patrimonio_id" :value="cat.categoria_patrimonio_id">
              {{ cat.nome }}
            </option>
          </select>
        </div>

      

        <!-- Número de Identificação -->
        <div class="space-y-2">
        <label class="flex items-center gap-2 text-sm font-semibold text-gray-700">
            <svg class="w-4 h-4 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 .5304-.2107 1.0391-.5858 1.4142C11.0391 12.7893 10.5304 13 10 13s-1.0391-.2107-1.4142-.5858C8.2107 12.0391 8 11.5304 8 11s.2107-1.0391.5858-1.4142C8.9609 9.2107 9.4696 9 10 9s1.0391.2107 1.4142.5858C11.7893 9.9609 12 10.4696 12 11z"></path>
            </svg>
            Número de Identificação
        </label>
        <input 
            v-model="form.numero_identificacao"
            type="text"
            maxlength="5"
            placeholder="Ex: 12345"
            class="w-full p-3 border-2 border-gray-200 rounded-xl focus:border-pink-400 focus:ring-4 focus:ring-pink-100 transition-all duration-200 bg-gray-50 hover:bg-white"
        >
        <p class="text-xs text-gray-500">Digite um código de 5 dígitos.</p>
        </div>

        <!-- Descrição -->
        <div class="space-y-2">
          <label class="flex items-center gap-2 text-sm font-semibold text-gray-700">
            <svg class="w-4 h-4 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h8m-8 4h5m-7 4h14M5 8h14M4 4h16"></path>
            </svg>
            Descrição
          </label>
          <textarea 
            v-model="form.descricao"
            placeholder="Ex: Vaso artesanal pintado à mão..."
            rows="3"
            class="w-full p-3 border-2 border-gray-200 rounded-xl focus:border-pink-400 focus:ring-4 focus:ring-pink-100 transition-all duration-200 bg-gray-50 hover:bg-white resize-none">
          </textarea>
        </div>

        <!-- Localização -->
        <div class="space-y-2">
          <label class="flex items-center gap-2 text-sm font-semibold text-gray-700">
            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M17.657 16.657L13.414 12.414A4 4 0 1112 20a4 4 0 01-4-4 4 4 0 014-4 4 4 0 014 4v.01">
              </path>
            </svg>
            Localização
          </label>
          <select v-model="form.localizacao_id" 
            class="w-full p-3 border-2 border-gray-200 rounded-xl focus:border-green-400 focus:ring-4 focus:ring-green-100 transition-all duration-200 bg-gray-50 hover:bg-white">
            <option disabled value="">Selecione a localização</option>
            <option v-for="loc in localizacoes" :key="loc.localizacao_id" :value="loc.localizacao_id">
              {{ loc.nome }}
            </option>
          </select>
        </div>
        

        <!-- Observações -->
        <div class="space-y-2">
          <label class="flex items-center gap-2 text-sm font-semibold text-gray-700">
            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h8m-8 4h5m-7 4h14M5 8h14M4 4h16"></path>
            </svg>
            Observações
          </label>
          <textarea 
            v-model="form.observacoes"
            placeholder="Ex: Esse vaso tem uma rachadura no..."
            rows="3"
            class="w-full p-3 border-2 border-gray-200 rounded-xl focus:border-pink-400 focus:ring-4 focus:ring-pink-100 transition-all duration-200 bg-gray-50 hover:bg-white resize-none">
          </textarea>
        </div>

        <!-- Upload de Foto -->
        <div class="space-y-3">
          <label class="flex items-center gap-2 text-sm font-semibold text-gray-700">
            <svg class="w-4 h-4 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            Foto do Produto
          </label>
          
          <div class="relative">
            <div v-if="preview" class="group relative">
              <img :src="preview" 
                class="w-full h-48 object-cover rounded-xl border-2 border-gray-200" />
              <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-200 rounded-xl flex items-center justify-center">
                <button @click="removerFoto" 
                  class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition-colors duration-200">
                  Remover Foto
                </button>
              </div>
            </div>
            
            <div v-else 
              @click="anexarFoto"
              class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center cursor-pointer hover:border-pink-400 hover:bg-pink-50 transition-all duration-200 group">
              <svg class="mx-auto h-12 w-12 text-gray-400 group-hover:text-pink-400 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
              </svg>
              <p class="mt-2 text-sm text-gray-500 group-hover:text-pink-600 transition-colors duration-200">
                <span class="font-semibold">Clique para enviar</span> ou arraste uma imagem
              </p>
              <p class="text-xs text-gray-400">PNG, JPG, JPEG até 5MB</p>
            </div>
          </div>

          <div v-if="preview" class="flex justify-center gap-3">
            <button @click="anexarFoto" 
              class="bg-pink-100 text-pink-600 px-4 py-2 rounded-lg hover:bg-pink-200 transition-all duration-200 flex items-center gap-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
              </svg>
              Trocar Foto
            </button>
          </div>

          <input type="file" ref="fotoInput" class="hidden" @change="handleFotoUpload" accept="image/*" />
        </div>

        <AlertaMensagem v-if="erroMsg" :mensagem="erroMsg" tipo="erro" />
        <AlertaMensagem v-if="sucessoMsg" :mensagem="sucessoMsg" tipo="sucesso" />


        <!-- Botão de salvar -->
        <button :disabled="salvando" @click="salvarProduto"
          class="w-full bg-gradient-to-r from-blue-300 to-blue-600 text-white font-semibold py-4 rounded-xl hover:from-blue-600 hover:to-blue-900 disabled:opacity-60 disabled:cursor-not-allowed transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2 shadow-lg">
          <svg v-if="salvando" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
          </svg>
          {{ salvando ? 'Salvando...' : 'Salvar Produto' }}
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import AlertaMensagem from '@/components/AlertaErro.vue';

export default {
  name: 'ModalAdicionarProduto',
  props: ['categorias', 'localizacoes'],
  components: {
    AlertaMensagem
  },
  data() {
    return {
      erroMsg: null,
      sucessoMsg: null,
      form: {
        categoria_patrimonio: '',
        descricao: '',
        localizacao_id: '',
        numero_identificacao: '',
        observacoes: '',
        foto: null
      },
      preview: null,
      salvando: false
    };
  },
  methods: {
    anexarFoto() {
      this.$refs.fotoInput.click();
    },
    handleFotoUpload(e) {
      const file = e.target.files[0];
      if (file) {
        this.form.foto = file;
        this.preview = URL.createObjectURL(file);
      }
    },
    removerFoto() {
      this.form.foto = null;
      this.preview = null;
      this.$refs.fotoInput.value = null;
    },
    async salvarProduto() {
      if (!this.form.descricao || !this.form.categoria_patrimonio || !this.form.localizacao_id) {
        alert("Preencha todos os campos obrigatórios!");
        return;
      }

      this.salvando = true;
      const formData = new FormData();
      formData.append('descricao', this.form.descricao); // enviar descrição
      formData.append('observacoes', this.form.observacoes);
      formData.append('categoria_patrimonio', this.form.categoria_patrimonio);
      formData.append('numero_identificacao', this.form.numero_identificacao);
      formData.append('localizacao_id', this.form.localizacao_id);
      if (this.form.foto) {
        formData.append('foto', this.form.foto);
      }

     try {
        const response = await axios.post('produtos-patrimonio', formData);
        this.erroMsg = null;
        this.sucessoMsg = 'Produto salvo com sucesso!';
        setTimeout(() => {
          this.$emit('salvou');
          this.$emit('fechar');
        }, 100);
      } catch (error) {
        console.error("Erro ao salvar produto:", error);
        if (error.response && error.response.status === 422) {
          const erros = error.response.data.errors;
          this.erroMsg = Object.values(erros).flat().join('\n');
        } else {
          this.erroMsg = "Erro inesperado ao salvar o produto.";
        }
      } finally {
        this.salvando = false;
      }

    }
  }
};
</script>
