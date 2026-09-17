<template>

</template>
<script setup lang="ts">
import categories from '@/routes/categories';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Categorias',
                href: categories.index.url(),
            },
        ],
    },
});

</script>