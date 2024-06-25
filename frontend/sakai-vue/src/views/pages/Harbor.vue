<script setup>
import { FilterMatchMode } from 'primevue/api';
import { ref, onMounted, onBeforeMount } from 'vue';
import { HarborService } from '@/service/HarborService';
import { useToast } from 'primevue/usetoast';

const toast = useToast();

const harbors = ref(null);
const harborDialog = ref(false);
const deleteHarborDialog = ref(false);
const deleteHarborsDialog = ref(false);
const harbor = ref({});
const selectedHarbors = ref(null);
const dt = ref(null);
const filters = ref({});
const submitted = ref(false);

const harborService = new HarborService();

onBeforeMount(() => {
    initFilters();
});
onMounted(() => {
    harborService.getHarbors().then((data) => (harbors.value = data));
});

const openNew = () => {
    harbor.value = {};
    submitted.value = false;
    harborDialog.value = true;
};

const hideDialog = () => {
    harborDialog.value = false;
    submitted.value = false;
};


const saveHarbor = () => {
    submitted.value = true;
    if (harbor.value.name && harbor.value.name.trim() && harbor.value.state) {
        if (harbor.value.id) {
            harborService.putHarbor(harbor.value).then((newHarbor) => {
                harbors.value[findIndexById(harbor.value.id)] = harbor.value;
                toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Porto Alterado', life: 3000 });
                harborDialog.value = false;
            }).catch((error) => {
                toast.add({ severity: 'error', summary: 'Erro', detail: 'Falha ao alterar Porto', life: 3000 });
                console.error('Error ao alterar porto:', error);
            });
            
        } else {
            harborService.postHarbor(harbor.value).then((newHarbor) => {
                harbor.value.id = newHarbor.data.id;
                harbor.value.name = newHarbor.data.name;
                harbor.value.state = newHarbor.data.state;
                harbor.value.created_at = newHarbor.data.created_at;
                harbor.value.updated_at = newHarbor.data.updated_at;
                harbors.value.push(harbor.value);

                toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Porto Criado', life: 3000 });
            }).catch((error) => {
                toast.add({ severity: 'error', summary: 'Erro', detail: 'Falha ao criar Porto', life: 3000 });
                console.error('Error ao criar Porto:', error);
            });
        }
        harborDialog.value = false;
    }
};


const editHarbor = (editHarbor) => {
    harbor.value = { ...editHarbor };
    harborDialog.value = true;
};

const confirmDeleteHarbor = (editHarbor) => { 
    harbor.value = editHarbor;
    deleteHarborDialog.value = true;
};

const deleteHarbor = () => {
    harbors.value = harbors.value.filter((val) => val.id !== harbor.value.id);
    deleteHarborDialog.value = false;
    harbor.value = {};
    toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Porto Deletado', life: 3000 });
};

const findIndexById = (id) => {
    let index = -1;
    for (let i = 0; i < harbors.value.length; i++) {
        if (harbors.value[i].id === id) {
            index = i;
            break;
        }
    }
    return index;
};


const exportCSV = () => {
    dt.value.exportCSV();
};

const confirmDeleteSelected = () => {
    deleteHarborsDialog.value = true;
};
const deleteSelectedHarbors = () => {
    const promises = selectedHarbors.value.map(harbor => {
        return harborService.deleteHarbor(harbor.id);
    });

    Promise.all(promises)
        .then(() => {
            harbors.value = harbors.value.filter((val) => !selectedHarbors.value.includes(val));
            deleteHarborsDialog.value = false;
            selectedHarbors.value = null;
            toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Portos Deletados', life: 3000 });
        })
        .catch((error) => {
            toast.add({ severity: 'error', summary: 'Error', detail: 'Falha ao deletar todos os Portos', life: 3000 });
            console.error('Erro ao deletar todos os Portos:', error);
        });
};

