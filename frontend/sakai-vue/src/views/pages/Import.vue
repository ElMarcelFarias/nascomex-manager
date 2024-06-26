<script setup>
import { ref, onMounted, onBeforeMount } from 'vue';
import { ImportService } from '@/service/ImportService';
import { useToast } from 'primevue/usetoast';

const toast = useToast();

const imports = ref(null);
const importDialog = ref(false);
const deleteImportDialog = ref(false);
const deleteImportsDialog = ref(false);
const importer = ref({});
const selectedImports = ref(null);
const dt = ref(null);
const filters = ref({});
const submitted = ref(false);

const importService = new ImportService();

onBeforeMount(() => {
    initFilters();
});
onMounted(() => {
    importService.getImports().then((data) => (imports.value = data));
});

const openNew = () => {
    importer.value = {};
    submitted.value = false;
    importDialog.value = true;
};

const hideDialog = () => {
    importDialog.value = false;
    submitted.value = false;
};

const saveImport = () => {
    submitted.value = true;
    if (importer.value.nome && importer.value.nome.trim() && importer.value.status) {
        if (importer.value.id) {
            importService.putImport(importer.value).then(() => {
                imports.value[findIndexById(importer.value.id)] = importer.value;
                toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Importador Alterado', life: 3000 });
                importDialog.value = false;
            }).catch((error) => {
                toast.add({ severity: 'error', summary: 'Erro', detail: 'Falha ao alterar Importador', life: 3000 });
                console.error('Error ao alterar importador:', error);
            });
        } else {
            importService.postImport(importer.value).then((newImport) => {
                importer.value.id = newImport.data.id;
                importer.value.nome = newImport.data.nome;
                importer.value.status = newImport.data.status;
                importer.value.created_at = newImport.data.created_at;
                importer.value.updated_at = newImport.data.updated_at;
                imports.value.push(importer.value);
                toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Importador Criado', life: 3000 });
            }).catch((error) => {
                toast.add({ severity: 'error', summary: 'Erro', detail: 'Falha ao criar Importador', life: 3000 });
                console.error('Error ao criar Importador:', error);
            });
        }
        importDialog.value = false;
    }
};

const editImport = (editImport) => {
    importer.value = { ...editImport };
    importDialog.value = true;
};

const confirmDeleteImport = (editImport) => { 
    importer.value = editImport;
    deleteImportDialog.value = true;
};

const deleteImport = () => {
    importService.deleteImport(importer.value.id).then(() => {
        imports.value = imports.value.filter((val) => val.id !== importer.value.id);
        deleteImportDialog.value = false;
        importer.value = {};
        toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Importador Deletado', life: 3000 });
    }).catch((error) => {
        toast.add({ severity: 'error', summary: 'Erro', detail: 'Falha ao deletar Importador', life: 3000 });
        console.error('Error ao deletar importador:', error);
    });
};

const findIndexById = (id) => {
    let index = -1;
    for (let i = 0; i < imports.value.length; i++) {
        if (imports.value[i].id === id) {
            index = i;
            break;
        }
    }
    return index;
};

const confirmDeleteSelected = () => {
    deleteImportsDialog.value = true;
};

const deleteSelectedImports = () => {
    const promises = selectedImports.value.map(importer => importService.deleteImport(importer.id));

    Promise.all(promises)
        .then(() => {
            imports.value = imports.value.filter((val) => !selectedImports.value.includes(val));
            deleteImportsDialog.value = false;
            selectedImports.value = null;
            toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Importadores Deletados', life: 3000 });
        })
        .catch((error) => {
            toast.add({ severity: 'error', summary: 'Erro', detail: 'Falha ao deletar todos os Importadores', life: 3000 });
            console.error('Erro ao deletar todos os Importadores:', error);
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
                            <Button label="Novo Importador" icon="pi pi-plus" class="mr-2" severity="success" @click="openNew" />
                            <Button label="Deletar" icon="pi pi-trash" severity="danger" @click="confirmDeleteSelected" :disabled="!selectedImports || !selectedImports.length" />
                        </div>
                    </template>

                    <template v-slot:end>
                        <Button label="Exportar" icon="pi pi-upload" severity="help" @click="exportCSV($event)" />
                    </template>
                </Toolbar>

                <DataTable
                    ref="dt"
                    :value="imports"
                    v-model:selection="selectedImports"
                    dataKey="id"
                    :paginator="true"
                    :rows="10"
                    :filters="filters"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 25]"
                    currentPageReportTemplate=" {first} a {last} de {totalRecords} Importadores"
                >
                    <template #header>
                        <div class="flex flex-column md:flex-row md:justify-content-between md:align-items-center">
                            <h5 class="m-0">Importadores</h5>
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
                    <Column field="nome" header="Nome" :sortable="true" headerStyle="width:35%; min-width:10rem;">
                        <template #body="slotProps">
                            <span class="p-column-title">Nome</span>
                            {{ slotProps.data.nome }}
                        </template>
                    </Column>
                    <Column field="status" header="Status" :sortable="true" headerStyle="width:20%; min-width:8rem;">
                        <template #body="slotProps">
                            <span class="p-column-title">Status</span>
                            {{ slotProps.data.status }}
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
                            <Button icon="pi pi-pencil" class="mr-2" severity="success" rounded @click="editImport(slotProps.data)" />   
                            <Button icon="pi pi-trash" class="mt-2" severity="danger" rounded @click="confirmDeleteImport(slotProps.data)" />
                        </template>
                    </Column>
                </DataTable>

                <Dialog v-model:visible="importDialog" :style="{ width: '450px' }" header="Importador Detalhes" :modal="true" class="p-fluid">
                    <div class="field">
                        <label for="nome">Nome</label>
                        <InputText id="nome" v-model.trim="importer.nome" required="true" autofocus :invalid="submitted && !importer.nome" />
                        <small class="p-invalid" v-if="submitted && !importer.nome">Nome é um campo obrigatório.</small>
                    </div>
                    <div class="field">
                        <label for="status">Status</label>
                        <InputText id="status" v-model.trim="importer.status" required="true" :invalid="submitted && !importer.status" />
                        <small class="p-invalid" v-if="submitted && !importer.status">Status é um campo obrigatório.</small>
                    </div>
                    <template #footer>
                        <Button label="Cancelar" icon="pi pi-times" text="" @click="hideDialog" />
                        <Button label="Salvar" icon="pi pi-check" text="" @click="saveImport" />
                    </template>
                </Dialog>

                <Dialog v-model:visible="deleteImportDialog" :style="{ width: '450px' }" header="Confirmar" :modal="true">
                    <div class="flex align-items-center justify-content-center">
                        <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
                        <span v-if="importer">Realmente deseja apagar <b>{{ importer.nome }}</b>?</span>
                    </div>
                    <template #footer>
                        <Button label="Não" icon="pi pi-times" text @click="deleteImportDialog = false" />
                        <Button label="Sim" icon="pi pi-check" text @click="deleteImport" />
                    </template>
                </Dialog>

                <Dialog v-model:visible="deleteImportsDialog" :style="{ width: '450px' }" header="Confirmar" :modal="true">
                    <div class="flex align-items-center justify-content-center">
                        <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
                        <span v-if="importer">Realmente deseja apagar?</span>
                    </div>
                    <template #footer>
                        <Button label="Não" icon="pi pi-times" text @click="deleteImportsDialog = false" />
                        <Button label="Sim" icon="pi pi-check" text @click="deleteSelectedImports" />
                    </template>
                </Dialog>
            </div>
        </div>
    </div>
</template>
