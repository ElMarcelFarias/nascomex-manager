<template>
  <form @submit.prevent="validateForm">
    <md-card>
      <md-card-header :data-background-color="dataBackgroundColor">
        <h4 class="title">Cadastro de <b>Numerário</b></h4>
        <p class="category">Preencha os campos</p>
      </md-card-header>

      <md-card-content>
        <div class="md-layout">
          <div class="md-layout-item md-small-size-100 md-size-33">
            <md-field>
              <label>Nome do Porto</label>
              <md-input v-model="name" type="text"></md-input>
            </md-field>
          </div>
          <div class="md-layout-item md-small-size-100 md-size-50">
            <md-field>
              <label>Estado</label>
              <md-input v-model="state" type="text"></md-input>
            </md-field>
          </div>
          <div class="md-layout-item md-size-100 text-right">
            <md-button class="md-raised md-success" @click="validateForm">Enviar</md-button>
          </div>
        </div>
      </md-card-content>
    </md-card>
  </form>
</template>

<script>
import Swal from 'sweetalert2';

export default {
  name: "add-shipping-form",
  props: {
    dataBackgroundColor: {
      type: String,
      default: "",
    },
  },
  data() {
    return {
      username: null,
      disabled: null,
      emailadress: null,
      lastname: null,
      firstname: null,
      address: null,
      city: null,
      country: null,
      code: null,
      aboutme: "Lamborghini Mercy, Your chick she so thirsty, I'm in that two seat Lambo.",
    };
  },
  methods: {
    validateForm() {
      if (!this.name) {
        Swal.fire({
          icon: 'error',
          title: 'Erro',
          text: 'Campo Nome do Porto é obrigatório.',
        });
        return;
      }

      if (!this.state) {
        Swal.fire({
          icon: 'error',
          title: 'Erro',
          text: 'Campo Estado é obrigatório.',
        });
        return;
      }

      // Se todos os campos forem válidos, você pode prosseguir com a submissão do formulário
      Swal.fire({
        icon: 'success',
        title: 'Sucesso',
        text: 'Formulário enviado com sucesso!',
      });
    }
  }
};
</script>