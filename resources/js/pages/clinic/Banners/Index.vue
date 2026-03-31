<script setup lang="ts">
import BannerIndex from '@/pages/admin/Banners/Index.vue';
const props = defineProps<{ banners: any }>();
</script>
<template>
    <BannerIndex :banners="banners" base-url="/clinic/banners" />
</template>
