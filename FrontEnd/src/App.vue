<template>
  <router-view />
</template>
<script>
import { reactive } from "vue";
import axios from "axios";
export default {

  data() {
    return {
      nivelAcesso: reactive({ value: null }),
      usuario: reactive({ value: null }),
      
    };
  },
  provide() {
    return {
      nivelAcesso: this.nivelAcesso,
      usuario: this.usuario,
    };
  },
  created() {
    this.checaSessao();
    // LÁ EM BAIXO NO COMPOENTNE
  },
  methods: {
    async checaSessao() {
      try {
        const response = await axios.get(
          "https://amttdetra.com/questionario_validador/backend/public/api/check-session",
          { withCredentials: true }
        );

        console.log("Resposta da API:", response.data);

        if (!response.data.logado) {
          window.location.href = "https://amttdetra.com/index.php";
        } else {
          // Atualiza os valores reativos com os dados corretos
          this.nivelAcesso.value = response.data.session.nivel_acesso; // Captura o nível de acesso
          this.usuario.value = response.data.session.nomeServidor; // Captura o nome do servidor
        }
      } catch (error) {
        console.error("Erro ao verificar a sessão:", error);
        // Redireciona em caso de erro, assumindo que o erro indica token ausente ou inválido
        window.location.href = "https://amttdetra.com/index.php";
      }
    },
  },
}
</script>
