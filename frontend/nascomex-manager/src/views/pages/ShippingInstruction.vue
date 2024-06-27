<script setup>
import { ref, onMounted, onBeforeMount } from 'vue';
import { ShippingInstructionsService } from '@/service/ShippingInstructionsService';
import { useToast } from 'primevue/usetoast';

const toast = useToast();

const shippingInstructions = ref(null);
const shippingInstructionDialog = ref(false);
const deleteShippingInstructionDialog = ref(false);
const deleteShippingInstructionsDialog = ref(false);
const shippingInstruction = ref({});
const selectedShippingInstructions = ref(null);
const dt = ref(null);
const filters = ref({});
const submitted = ref(false);

const imports = ref([]);
const harbors = ref([]);
const banks = ref([]);

const shippingInstructionsService = new ShippingInstructionsService();

onBeforeMount(() => {
    initFilters();
});
onMounted(() => {
    shippingInstructionsService.getShippingInstructions().then((data) => (shippingInstructions.value = data));
    shippingInstructionsService.getImports().then((data) => (imports.value = data));
    shippingInstructionsService.getHarbors().then((data) => (harbors.value = data));
    shippingInstructionsService.getBanks().then((data) => (banks.value = data));
});

const openNew = () => {
    shippingInstruction.value = {};
    submitted.value = false;
    shippingInstructionDialog.value = true;
};

const hideDialog = () => {
    shippingInstructionDialog.value = false;
    submitted.value = false;
};

const saveShippingInstruction = () => {
    submitted.value = true;

    if (shippingInstruction.value.exporter && shippingInstruction.value.att && shippingInstruction.value.imports_id &&
        shippingInstruction.value.volumes && shippingInstruction.value.ship && shippingInstruction.value.harbors_id &&
        shippingInstruction.value.data && shippingInstruction.value.banks_id) {

        if (shippingInstruction.value.id) {
            shippingInstructionsService.putShippingInstruction(shippingInstruction.value).then(() => {
                shippingInstructions.value[findIndexById(shippingInstruction.value.id)] = shippingInstruction.value;
                toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Numerário Alterado', life: 3000 });
                shippingInstructionDialog.value = false;
            }).catch((error) => {
                toast.add({ severity: 'error', summary: 'Erro', detail: 'Falha ao alterar Numerário', life: 3000 });
                console.error('Error ao alterar Numerário:', error);
            });
        } else {
            shippingInstructionsService.postShippingInstruction(shippingInstruction.value).then((newInstruction) => {
                Object.assign(shippingInstruction.value, newInstruction.data);
                shippingInstructions.value.push({ ...shippingInstruction.value });

                toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Numerário Criada', life: 3000 });
                shippingInstructionDialog.value = false;
            }).catch((error) => {
                toast.add({ severity: 'error', summary: 'Erro', detail: 'Falha ao criar Numerário', life: 3000 });
                console.error('Error ao criar Numerário:', error);
            });
        }
    }
};


const editShippingInstruction = (editShippingInstruction) => {
    shippingInstruction.value = { ...editShippingInstruction };
    shippingInstructionDialog.value = true;
};

const confirmDeleteShippingInstruction = (editShippingInstruction) => { 
    shippingInstruction.value = editShippingInstruction;
    deleteShippingInstructionDialog.value = true;
};

const deleteShippingInstruction = () => {
    shippingInstructionsService.deleteShippingInstruction(shippingInstruction.value.id).then(() => {
        shippingInstructions.value = shippingInstructions.value.filter((val) => val.id !== shippingInstruction.value.id);
        deleteShippingInstructionDialog.value = false;
        shippingInstruction.value = {};
        toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Numerário Deletada', life: 3000 });
    }).catch((error) => {
        toast.add({ severity: 'error', summary: 'Erro', detail: 'Falha ao deletar Numerário', life: 3000 });
        console.error('Error ao deletar Numerário:', error);
    });
};

const findIndexById = (id) => {
    let index = -1;
    for (let i = 0; i < shippingInstructions.value.length; i++) {
        if (shippingInstructions.value[i].id === id) {
            index = i;
            break;
        }
    }
    return index;
};

const confirmDeleteSelected = () => {
    deleteShippingInstructionsDialog.value = true;
};