const initFilters = () => {
    filters.value = {
        global: { value: null, matchMode: FilterMatchMode.CONTAINS }
    };
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
                            <Button label="Novo Porto" icon="pi pi-plus" class="mr-2" severity="success" @click="openNew" />
                            <Button label="Deletar" icon="pi pi-trash" severity="danger" @click="confirmDeleteSelected" :disabled="!selectedHarbors || !selectedHarbors.length" />
                        </div>
                    </template>

                    <template v-slot:end>
                        <!-- <FileUpload mode="basic" accept="image/*" :maxFileSize="1000000" label="Import" chooseLabel="Import" class="mr-2 inline-block" /> -->
                        <Button label="Exportar" icon="pi pi-upload" severity="help" @click="exportCSV($event)" />
                    </template>
                </Toolbar>

                <DataTable
                    ref="dt"
                    :value="harbors"
                    v-model:selection="selectedHarbors"
                    dataKey="id"
                    :paginator="true"
                    :rows="10"
                    :filters="filters"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 25]"
                    currentPageReportTemplate=" {first} a {last} de {totalRecords} Portos"
                >
                    <template #header>
                        <div class="flex flex-column md:flex-row md:justify-content-between md:align-items-center">
                            <h5 class="m-0">Portos</h5>
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
                    <Column field="name" header="Nome" :sortable="true" headerStyle="width:35%; min-width:10rem;">
                        <template #body="slotProps">
                            <span class="p-column-title">Nome</span>
                            {{ slotProps.data.name }}
                        </template>
                    </Column>
                    <Column field="state" header="Estado" :sortable="true" headerStyle="width:20%; min-width:8rem;">
                        <template #body="slotProps">
                            <span class="p-column-title">Estado</span>
                            {{ slotProps.data.state }}
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
                            <span class="p-column-title">Atualizado em </span>
                            {{ formatDate(slotProps.data.updated_at) }}
                        </template>
                    </Column>
                    <Column headerStyle="min-width:10rem;">
                        <template #body="slotProps">
                            <Button icon="pi pi-pencil" class="mr-2" severity="success" rounded @click="editHarbor(slotProps.data)" />   
                            <Button icon="pi pi-trash" class="mt-2" severity="danger" rounded @click="confirmDeleteHarbor(slotProps.data)" />
                        </template>
                    </Column>
                </DataTable>

                <Dialog v-model:visible="harborDialog" :style="{ width: '450px' }" header="Harbor Details" :modal="true" class="p-fluid">
                    <div class="field">
                        <label for="name">Name</label>
                        <InputText id="name" v-model.trim="harbor.name" required="true" autofocus :invalid="submitted && !harbor.name" />
                        <small class="p-invalid" v-if="submitted && !harbor.name">Nome é um campo obrigatório.</small>
                    </div>
                    <div class="field">
                        <label for="state">State</label>
                        <InputText id="state" v-model.trim="harbor.state" required="true" :invalid="submitted && !harbor.state" />
                        <small class="p-invalid" v-if="submitted && !harbor.state">Estado é um campo obrigatório.</small>
                    </div>
                    <template #footer>
                        <Button label="Cancel" icon="pi pi-times" text="" @click="hideDialog" />
                        <Button label="Save" icon="pi pi-check" text="" @click="saveHarbor" />
                    </template>
                </Dialog>

                <Dialog v-model:visible="deleteHarborDialog" :style="{ width: '450px' }" header="Confirmar" :modal="true">
                    <div class="flex align-items-center justify-content-center">
                        <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
                        <span v-if="harbor">Realmente deseja apagar <b>{{ harbor.name }}</b>?</span>
                    </div>
                    <template #footer>
                        <Button label="No" icon="pi pi-times" text @click="deleteHarborDialog = false" />
                        <Button label="Yes" icon="pi pi-check" text @click="deleteHarbor" />
                    </template>
                </Dialog>

                <Dialog v-model:visible="deleteHarborsDialog" :style="{ width: '450px' }" header="Confirmar" :modal="true">
                    <div class="flex align-items-center justify-content-center">
                        <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
                        <span v-if="harbor">Realmente deseja apaga?</span>
                    </div>
                    <template #footer>
                        <Button label="No" icon="pi pi-times" text @click="deleteHarborsDialog = false" />
                        <Button label="Yes" icon="pi pi-check" text @click="deleteSelectedHarbors" />
                    </template>
                </Dialog>
            </div>
        </div>
    </div>
</template>
