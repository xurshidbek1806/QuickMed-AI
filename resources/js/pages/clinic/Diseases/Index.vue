<script setup lang="ts">
// Re-uses admin Diseases/Index with clinic base URL
import DiseaseIndex from '@/pages/admin/Diseases/Index.vue';
const props = defineProps<{ diseases: any }>();
</script>
<template>
    <DiseaseIndex :diseases="diseases" base-url="/clinic/diseases" />
</template>
