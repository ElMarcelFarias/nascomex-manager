<script setup>
import { ref, onMounted, onBeforeMount } from 'vue';
import { BankService } from '@/service/BankService';
import { useToast } from 'primevue/usetoast';

const toast = useToast();

const banks = ref(null);
const bankDialog = ref(false);
const deleteBankDialog = ref(false);
const deleteBanksDialog = ref(false);
const bank = ref({});
const selectedBanks = ref(null);
const dt = ref(null);
const filters = ref({});
const submitted = ref(false);

const bankService = new BankService();

onBeforeMount(() => {
    initFilters();
});
onMounted(() => {
    bankService.getBanks().then((data) => (banks.value = data));
});

const openNew = () => {
    bank.value = {};
    submitted.value = false;
    bankDialog.value = true;
};

const hideDialog = () => {
    bankDialog.value = false;
    submitted.value = false;
};

const saveBank = () => {
    submitted.value = true;
    if (bank.value.name && bank.value.name.trim() && bank.value.bank && bank.value.agency && bank.value.number) {
        if (bank.value.id) {
            bankService.putBank(bank.value).then(() => {
                banks.value[findIndexById(bank.value.id)] = bank.value;
                toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Banco Alterado', life: 3000 });
                bankDialog.value = false;
            }).catch((error) => {
                toast.add({ severity: 'error', summary: 'Erro', detail: 'Falha ao alterar Banco', life: 3000 });
                console.error('Error ao alterar banco:', error);
            });
        } else {
            bankService.postBank(bank.value).then((newBank) => {
                bank.value.id = newBank.data.id;
                bank.value.bank = newBank.data.bank;
                bank.value.agency = newBank.data.agency;
                bank.value.number = newBank.data.number;
                bank.value.name = newBank.data.name;
                bank.value.created_at = newBank.data.created_at;
                bank.value.updated_at = newBank.data.updated_at;
                banks.value.push(bank.value);
                toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Banco Criado', life: 3000 });
            }).catch((error) => {
                toast.add({ severity: 'error', summary: 'Erro', detail: 'Falha ao criar Banco', life: 3000 });
                console.error('Error ao criar Banco:', error);
            });
            
        }
        bankDialog.value = false;
    }
};

const editBank = (editBank) => {
    bank.value = { ...editBank };
    bankDialog.value = true;
};

const confirmDeleteBank = (editBank) => { 
    bank.value = editBank;
    deleteBankDialog.value = true;
};

const deleteBank = () => {
    bankService.deleteBank(bank.value.id).then(() => {
        banks.value = banks.value.filter((val) => val.id !== bank.value.id);
        deleteBankDialog.value = false;
        bank.value = {};
        toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Banco Deletado', life: 3000 });
    }).catch((error) => {
        toast.add({ severity: 'error', summary: 'Erro', detail: 'Falha ao deletar Banco', life: 3000 });
        console.error('Error ao deletar banco:', error);
    });
};

const findIndexById = (id) => {
    let index = -1;
    for (let i = 0; i < banks.value.length; i++) {
        if (banks.value[i].id === id) {
            index = i;
            break;
        }
    }
    return index;
};

const confirmDeleteSelected = () => {
    deleteBanksDialog.value = true;
};

