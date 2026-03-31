<script setup lang="ts">
import DiseaseForm from '@/pages/admin/Diseases/Form.vue';
const props = defineProps<{ disease?: any }>();
</script>
<template>
    <DiseaseForm :disease="disease" base-url="/clinic/diseases" />
</template>
