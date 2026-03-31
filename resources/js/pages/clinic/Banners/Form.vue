<script setup lang="ts">
import BannerForm from '@/pages/admin/Banners/Form.vue';
const props = defineProps<{ banner?: any }>();
</script>
<template>
    <BannerForm :banner="banner" base-url="/clinic/banners" />
</template>