const deleteSelectedShippingInstructions = () => {
    const promises = selectedShippingInstructions.value.map(instruction => shippingInstructionsService.deleteShippingInstruction(instruction.id));

    Promise.all(promises)
        .then(() => {
            shippingInstructions.value = shippingInstructions.value.filter((val) => !selectedShippingInstructions.value.includes(val));
            deleteShippingInstructionsDialog.value = false;
            selectedShippingInstructions.value = null;
            toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Numerário Deletadas', life: 3000 });
        })
        .catch((error) => {
            toast.add({ severity: 'error', summary: 'Erro', detail: 'Falha ao deletar todas as Numerário', life: 3000 });
            console.error('Erro ao deletar todas as Numerário:', error);
        });
};

const initFilters = () => {
    filters.value = {
        global: { value: null }
    };
};

const exportCSV = () => {
    dt.value.exportCSV();
};

const formatDate = (value) => {
    const date = new Date(value);
    return date.toLocaleDateString('pt-BR', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
    });
};
</script>

<template>
    <div class="grid">
        <div class="col-12">
            <div class="card">
                <Toolbar class="mb-4">
                    <template v-slot:start>
                        <div class="my-2">
                            <Button label="Nova Numerário" icon="pi pi-plus" class="mr-2" severity="success" @click="openNew" />
                            <Button label="Deletar" icon="pi pi-trash" severity="danger" @click="confirmDeleteSelected" :disabled="!selectedShippingInstructions || !selectedShippingInstructions.length" />
                        </div>
                    </template>

                    <template v-slot:end>
                        <Button label="Exportar" icon="pi pi-upload" severity="help" @click="exportCSV($event)" />
                    </template>
                </Toolbar>

                <DataTable
                    ref="dt"
                    :value="shippingInstructions"
                    v-model:selection="selectedShippingInstructions"
                    dataKey="id"
                    :paginator="true"
                    :rows="10"
                    :filters="filters"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 25]"
                    currentPageReportTemplate=" {first} a {last} de {totalRecords} Numerário"
                >
                    <template #header>
                        <div class="flex flex-column md:flex-row md:justify-content-between md:align-items-center">
                            <h5 class="m-0">Numerário</h5>
                            <IconField iconPosition="left" class="block mt-2 md:mt-0">
                                <InputIcon class="pi pi-search" />
                                <InputText class="w-full sm:w-auto" v-model="filters['global'].value" placeholder="Pesquisar..." />
                            </IconField>
                        </div>
                    </template>

                    <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
                    <Column field="id" header="ID" :sortable="true" headerStyle="width:10%; min-width:4rem;">
                        <template #body="slotProps">
                            <span class="p-column-title">ID</span>
                            {{ slotProps.data.id }}
                        </template>
                    </Column>
                    <Column field="exporter" header="Exportador" :sortable="true" headerStyle="width:35%; min-width:10rem;">
                        <template #body="slotProps">
                            <span class="p-column-title">Exportador</span>
                            {{ slotProps.data.exporter }}
                        </template>
                    </Column>
                    <Column field="att" header="ATT" :sortable="true" headerStyle="width:20%; min-width:8rem;">
                        <template #body="slotProps">
                            <span class="p-column-title">ATT</span>
                            {{ slotProps.data.att }}
                        </template>
                    </Column>
                    <Column field="imports_id" header="Importador" :sortable="true" headerStyle="width:20%; min-width:8rem;">
                        <template #body="slotProps">
                            <span class="p-column-title">Importador</span>
                            {{ slotProps.data.imports_id }}
                        </template>
                    </Column>
                    <Column field="volumes" header="Volumes" :sortable="true" headerStyle="width:20%; min-width:8rem;">
                        <template #body="slotProps">
                            <span class="p-column-title">Volumes</span>
                            {{ slotProps.data.volumes }}
                        </template>
                    </Column>
                    <Column field="ship" header="Navio" :sortable="true" headerStyle="width:20%; min-width:8rem;">
                        <template #body="slotProps">
                            <span class="p-column-title">Navio</span>
                            {{ slotProps.data.ship }}
                        </template>
                    </Column>
                    <Column field="harbors_id" header="Porto" :sortable="true" headerStyle="width:20%; min-width:8rem;">
                        <template #body="slotProps">
                            <span class="p-column-title">Porto</span>
                            {{ slotProps.data.harbors_id }}
                        </template>
                    </Column>
                    <Column field="data" header="Data" :sortable="true" headerStyle="width:20%; min-width:8rem;">
                        <template #body="slotProps">
                            <span class="p-column-title">Data</span>
                            {{ formatDate(slotProps.data.data) }}
                        </template>
                    </Column>
                    <Column field="banks_id" header="Banco" :sortable="true" headerStyle="width:20%; min-width:8rem;">
                        <template #body="slotProps">
                            <span class="p-column-title">Banco</span>
                            {{ slotProps.data.banks_id }}
                        </template>
                    </Column>
                    <Column field="enterprise_name" header="Nome da Empresa" :sortable="true" headerStyle="width:20%; min-width:8rem;">
                        <template #body="slotProps">
                            <span class="p-column-title">Nome da Empresa</span>
                            {{ slotProps.data.enterprise_name }}
                        </template>
                    </Column>
                    <Column headerStyle="min-width:10rem;">
                        <template #body="slotProps">
                            <Button icon="pi pi-pencil" class="mr-2" severity="success" rounded @click="editShippingInstruction(slotProps.data)" />   
                            <Button icon="pi pi-trash" class="mt-2" severity="danger" rounded @click="confirmDeleteShippingInstruction(slotProps.data)" />
                        </template>
                    </Column>
                </DataTable>

                <Dialog v-model:visible="shippingInstructionDialog" :style="{ width: '850px' }" header="Detalhes da Numerário" :modal="true" class="p-fluid">
                    <div class="grid">
                        <div class="field md:col-4">
                            <label for="exporter">Exportador</label>
                            <InputText id="exporter" v-model.trim="shippingInstruction.exporter" required="true" autofocus :invalid="submitted && !shippingInstruction.exporter" />
                            <small class="p-invalid" v-if="submitted && !shippingInstruction.exporter">Exportador é um campo obrigatório.</small>
                        </div>
                        <div class="field md:col-4">
                            <label for="imports_id">Importador</label>
                            <Dropdown id="imports_id" v-model="shippingInstruction.imports_id" :options="imports" optionLabel="nome" optionValue="id" required :invalid="submitted && !shippingInstruction.imports_id" />
                            <small class="p-invalid" v-if="submitted && !shippingInstruction.imports_id">Importador é um campo obrigatório.</small>
                        </div>
                        <div class="field md:col-4">
                            <label for="data">Data</label>
                            <Calendar id="data" v-model="shippingInstruction.data" dateFormat="dd/mm/yy" required :invalid="submitted && !shippingInstruction.data" />
                            <small class="p-invalid" v-if="submitted && !shippingInstruction.data">Data é um campo obrigatório.</small>
                        </div>
                        <div class="field md:col-3">
                            <label for="harbors_id">Porto</label>
                            <Dropdown id="harbors_id" v-model="shippingInstruction.harbors_id" :options="harbors" optionLabel="name" optionValue="id" required :invalid="submitted && !shippingInstruction.harbors_id" />
                            <small class="p-invalid" v-if="submitted && !shippingInstruction.harbors_id">Porto é um campo obrigatório.</small>
                        </div>
                        <div class="field md:col-3">
                            <label for="ship">Navio</label>
                            <InputText id="ship" v-model.trim="shippingInstruction.ship" required="true" :invalid="submitted && !shippingInstruction.ship" />
                            <small class="p-invalid" v-if="submitted && !shippingInstruction.ship">Navio é um campo obrigatório.</small>
                        </div>
                        <div class="field md:col-3">
                            <label for="att">ATT</label>
                            <InputText id="att" v-model.trim="shippingInstruction.att" required="true" :invalid="submitted && !shippingInstruction.att" />
                            <small class="p-invalid" v-if="submitted && !shippingInstruction.att">ATT é um campo obrigatório.</small>
                        </div>
                        <div class="field md:col-3">
                            <label for="volumes">Volumes</label>
                            <InputText id="volumes" v-model.trim="shippingInstruction.volumes" required="true" :invalid="submitted && !shippingInstruction.volumes" />
                            <small class="p-invalid" v-if="submitted && !shippingInstruction.volumes">Volumes é um campo obrigatório.</small>
                        </div>
                        <div class="field md:col-12">
                            <label for="invoices">Invoices</label>
                            <Textarea id="invoices" v-model.trim="shippingInstruction.invoices" rows="4" />
                        </div>
                        <div class="field md:col-4">
                            <label for="banks_id">Banco</label>
                            <Dropdown id="banks_id" v-model="shippingInstruction.banks_id" :options="banks" optionLabel="bank" optionValue="id" required :invalid="submitted && !shippingInstruction.banks_id" />
                            <small class="p-invalid" v-if="submitted && !shippingInstruction.banks_id">Banco é um campo obrigatório.</small>
                        </div>
                        <div class="field md:col-4">
                            <label for="enterprise_name">Nome da Empresa</label>
                            <InputText id="enterprise_name" v-model.trim="shippingInstruction.enterprise_name" />
                        </div>
                        <div class="field md:col-4">
                            <label for="enterprise_cnpj">CNPJ da Empresa</label>
                            <InputText id="enterprise_cnpj" v-model.trim="shippingInstruction.enterprise_cnpj" />
                        </div>
                        <div class="field md:col-3">
                            <label for="thc">THC</label>
                            <InputNumber id="thc" v-model.number="shippingInstruction.thc" mode="currency" currency="BRL" locale="pt-BR" />
                        </div>
                        <div class="field md:col-3">
                            <label for="BL">Bill of Lading (BL)</label>
                            <InputText id="BL" v-model.trim="shippingInstruction.BL" />
                        </div>
                        <div class="field md:col-3">
                            <label for="office_fee">Taxa de Expediente</label>
                            <InputNumber id="office_fee" v-model.number="shippingInstruction.office_fee" mode="currency" currency="BRL" locale="pt-BR" />
                        </div>
                        <div class="field md:col-3">
                            <label for="clearance">Desembaraço</label>
                            <InputNumber id="clearance" v-model.number="shippingInstruction.clearance" mode="currency" currency="BRL" locale="pt-BR" />
                        </div>
                        <div class="field md:col-3">
                            <label for="doc_bank">Doc. Banco</label>
                            <InputNumber id="doc_bank" v-model.number="shippingInstruction.doc_bank" mode="currency" currency="BRL" locale="pt-BR" />
                        </div>
                        <div class="field md:col-3">
                            <label for="sda">SDA</label>
                            <InputText id="sda" v-model.trim="shippingInstruction.sda" />
                        </div>
                        <div class="field md:col-3">
                            <label for="origem">Origem</label>
                            <InputText id="origem" v-model.trim="shippingInstruction.origem" />
                        </div>
                        <div class="field md:col-3">
                            <label for="divergence">Divergência</label>
                            <InputText id="divergence" v-model.trim="shippingInstruction.divergence" />
                        </div>
                        <div class="field md:col-3">
                            <label for="transport_docs">Docs. Transporte</label>
                            <InputText id="transport_docs" v-model.trim="shippingInstruction.transport_docs" />
                        </div>
                        <div class="field md:col-3">
                            <label for="discount_installment">Desconto na Parcela</label>
                            <InputText id="discount_installment" v-model.trim="shippingInstruction.discount_installment" />
                        </div>
                        <div class="field md:col-3">
                            <label for="ship_transfer">Transferência do Navio</label>
                            <InputText id="ship_transfer" v-model.trim="shippingInstruction.ship_transfer" />
                        </div>
                        <div class="field md:col-3">
                            <label for="installment_loan">Empréstimo da Parcela</label>
                            <InputText id="installment_loan" v-model.trim="shippingInstruction.installment_loan" />
                        </div>
                        
                    </div>
                    <template #footer>
                        <Button label="Cancelar" icon="pi pi-times" text="" @click="hideDialog" />
                        <Button label="Salvar" icon="pi pi-check" text="" @click="saveShippingInstruction" />
                    </template>
                </Dialog>

                <Dialog v-model:visible="deleteShippingInstructionDialog" :style="{ width: '450px' }" header="Confirmar" :modal="true">
                    <div class="flex align-items-center justify-content-center">
                        <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
                        <span v-if="shippingInstruction">Realmente deseja apagar <b>{{ shippingInstruction.exporter }}</b>?</span>
                    </div>
                    <template #footer>
                        <Button label="Não" icon="pi pi-times" text @click="deleteShippingInstructionDialog = false" />
                        <Button label="Sim" icon="pi pi-check" text @click="deleteShippingInstruction" />
                    </template>
                </Dialog>

                <Dialog v-model:visible="deleteShippingInstructionsDialog" :style="{ width: '450px' }" header="Confirmar" :modal="true">
                    <div class="flex align-items-center justify-content-center">
                        <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
                        <span v-if="shippingInstruction">Realmente deseja apagar?</span>
                    </div>
                    <template #footer>
                        <Button label="Não" icon="pi pi-times" text @click="deleteShippingInstructionsDialog = false" />
                        <Button label="Sim" icon="pi pi-check" text @click="deleteSelectedShippingInstructions" />
                    </template>
                </Dialog>
            </div>
        </div>
    </div>
</template>
