<template>
  <div class="fixed inset-0 backdrop-blur-md bg-pink-200/20 flex items-center justify-center z-50 p-4"
    @click.self="$emit('fechar')">
    <div class="bg-white rounded-2xl w-full max-w-lg shadow-2xl relative overflow-hidden transform transition-all">
      <!-- Header com gradiente -->
      <div class="bg-gradient-to-r from-purple-400 to-purple-600 p-6 text-white relative">
        
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
              </svg>
            </div>
            <h2 class="text-2xl font-bold">Editar Produto</h2>
          </div>
          <p class="text-white/80 text-sm">Atualize as informações do produto</p>
        </div>
      </div>

      <!-- Conteúdo do formulário -->
      <div class="p-6 space-y-5 overflow-y-auto max-h-[70vh]">
        <!-- Categoria -->
        <div class="space-y-2">
          <label class="flex items-center gap-2 text-sm font-semibold text-gray-700">
            <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
            </svg>
            Categoria
          </label>
          <select v-model="form.categoria_consumivel" 
            class="w-full p-3 border-2 border-gray-200 rounded-xl focus:border-purple-400 focus:ring-4 focus:ring-purple-100 transition-all duration-200 bg-gray-50 hover:bg-white">
            <option disabled value="">Selecione a categoria</option>
            <option v-for="cat in categorias" :key="cat.categoria_consumivel_id" :value="cat.categoria_consumivel_id">
              {{ cat.nome }}
            </option>
          </select>
        </div>

        <!-- Nome do Produto -->
        <div class="space-y-2">
          <label class="flex items-center gap-2 text-sm font-semibold text-gray-700">
            <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
            </svg>
            Nome do Produto
          </label>
          <input v-model="form.nome" 
            placeholder="Ex: Vaso de Cerâmica" 
            class="w-full p-3 border-2 border-gray-200 rounded-xl focus:border-purple-400 focus:ring-4 focus:ring-purple-100 transition-all duration-200 bg-gray-50 hover:bg-white" />
        </div>

        <!-- Descrição -->
        <div class="space-y-2">
          <label class="flex items-center gap-2 text-sm font-semibold text-gray-700">
            <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
            </svg>
            Descrição
          </label>
          <textarea v-model="form.descricao" 
            placeholder="Descreva as características e detalhes do produto..." 
            rows="3"
            class="w-full p-3 border-2 border-gray-200 rounded-xl focus:border-purple-400 focus:ring-4 focus:ring-purple-100 transition-all duration-200 bg-gray-50 hover:bg-white resize-none"></textarea>
        </div>

        <!-- Número de Identificação -->
