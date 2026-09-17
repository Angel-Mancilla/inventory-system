<template>
    <Head title="Brands"/>

    <div class="p-4 space-y-2">

        <div class="flex items-center justify-between">
            <Button class="cursor-pointer"  @click="showModal = true">Crear Marca</Button>
        </div>
        
        <FormDialog
        v-model:open="showModal"
        title="Crear Marca"
        description="Agrega una nueva marca al catálogo."
        form-id="create-brand-form"
        :processing="form.processing"
        >
        <form id="create-brand-form" @submit.prevent="submit" class="space-y-4">
            <div class="space-y-2 space-x-2">
            <Label for="name">Nombre</Label>
            <Input id="name" v-model="form.name" placeholder="Nombre" />
            <p v-if="form.errors.name" class="text-sm text-destructive">
                {{ form.errors.name }}
            </p>
            </div>
        </form>
        </FormDialog>

        <div>
            <DataTable :columns="columns" :data="marcas.data"/>

            <DataTablePagination
                :links="marcas.links"
                :from="marcas.from"
                :to="marcas.to"
                :total="marcas.total"
            />
        </div>
        

    </div>
</template>

<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3';
import brands from '@/routes/brands';
import { store } from '@/actions/App/Http/Controllers/BrandController';
import DataTable from '@/components/data-table/DataTable.vue';
import DataTablePagination from '@/components/sorting/pagination/DataTablePagination.vue';
import { createColumnHelper } from '@tanstack/vue-table';
import { ref, h } from 'vue';
import FormDialog from '@/components/FormDialog.vue';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import Badge from '@/components/ui/badge/Badge.vue';
import Switch from '@/components/ui/switch/Switch.vue';

interface PaginatedResponse<T> {
  data: T[]
  links: { url: string | null; label: string; active: boolean }[]
  from: number
  to: number
  total: number
}

export interface Brand {
    id: number;
    name: string;
}

defineProps<{
    marcas: 
        PaginatedResponse<Brand>
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Marcas',
                href: brands.index.url(),
            },
        ],
    },
});

const columnHelper = createColumnHelper<Brand>();

const columns = [
    columnHelper.accessor('id', {
        header: 'ID',
        size: 50,
    }),

    columnHelper.accessor('name', {
        header: 'Nombre',

    }),

    columnHelper.accessor('is_active', {
        header: 'Estado',
        cell: (info) => h(Switch, {
        modelValue: info.getValue(),
        'onUpdate:modelValue': () => toggleActive(info.row.original.id),
        }),
    }),
    
];

const showModal = ref(false);

const form = useForm({
    name: '',
});

const submit = () => {
    const route = store.form.post();

     form.submit(route.method, route.action, {
    onSuccess: () => {
      form.reset();
      showModal.value = false;
    },
  });
};

function toggleActive(brandId: number) {
    router.patch(brands.toogleActive.url(brandId), {},{
        preserveScroll: true,
        preserveState: true,
    })
}
</script>