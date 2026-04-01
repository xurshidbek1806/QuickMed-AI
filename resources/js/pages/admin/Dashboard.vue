<script setup lang="ts">
import { Building2, Stethoscope, Users, Image, TrendingUp, Info } from 'lucide-vue-next';
import { Link } from '@inertiajs/vue3';

const props = defineProps<{
    stats: { clinics: number; diseases: number; doctors: number; banners: number; users: number; recommendations: number }
}>();

const cards = [
    { label: 'Shifoxonalar', value: props.stats.clinics,  icon: Building2,    bg: 'bg-sky-100 dark:bg-sky-900/30',     text: 'text-sky-500',     href: '/admin/clinics'   },
    { label: 'Kasalliklar',  value: props.stats.diseases, icon: Stethoscope,  bg: 'bg-violet-100 dark:bg-violet-900/30', text: 'text-violet-500',  href: '/admin/diseases'  },
    { label: 'Shifokorlar',  value: props.stats.doctors,  icon: Users,        bg: 'bg-emerald-100 dark:bg-emerald-900/30', text: 'text-emerald-500', href: '/admin/doctors'   },
    { label: 'Bannerlar',    value: props.stats.banners,  icon: Image,        bg: 'bg-amber-100 dark:bg-amber-900/30',   text: 'text-amber-500',   href: '/admin/banners'   },
    { label: 'Foydalanuvchilar', value: props.stats.users, icon: TrendingUp, bg: 'bg-rose-100 dark:bg-rose-900/30', text: 'text-rose-500', href: '/admin/users' },
    { label: 'Tavsiyalar', value: props.stats.recommendations, icon: Stethoscope, bg: 'bg-teal-100 dark:bg-teal-900/30', text: 'text-teal-500', href: '/admin/recommendations' },
];
</script>

<template>
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Boshqaruv paneli</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Umumiy statistika</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">
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

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="p-5 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                <h2 class="font-semibold text-gray-800 dark:text-white mb-3">Tezkor havolalar</h2>
                <div class="space-y-2">
                    <Link href="/admin/clinics/create" class="flex items-center gap-2 text-sm text-sky-600 hover:underline">+ Yangi shifoxona qo'shish</Link>
                    <Link href="/admin/diseases/create" class="flex items-center gap-2 text-sm text-sky-600 hover:underline">+ Yangi kasallik qo'shish</Link>
                    <Link href="/admin/doctors/create" class="flex items-center gap-2 text-sm text-sky-600 hover:underline">+ Yangi shifokor qo'shish</Link>
                    <Link href="/admin/banners/create" class="flex items-center gap-2 text-sm text-sky-600 hover:underline">+ Yangi banner qo'shish</Link>
                    <Link href="/admin/users/create" class="flex items-center gap-2 text-sm text-sky-600 hover:underline">+ Yangi foydalanuvchi qo'shish</Link>
                    <Link href="/admin/recommendations/create" class="flex items-center gap-2 text-sm text-sky-600 hover:underline">+ Yangi tavsiya qo'shish</Link>
                </div>
            </div>
            <div class="p-5 rounded-2xl bg-sky-50 dark:bg-sky-900/20 border border-sky-200 dark:border-sky-800">
                <h2 class="font-semibold text-sky-800 dark:text-sky-200 mb-3 flex items-center gap-1.5"><Info class="size-4" /> Qo'llanma</h2>
                <ol class="space-y-1.5 text-sm text-sky-700 dark:text-sky-300 list-decimal ml-4">
                    <li>Yangi shifoxona yarating (admin akkaunt avtomatik yaratiladi)</li>
                    <li>Shifoxona admini kasalliklar va shifokorlar qo'shadi</li>
                    <li>Har bir kasallik uchun tavsiya matnlari yoziladi</li>
                    <li>Foydalanuvchilar chat orqali AI tavsiya oladi</li>
                </ol>
            </div>
        </div>
    </div>
</template>
