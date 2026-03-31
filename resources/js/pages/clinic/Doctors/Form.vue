<script setup lang="ts">
import DoctorForm from '@/pages/admin/Doctors/Form.vue';
const props = defineProps<{ doctor?: any }>();
</script>
<template>
    <DoctorForm :doctor="doctor" base-url="/clinic/doctors" />
</template>
