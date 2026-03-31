<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppearanceTabs from '@/components/AppearanceTabs.vue';
import Heading from '@/components/Heading.vue';
import { edit } from '@/routes/appearance';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Tashqi ko\'rinish',
                href: edit(),
            },
        ],
    },
});
</script>

<template>
    <Head title="Tashqi ko'rinish" />

    <h1 class="sr-only">Tashqi ko'rinish sozlamalari</h1>

    <div class="space-y-6">
        <Heading
            variant="small"
            title="Tashqi ko'rinish"
            description="Hisobingizning tashqi ko'rinish sozlamalarini yangilang"
        />
        <AppearanceTabs />
    </div>
</template>
