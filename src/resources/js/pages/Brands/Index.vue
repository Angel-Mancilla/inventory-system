<template>
    <Head title="Brands"/>

    <div class="p-4">
        <h1>Crear Marca</h1>

        <!-- En Vue, v-bind toma el objeto generado y lo convierte en action="..." method="..." -->
        <!-- Seguimos usando @submit.prevent para que Inertia tome el control y no recargue la página -->
        <form v-bind="store.form.post()" @submit.prevent="submit">
            
            <input type="text" v-model="form.name" placeholder="Nombre">
            
            <button type="submit">Guardar</button>
        </form>

        {{ marcas.data }}
    </div>
</template>

<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import brands from '@/routes/brands';
import { store } from '@/actions/App/Http/Controllers/BrandController';

defineProps<{
    marcas: {
        data: any[],
    }
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

const form = useForm({
    name: '',
});

// Función de envío limpia e integrada con Wayfinder
const submit = () => {
    // Wayfinder nos da { action: '/brands', method: 'post' }
    const route = store.form.post(); 
    
    // Le pasamos esos valores exactos a Inertia
    form.submit(route.method, route.action, {
        onSuccess: () => form.reset(),
    });
};
</script>