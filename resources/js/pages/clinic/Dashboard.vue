<script setup lang="ts">
import { Stethoscope, Users, ClipboardList, Image, TrendingUp } from 'lucide-vue-next';
import { Link } from '@inertiajs/vue3';

const props = defineProps<{
    stats:  { diseases: number; doctors: number; recommendations: number; banners: number };
    clinic: { id: number; name: string; phone: string; email: string } | null;
}>();

const cards = [
    { label: 'Kasalliklar',  value: props.stats.diseases,        icon: Stethoscope,   bg: 'bg-sky-100 dark:bg-sky-900/30',       text: 'text-sky-500',     href: '/clinic/diseases'        },
    { label: 'Shifokorlar',  value: props.stats.doctors,         icon: Users,         bg: 'bg-emerald-100 dark:bg-emerald-900/30', text: 'text-emerald-500', href: '/clinic/doctors'         },
    { label: 'Tavsiyalar',   value: props.stats.recommendations, icon: ClipboardList, bg: 'bg-violet-100 dark:bg-violet-900/30',   text: 'text-violet-500',  href: '/clinic/recommendations' },
    { label: 'Bannerlar',    value: props.stats.banners,         icon: Image,         bg: 'bg-amber-100 dark:bg-amber-900/30',     text: 'text-amber-500',   href: '/clinic/banners'         },
];
</script>

<template>
    <div class="space-y-6">
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ clinic?.name ?? 'Shifoxona paneli' }}</h1>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Umumiy statistika</p>
            </div>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <Link
                v-for="c in cards"
                :key="c.label"
                :href="c.href"
                class="group flex items-center gap-4 p-5 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200"
            >
                <div :class="['size-12 rounded-xl flex items-center justify-center', c.bg]">
                    <component :is="c.icon" :class="['size-5', c.text]" />
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ c.value }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ c.label }}</p>
                </div>
            </Link>
        </div>

        <div class="p-5 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
            <h2 class="font-semibold text-gray-800 dark:text-white mb-3">Tezkor havolalar</h2>
            <div class="space-y-2">
                <Link href="/clinic/diseases/create" class="flex items-center gap-2 text-sm text-emerald-600 hover:underline">+ Yangi kasallik qo'shish</Link>
                <Link href="/clinic/doctors/create" class="flex items-center gap-2 text-sm text-emerald-600 hover:underline">+ Yangi shifokor qo'shish</Link>
                <Link href="/clinic/recommendations/create" class="flex items-center gap-2 text-sm text-emerald-600 hover:underline">+ Yangi tavsiya yozish</Link>
                <Link href="/clinic/banners/create" class="flex items-center gap-2 text-sm text-emerald-600 hover:underline">+ Yangi banner qo'shish</Link>
            </div>
        </div>
    </div>
</template>
