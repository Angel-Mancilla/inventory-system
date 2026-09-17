<template>

</template>
<script setup lang="ts">
import sales from '@/routes/sales';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Ventas',
                href: sales.index.url(),
            },
        ],
    },
});
</script>