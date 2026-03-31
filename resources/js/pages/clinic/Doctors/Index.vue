<script setup lang="ts">
import DoctorIndex from '@/pages/admin/Doctors/Index.vue';
const props = defineProps<{ doctors: any }>();
</script>
<template>
    <DoctorIndex :doctors="doctors" base-url="/clinic/doctors" />
</template>