const deleteSelectedBanks = () => {
    const promises = selectedBanks.value.map(bank => bankService.deleteBank(bank.id));

    Promise.all(promises)
        .then(() => {
            banks.value = banks.value.filter((val) => !selectedBanks.value.includes(val));
            deleteBanksDialog.value = false;
            selectedBanks.value = null;
            toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Bancos Deletados', life: 3000 });
        })
        .catch((error) => {
            toast.add({ severity: 'error', summary: 'Erro', detail: 'Falha ao deletar todos os Bancos', life: 3000 });
            console.error('Erro ao deletar todos os Bancos:', error);
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
                            <Button label="Novo Banco" icon="pi pi-plus" class="mr-2" severity="success" @click="openNew" />
                            <Button label="Deletar" icon="pi pi-trash" severity="danger" @click="confirmDeleteSelected" :disabled="!selectedBanks || !selectedBanks.length" />
                        </div>
                    </template>

                    <template v-slot:end>
                        <Button label="Exportar" icon="pi pi-upload" severity="help" @click="exportCSV($event)" />
                    </template>
                </Toolbar>

                <DataTable
                    ref="dt"
                    :value="banks"
                    v-model:selection="selectedBanks"
                    dataKey="id"
                    :paginator="true"
                    :rows="10"
                    :filters="filters"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 25]"
                    currentPageReportTemplate=" {first} a {last} de {totalRecords} Bancos"
                >
                    <template #header>
                        <div class="flex flex-column md:flex-row md:justify-content-between md:align-items-center">
                            <h5 class="m-0">Bancos</h5>
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
                    <Column field="bank" header="Banco" :sortable="true" headerStyle="width:35%; min-width:10rem;">
                        <template #body="slotProps">
                            <span class="p-column-title">Banco</span>
                            {{ slotProps.data.bank }}
                        </template>
                    </Column>
                    <Column field="agency" header="Agência" :sortable="true" headerStyle="width:20%; min-width:8rem;">
                        <template #body="slotProps">
                            <span class="p-column-title">Agência</span>
                            {{ slotProps.data.agency }}
                        </template>
                    </Column>
                    <Column field="number" header="Número" :sortable="true" headerStyle="width:20%; min-width:8rem;">
                        <template #body="slotProps">
                            <span class="p-column-title">Número</span>
                            {{ slotProps.data.number }}
                        </template>
                    </Column>
                    <Column field="name" header="Nome" :sortable="true" headerStyle="width:20%; min-width:8rem;">
                        <template #body="slotProps">
                            <span class="p-column-title">Nome</span>
                            {{ slotProps.data.name }}
                        </template>
                    </Column>
                    <Column field="created_at" header="Criado em" :sortable="true" headerStyle="width:20%; min-width:8rem;">
                        <template #body="slotProps">
                            <span class="p-column-title">Criado em</span>
                            {{ formatDate(slotProps.data.created_at) }}
                        </template>
                    </Column>
                    <Column field="updated_at" header="Atualizado em" :sortable="true" headerStyle="width:20%; min-width:8rem;">
                        <template #body="slotProps">
                            <span class="p-column-title">Atualizado em</span>
                            {{ formatDate(slotProps.data.updated_at) }}
                        </template>
                    </Column>
                    <Column headerStyle="min-width:10rem;">
                        <template #body="slotProps">
                            <Button icon="pi pi-pencil" class="mr-2" severity="success" rounded @click="editBank(slotProps.data)" />   
                            <Button icon="pi pi-trash" class="mt-2" severity="danger" rounded @click="confirmDeleteBank(slotProps.data)" />
                        </template>
                    </Column>
                </DataTable>

                <Dialog v-model:visible="bankDialog" :style="{ width: '450px' }" header="Cadastro de Banco" :modal="true" class="p-fluid">
                    <div class="field">
                        <label for="bank">Banco</label>
                        <InputText id="bank" v-model.trim="bank.bank" required="true" autofocus :invalid="submitted && !bank.bank" />
                        <small class="p-invalid" v-if="submitted && !bank.bank">Banco é um campo obrigatório.</small>
                    </div>
                    <div class="field">
                        <label for="agency">Agência</label>
                        <InputText id="agency" v-model.trim="bank.agency" required="true" :invalid="submitted && !bank.agency" />
                        <small class="p-invalid" v-if="submitted && !bank.agency">Agência é um campo obrigatório.</small>
                    </div>
                    <div class="field">
                        <label for="number">Número</label>
                        <InputText id="number" v-model.trim="bank.number" required="true" :invalid="submitted && !bank.number" />
                        <small class="p-invalid" v-if="submitted && !bank.number">Número é um campo obrigatório.</small>
                    </div>
                    <div class="field">
                        <label for="name">Nome</label>
                        <InputText id="name" v-model.trim="bank.name" required="true" :invalid="submitted && !bank.name" />
                        <small class="p-invalid" v-if="submitted && !bank.name">Nome é um campo obrigatório.</small>
                    </div>
                    <template #footer>
                        <Button label="Cancel" icon="pi pi-times" text="" @click="hideDialog" />
                        <Button label="Save" icon="pi pi-check" text="" @click="saveBank" />
                    </template>
                </Dialog>

                <Dialog v-model:visible="deleteBankDialog" :style="{ width: '450px' }" header="Confirmar" :modal="true">
                    <div class="flex align-items-center justify-content-center">
                        <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
                        <span v-if="bank">Realmente deseja apagar <b>{{ bank.name }}</b>?</span>
                    </div>
                    <template #footer>
                        <Button label="Não" icon="pi pi-times" text @click="deleteBankDialog = false" />
                        <Button label="Sim" icon="pi pi-check" text @click="deleteBank" />
                    </template>
                </Dialog>

                <Dialog v-model:visible="deleteBanksDialog" :style="{ width: '450px' }" header="Confirmar" :modal="true">
                    <div class="flex align-items-center justify-content-center">
                        <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
                        <span v-if="bank">Realmente deseja apagar?</span>
                    </div>
                    <template #footer>
                        <Button label="No" icon="pi pi-times" text @click="deleteBanksDialog = false" />
                        <Button label="Sim" icon="pi pi-check" text @click="deleteSelectedBanks" />
                    </template>
                </Dialog>
            </div>
        </div>
    </div>
</template>
