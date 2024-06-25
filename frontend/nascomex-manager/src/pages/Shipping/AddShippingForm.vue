<template>
  <form @submit.prevent="validateForm">
    <md-card>
      <md-card-header :data-background-color="dataBackgroundColor">
        <h4 class="title">Cadastro de <b>Numerário</b></h4>
        <p class="category">Preencha os campos</p>
      </md-card-header>

      <md-card-content>
        <div class="md-layout">
          <div class="md-layout-item md-small-size-100 md-size-30">
            <md-field>
              <label>Exportador</label>
              <md-input v-model="exporter" type="text"></md-input>
            </md-field>
          </div>
          <div class="md-layout-item md-small-size-100 md-size-30">
            <md-field>
              <label>Importador</label>
              <md-select v-model="imports_id" :disabled="!imports.length">
                <md-option v-for="importItem in imports" :key="importItem.id" :value="importItem.id">
                  {{ importItem.nome }}
                </md-option>
              </md-select>
            </md-field>
          </div>
          <div class="md-layout-item md-small-size-100 md-size-40">
            <md-field>
              <label>Att</label>
              <md-input v-model="att" type="text"></md-input>
            </md-field>
          </div>
          <div class="md-layout-item md-small-size-100 md-size-50">
            <md-field>
              <label>Navio</label>
              <md-input v-model="ship" type="text"></md-input>
            </md-field>
          </div>
          <div class="md-layout-item md-small-size-100 md-size-50">
            <md-field>
              <label>Porto</label>
              <md-select v-model="harbors_id" :disabled="!harbors.length">
                <md-option v-for="harbor in harbors" :key="harbor.id" :value="harbor.id">
                  {{ harbor.name }}
                </md-option>
              </md-select>
            </md-field>
          </div>
          <div class="md-layout-item md-small-size-100 md-size-50">
            <md-field>
              <label>Volumes</label>
              <md-input v-model="volumes" type="text"></md-input>
            </md-field>
          </div>
          <div class="md-layout-item md-small-size-100 md-size-50">
            <md-datepicker v-model="data" />
          </div>
          <div class="md-layout-item md-small-size-100 md-size-100">
            <md-field>
              <label>Invoices</label>
              <md-input v-model="invoices" type="text"></md-input>
            </md-field>
          </div>
          <!-- Campos adicionais -->
          <div class="md-layout-item md-small-size-100 md-size-20">
            <md-field>
              <label>THC</label>
              <md-input v-model="thc" type="number" step="0.01"></md-input>
            </md-field>
          </div>
          <div class="md-layout-item md-small-size-100 md-size-20">
            <md-field>
              <label>BL</label>
              <md-input v-model="BL" type="text"></md-input>
            </md-field>
          </div>
          <div class="md-layout-item md-small-size-100 md-size-20">
            <md-field>
              <label>Taxa de Expediente</label>
              <md-input v-model="office_fee" type="number" step="0.01"></md-input>
            </md-field>
          </div>
          <div class="md-layout-item md-small-size-100 md-size-20">
            <md-field>
              <label>Desembaraço</label>
              <md-input v-model="clearance" type="number" step="0.01"></md-input>
            </md-field>
          </div>
          <div class="md-layout-item md-small-size-100 md-size-20">
            <md-field>
              <label>Doc Bancaria</label>
              <md-input v-model="doc_bank" type="number" step="0.01"></md-input>
            </md-field>
          </div>
          <div class="md-layout-item md-small-size-100 md-size-20">
            <md-field>
              <label>SDA</label>
              <md-input v-model="sda" type="text"></md-input>
            </md-field>
          </div>
          <!-- Adicione mais campos conforme necessário -->
          
          <div class="md-layout-item md-small-size-100 md-size-20">
            <md-field>
              <label>Origem</label>
              <md-input v-model="origem" type="text"></md-input>
            </md-field>
          </div>
          <div class="md-layout-item md-small-size-100 md-size-20">
            <md-field>
              <label>Divergence</label>
              <md-input v-model="divergence" type="text"></md-input>
            </md-field>
          </div>
          <div class="md-layout-item md-small-size-100 md-size-20">
            <md-field>
              <label>Transport Docs</label>
              <md-input v-model="transport_docs" type="text"></md-input>
            </md-field>
          </div>
          <div class="md-layout-item md-small-size-100 md-size-20">
            <md-field>
              <label>Desconto Parcela</label>
              <md-input v-model="discount_installment" type="text"></md-input>
            </md-field>
          </div>
          <div class="md-layout-item md-small-size-100 md-size-60">
            <md-field>
              <label>Banco</label>
              <md-select v-model="banks_id" :disabled="!banks.length">
                <md-option v-for="bank in banks" :key="bank.id" :value="bank.id">
                  {{ bank.name }}
                </md-option>
              </md-select>
            </md-field>
          </div>
          <div class="md-layout-item md-small-size-100 md-size-20">
            <md-field>
              <label>Transferência Navio</label>
              <md-input v-model="ship_transfer" type="text"></md-input>
            </md-field>
          </div>
          <div class="md-layout-item md-small-size-100 md-size-20">
            <md-field>
              <label>Parcela Empréstimo</label>
              <md-input v-model="installment_loan" type="text"></md-input>
            </md-field>
          </div>
          <div class="md-layout-item md-small-size-100 md-size-50">
            <md-field>
              <label>Empresa</label>
              <md-input v-model="enterprise_name" type="text"></md-input>
            </md-field>
          </div>
          <div class="md-layout-item md-small-size-100 md-size-50">
            <md-field>
              <label>CNPJ</label>
              <md-input v-model="enterprise_cnpj" type="text"></md-input>
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
import axios from 'axios';

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
      exporter: null,
      att: null,
      imports_id: null,
      volumes: null,
      ship: null,
      harbors_id: null,
      data: null,
      invoices: null,
      thc: null,
      BL: null,
      office_fee: null,
      clearance: null,
      doc_bank: null,
      sda: null,
      origem: null,
      divergence: null,
      transport_docs: null,
      discount_installment: null,
      ship_transfer: null,
      installment_loan: null,
      banks_id: null,
      enterprise_name: null,
      enterprise_cnpj: null,
      imports: [],
      harbors: [],
      banks: [],
    };
  },
  methods: {
    async fetchData() {
      try {
        const importsResponse = await axios.get('http://localhost:8989/api/import');
        this.imports = importsResponse.data.imports;
        const harborsResponse = await axios.get('http://localhost:8989/api/harbor');
        this.harbors = harborsResponse.data.harbors;
        const banksResponse = await axios.get('http://localhost:8989/api/bank');
        this.banks = banksResponse.data.banks;
      } catch (error) {
        Swal.fire({
          icon: 'error',
          title: 'Erro',
          text: 'Erro ao buscar dados das APIs.',
        });
      }
    },
    validateForm() {
      if (!this.exporter) {
        Swal.fire({
          icon: 'error',
          title: 'Erro',
          text: 'Exportador é obrigatório.',
        });
        return;
      }

      if (!this.att) {
        Swal.fire({
          icon: 'error',
          title: 'Erro',
          text: 'Att é obrigatório.',
        });
        return;
      }

      if (!this.imports_id) {
        Swal.fire({
          icon: 'error',
          title: 'Erro',
          text: 'Importação é obrigatória.',
        });
        return;
      }

      if (!this.volumes) {
        Swal.fire({
          icon: 'error',
          title: 'Erro',
          text: 'Volumes é obrigatório.',
        });
        return;
      }

      if (!this.ship) {
        Swal.fire({
          icon: 'error',
          title: 'Erro',
          text: 'Navio é obrigatório.',
        });
        return;
      }

      if (!this.harbors_id) {
        Swal.fire({
          icon: 'error',
          title: 'Erro',
          text: 'Porto é obrigatório.',
        });
        return;
      }

      if (!this.data) {
        Swal.fire({
          icon: 'error',
          title: 'Erro',
          text: 'Data é obrigatória.',
        });
        return;
      }

      if (!this.banks_id) {
        Swal.fire({
          icon: 'error',
          title: 'Erro',
          text: 'Banco é obrigatório.',
        });
        return;
      }

      // Se todos os campos obrigatórios forem preenchidos, prossiga
      Swal.fire({
        icon: 'success',
        title: 'Sucesso',
        text: 'Formulário enviado com sucesso!',
      });
    }
  },
  mounted() {
    this.fetchData();
  }
};
</script>