<!--         <div class="space-y-2">
          <label class="flex items-center gap-2 text-sm font-semibold text-gray-700">
            <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Número de Identificação *
            <span class="text-xs text-gray-500">(5 dígitos)</span>
          </label>
          <input type="text" v-model="form.numero_identificacao" @input="formatarNumeroId"
            placeholder="00000" 
            maxlength="5"
            :class="[
              'w-full p-3 border-2 rounded-xl focus:ring-4 transition-all duration-200 bg-gray-50 hover:bg-white',
              form.numero_identificacao && form.numero_identificacao.length !== 5 
                ? 'border-red-300 focus:border-red-400 focus:ring-red-100' 
                : 'border-gray-200 focus:border-purple-400 focus:ring-purple-100'
            ]" />
          <p v-if="form.numero_identificacao && form.numero_identificacao.length !== 5" 
            class="text-xs text-red-500 flex items-center gap-1">
            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
            O número deve ter exatamente 5 dígitos
          </p>
        </div> -->


        <!-- Observações -->
        <div class="space-y-2">
          <label class="flex items-center gap-2 text-sm font-semibold text-gray-700">
            <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
            </svg>
            Observações
          </label>
          <textarea v-model="form.observacoes" 
            placeholder="Observações sobre o estado do patrimônio..." 
            rows="3"
            class="w-full p-3 border-2 border-gray-200 rounded-xl focus:border-purple-400 focus:ring-4 focus:ring-purple-100 transition-all duration-200 bg-gray-50 hover:bg-white resize-none"></textarea>
        </div>

  

        <!-- Upload de Foto -->
        <div class="space-y-3">
          <label class="flex items-center gap-2 text-sm font-semibold text-gray-700">
            <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            Foto do Produto
          </label>
          
          <!-- Área de preview da imagem -->
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
            
            <!-- Área de upload quando não há imagem -->
            <div v-else 
              @click="anexarFoto"
              class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center cursor-pointer hover:border-purple-400 hover:bg-purple-50 transition-all duration-200 group">
              <svg class="mx-auto h-12 w-12 text-gray-400 group-hover:text-purple-400 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
              </svg>
              <p class="mt-2 text-sm text-gray-500 group-hover:text-purple-600 transition-colors duration-200">
                <span class="font-semibold">Clique para enviar</span> ou arraste uma imagem
              </p>
              <p class="text-xs text-gray-400">PNG, JPG, JPEG até 5MB</p>
            </div>
          </div>

          <!-- Botões de ação para foto (quando há preview) -->
          <div v-if="preview" class="flex justify-center gap-3">
            <button @click="anexarFoto" 
              class="bg-purple-100 text-purple-600 px-4 py-2 rounded-lg hover:bg-purple-200 transition-all duration-200 flex items-center gap-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
              </svg>
              Trocar Foto
            </button>
          </div>

          <input type="file" ref="fotoInput" class="hidden" @change="handleFotoUpload" accept="image/*" />
        </div>

        <!-- Botões de ação -->
        <div class="flex gap-3">
          <button @click="$emit('fechar')"
            class="flex-1 bg-gray-100 text-gray-600 font-semibold py-4 rounded-xl hover:bg-gray-200 transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            Cancelar
          </button>
          
          <button :disabled="salvando" @click="atualizarProduto"
            class="flex-1 bg-gradient-to-r from-purple-400 to-purple-600 text-white font-semibold py-4 rounded-xl hover:from-purple-600 hover:to-indigo-700 disabled:opacity-60 disabled:cursor-not-allowed transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2 shadow-lg">
            <svg v-if="salvando" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            {{ salvando ? 'Atualizando...' : 'Atualizar Produto' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'ModalEditarProduto',
  props: ['categorias', 'produto'],
  data() {
    return {
      form: {
        categoria_consumivel: '',
        nome: '',
        descricao: '',
        observacoes: '',
        // numero_identificacao: '',
        foto: null
      },
      preview: null,
      salvando: false,
      fotoAlterada: false
    };
  },
  mounted() {
    this.preencherFormulario();
    console.log(this.produto)
  },
  watch: {
    produto: {
      handler() {
        this.preencherFormulario();
      },
      deep: true
    }
  },
  methods: {
    preencherFormulario() {
      if (this.produto) {
        this.form.categoria_consumivel = this.produto.categoria_consumivel || '';
        this.form.nome = this.produto.nome || '';
        this.form.descricao = this.produto.descricao || '';
        this.form.observacoes = this.produto.observacoes || '';
        // this.form.numero_identificacao = this.produto.numero_identificacao || '';
        
        // Carregar foto existente se houver
        if (this.produto.foto) {
          this.preview = `${this.produto.foto}`;
          this.fotoAlterada = false;
        } else {
          this.preview = null;
        }
      }
    },
    anexarFoto() {
      this.$refs.fotoInput.click();
    },
    formatarPreco(e){
      let valor = e.target.value.replace(/\D/g, '');
      if(!valor){
        this.form.valor_locacao = '';
        return;
      }
      valor = (parseInt(valor, 10) /100).toFixed(2);
      let numero = parseFloat(valor);
      if(numero > 999999.99){
        numero = 999999.99;
      }
      this.form.valor_locacao = numero.toFixed(2).replace('.',',');
    },
    handleFotoUpload(e) {
      const file = e.target.files[0];
      if (file) {
        this.form.foto = file;
        this.preview = URL.createObjectURL(file);
        this.fotoAlterada = true;
      }
    },
    removerFoto() {
      this.form.foto = null;
      this.preview = null;
      this.fotoAlterada = true;
      this.$refs.fotoInput.value = null;
    },
    async atualizarProduto() {
      // Validação dos campos obrigatórios
      if (!this.form.nome || !this.form.categoria_consumivel) {
        alert("Preencha todos os campos obrigatórios!");
        return;
      }

      this.salvando = true;
      const formData = new FormData();
      formData.append('_method', 'PUT'); // Para Laravel aceitar PUT via FormData
      formData.append('nome', this.form.nome);
      formData.append('descricao', this.form.descricao);
      formData.append('observacoes', this.form.observacoes);
      // formData.append('numero_identificacao', this.form.numero_identificacao);
      formData.append('categoria_consumivel', this.form.categoria_consumivel);
      
      // Só enviar foto se foi alterada
      if (this.fotoAlterada) {
        if (this.form.foto) {
          formData.append('foto', this.form.foto);
        } else {
          formData.append('remover_foto', 'true');
        }
      }

      try {
        const response = await axios.post(`editar-consumivel/${this.produto.produtos_consumivel_id}`, formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        });
        
        this.$emit('atualizado');
        this.$emit('fechar');
      } catch (error) {
        console.error("Erro ao atualizar produto:", error);
        alert("Erro ao atualizar produto.");
      } finally {
        this.salvando = false;
      }
    }
  }
};
</script>