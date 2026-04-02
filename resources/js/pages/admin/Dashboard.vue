<script setup lang="ts">
import { Building2, Stethoscope, Users, Image, TrendingUp, Info, MessageCircle, Mic, Calendar, Download, Activity, Shield, ArrowUpRight, Plus } from 'lucide-vue-next';
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

interface TopDisease { disease_id: string; name: string; category: string; count: number }
interface TopDoctor { doctor_id: string; name: string; specialization: string; count: number }
interface AgeCat { age_category: string; count: number }
interface GenderStat { gender: string; count: number }
interface DailyItem { date: string; count: number }
interface AdminActionItem { id: string; admin_name: string; action_type: string; entity_type: string; details: Record<string,any>|null; ip_address: string; created_at: string }

const props = defineProps<{
    stats: { clinics: number; diseases: number; doctors: number; banners: number; users: number; recommendations: number }
    interaction_stats: { total_interactions: number; text_interactions: number; voice_interactions: number; today_interactions: number; week_interactions: number; unique_users_week: number }
    top_diseases: TopDisease[]
    top_doctors: TopDoctor[]
    age_categories: AgeCat[]
    gender_stats: GenderStat[]
    daily_chart: DailyItem[]
    recent_actions: AdminActionItem[]
}>();

const cards = [
    { label: 'Shifoxonalar', value: props.stats.clinics,  icon: Building2,    color: 'blue',    href: '/admin/clinics' },
    { label: 'Kasalliklar',  value: props.stats.diseases, icon: Stethoscope,  color: 'violet',  href: '/admin/diseases' },
    { label: 'Shifokorlar',  value: props.stats.doctors,  icon: Users,        color: 'emerald', href: '/admin/doctors' },
    { label: 'Bannerlar',    value: props.stats.banners,  icon: Image,        color: 'amber',   href: '/admin/banners' },
    { label: 'Foydalanuvchilar', value: props.stats.users, icon: Users,       color: 'rose',    href: '/admin/users' },
    { label: 'Tavsiyalar', value: props.stats.recommendations, icon: Stethoscope, color: 'cyan', href: '/admin/recommendations' },
];

const interactionCards = [
    { label: 'Jami murojaat', value: props.interaction_stats.total_interactions, icon: Activity, color: 'blue' },
    { label: 'Matn',          value: props.interaction_stats.text_interactions,  icon: MessageCircle, color: 'indigo' },
    { label: 'Ovoz',          value: props.interaction_stats.voice_interactions, icon: Mic, color: 'violet' },
    { label: 'Bugun',         value: props.interaction_stats.today_interactions, icon: Calendar, color: 'emerald' },
    { label: 'Haftalik',      value: props.interaction_stats.week_interactions,  icon: TrendingUp, color: 'amber' },
    { label: 'Unikal (hafta)', value: props.interaction_stats.unique_users_week, icon: Users, color: 'rose' },
];

const maxDaily = computed(() => Math.max(...props.daily_chart.map(d => d.count), 1));

const actionTypeLabels: Record<string, string> = {
    create: 'Yaratish',
    update: 'Tahrirlash',
    delete: "O'chirish",
};

const genderLabels: Record<string, string> = { male: 'Erkak', female: 'Ayol' };

function colorClasses(color: string) {
    const map: Record<string, { bg: string; text: string; light: string }> = {
        blue:    { bg: 'bg-blue-600',    text: 'text-blue-600',    light: 'bg-blue-50 dark:bg-blue-950/40' },
        violet:  { bg: 'bg-violet-600',  text: 'text-violet-600',  light: 'bg-violet-50 dark:bg-violet-950/40' },
        emerald: { bg: 'bg-emerald-600', text: 'text-emerald-600', light: 'bg-emerald-50 dark:bg-emerald-950/40' },
        amber:   { bg: 'bg-amber-500',   text: 'text-amber-600',   light: 'bg-amber-50 dark:bg-amber-950/40' },
        rose:    { bg: 'bg-rose-600',     text: 'text-rose-600',    light: 'bg-rose-50 dark:bg-rose-950/40' },
        cyan:    { bg: 'bg-cyan-600',     text: 'text-cyan-600',    light: 'bg-cyan-50 dark:bg-cyan-950/40' },
        indigo:  { bg: 'bg-indigo-600',   text: 'text-indigo-600',  light: 'bg-indigo-50 dark:bg-indigo-950/40' },
    };
    return map[color] || map.blue;
}
</script>

<template>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Boshqaruv paneli</h1>
                <p class="text-gray-400 text-sm mt-0.5">Umumiy statistika va tahlillar</p>
            </div>
            <a href="/admin/export/statistics" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition shadow-sm">
                <Download class="size-4" /> CSV Export
            </a>
        </div>

        <!-- Entity counts -->
        <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-6 gap-4">
            <Link
                v-for="c in cards"
                :key="c.label"
                :href="c.href"
                class="group relative p-5 rounded-2xl bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 hover:border-gray-200 dark:hover:border-gray-700 hover:shadow-lg hover:shadow-gray-200/50 dark:hover:shadow-none hover:-translate-y-0.5 transition-all duration-300"
            >
                <div :class="['size-10 rounded-xl flex items-center justify-center mb-3', colorClasses(c.color).light]">
                    <component :is="c.icon" :class="['size-5', colorClasses(c.color).text]" />
                </div>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ c.value }}</p>
                <p class="text-xs text-gray-400 mt-0.5">{{ c.label }}</p>
                <ArrowUpRight class="absolute top-4 right-4 size-4 text-gray-300 dark:text-gray-600 opacity-0 group-hover:opacity-100 transition" />
            </Link>
        </div>

        <!-- Interaction stats -->
        <div>
            <h2 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-3">Murojaat statistikasi</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
                <div
                    v-for="ic in interactionCards"
                    :key="ic.label"
                    class="p-4 rounded-2xl bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800"
                >
                    <component :is="ic.icon" :class="['size-4 mb-2', colorClasses(ic.color).text]" />
                    <p class="text-xl font-bold text-gray-900 dark:text-white">{{ ic.value }}</p>
                    <p class="text-[11px] text-gray-400 mt-0.5">{{ ic.label }}</p>
                </div>
            </div>
        </div>

        <!-- Charts row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
            <!-- Daily chart -->
            <div class="p-6 rounded-2xl bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800">
                <h2 class="text-sm font-semibold text-gray-900 dark:text-white mb-5">Kunlik murojaat <span class="text-gray-400 font-normal">(14 kun)</span></h2>
                <div v-if="daily_chart.length" class="flex items-end gap-1.5 h-44">
                    <div
                        v-for="d in daily_chart"
                        :key="d.date"
                        class="flex-1 flex flex-col items-center gap-1.5"
                    >
                        <span class="text-[10px] font-medium text-gray-400">{{ d.count || '' }}</span>
                        <div
                            class="w-full rounded-md bg-gradient-to-t from-blue-600 to-blue-400 transition-all duration-500"
                            :style="{ height: Math.max(d.count / maxDaily * 100, 2) + '%' }"
                        />
                        <span class="text-[9px] text-gray-400 whitespace-nowrap">{{ d.date.slice(5) }}</span>
                    </div>
                </div>
                <p v-else class="text-sm text-gray-400 text-center py-10">Ma'lumot yo'q</p>
            </div>

            <!-- Top diseases -->
            <div class="p-6 rounded-2xl bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800">
                <h2 class="text-sm font-semibold text-gray-900 dark:text-white mb-5">Top kasalliklar</h2>
                <div v-if="top_diseases.length" class="space-y-3.5">
                    <div v-for="(d, i) in top_diseases" :key="d.disease_id" class="flex items-center gap-3">
                        <div class="size-7 rounded-lg bg-violet-50 dark:bg-violet-950/40 flex items-center justify-center text-xs font-bold text-violet-600">{{ i + 1 }}</div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ d.name }}</p>
                            <p class="text-[11px] text-gray-400">{{ d.category }}</p>
                        </div>
                        <span class="text-sm font-bold text-violet-600 bg-violet-50 dark:bg-violet-950/40 px-2.5 py-0.5 rounded-lg">{{ d.count }}</span>
                    </div>
                </div>
                <p v-else class="text-sm text-gray-400 text-center py-10">Ma'lumot yo'q</p>
            </div>
        </div>

        <!-- Second row: top doctors + demographics -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
            <!-- Top doctors -->
            <div class="p-6 rounded-2xl bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800">
                <h2 class="text-sm font-semibold text-gray-900 dark:text-white mb-5">Top shifokorlar</h2>
                <div v-if="top_doctors.length" class="space-y-3.5">
                    <div v-for="(d, i) in top_doctors" :key="d.doctor_id" class="flex items-center gap-3">
                        <div class="size-7 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 flex items-center justify-center text-xs font-bold text-emerald-600">{{ i + 1 }}</div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ d.name }}</p>
                            <p class="text-[11px] text-gray-400">{{ d.specialization }}</p>
                        </div>
                        <span class="text-sm font-bold text-emerald-600 bg-emerald-50 dark:bg-emerald-950/40 px-2.5 py-0.5 rounded-lg">{{ d.count }}</span>
                    </div>
                </div>
                <p v-else class="text-sm text-gray-400 text-center py-10">Ma'lumot yo'q</p>
            </div>

            <!-- Age categories -->
            <div class="p-6 rounded-2xl bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800">
                <h2 class="text-sm font-semibold text-gray-900 dark:text-white mb-5">Yosh guruhlari</h2>
                <div v-if="age_categories.length" class="space-y-3">
                    <div v-for="a in age_categories" :key="a.age_category" class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-300">{{ a.age_category }}</span>
                        <span class="text-sm font-bold text-indigo-600 bg-indigo-50 dark:bg-indigo-950/40 px-2.5 py-0.5 rounded-lg">{{ a.count }}</span>
                    </div>
                </div>
                <p v-else class="text-sm text-gray-400 text-center py-10">Ma'lumot yo'q</p>
            </div>

            <!-- Gender stats -->
            <div class="p-6 rounded-2xl bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800">
                <h2 class="text-sm font-semibold text-gray-900 dark:text-white mb-5">Jins bo'yicha</h2>
                <div v-if="gender_stats.length" class="space-y-3">
                    <div v-for="g in gender_stats" :key="g.gender" class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-300">{{ genderLabels[g.gender] || g.gender }}</span>
                        <span class="text-sm font-bold text-rose-600 bg-rose-50 dark:bg-rose-950/40 px-2.5 py-0.5 rounded-lg">{{ g.count }}</span>
                    </div>
                </div>
                <p v-else class="text-sm text-gray-400 text-center py-10">Ma'lumot yo'q</p>
            </div>
        </div>

        <!-- Admin actions + Quick links -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
            <!-- Recent admin actions -->
            <div class="p-6 rounded-2xl bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800">
                <h2 class="text-sm font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <Shield class="size-4 text-gray-400" /> So'nggi admin harakatlari
                </h2>
                <div v-if="recent_actions.length" class="space-y-2.5 max-h-72 overflow-y-auto">
                    <div v-for="a in recent_actions" :key="a.id" class="flex items-start gap-2.5 text-sm pb-2.5 border-b border-gray-50 dark:border-gray-800 last:border-0">
                        <span class="font-semibold text-gray-700 dark:text-gray-200 whitespace-nowrap">{{ a.admin_name }}</span>
                        <span :class="[
                            'px-2 py-0.5 rounded-md text-[11px] font-semibold shrink-0',
                            a.action_type === 'create' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400' :
                            a.action_type === 'update' ? 'bg-blue-50 text-blue-700 dark:bg-blue-950/40 dark:text-blue-400' :
                            'bg-red-50 text-red-700 dark:bg-red-950/40 dark:text-red-400'
                        ]">{{ actionTypeLabels[a.action_type] || a.action_type }}</span>
                        <span class="text-gray-400 truncate flex-1">{{ a.entity_type }}</span>
                        <span class="text-[11px] text-gray-300 whitespace-nowrap">{{ a.created_at }}</span>
                    </div>
                </div>
                <p v-else class="text-sm text-gray-400 text-center py-6">Hali harakatlar yo'q</p>
            </div>

            <!-- Quick links + guide -->
            <div class="space-y-5">
                <div class="p-6 rounded-2xl bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800">
                    <h2 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Tezkor harakatlar</h2>
                    <div class="grid grid-cols-2 gap-2">
                        <Link href="/admin/clinics/create" class="flex items-center gap-2 px-3 py-2.5 rounded-xl bg-gray-50 dark:bg-gray-800 hover:bg-blue-50 dark:hover:bg-blue-950/30 text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-blue-700 dark:hover:text-blue-400 transition">
                            <Plus class="size-3.5" /> Shifoxona
                        </Link>
                        <Link href="/admin/diseases/create" class="flex items-center gap-2 px-3 py-2.5 rounded-xl bg-gray-50 dark:bg-gray-800 hover:bg-blue-50 dark:hover:bg-blue-950/30 text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-blue-700 dark:hover:text-blue-400 transition">
                            <Plus class="size-3.5" /> Kasallik
                        </Link>
                        <Link href="/admin/doctors/create" class="flex items-center gap-2 px-3 py-2.5 rounded-xl bg-gray-50 dark:bg-gray-800 hover:bg-blue-50 dark:hover:bg-blue-950/30 text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-blue-700 dark:hover:text-blue-400 transition">
                            <Plus class="size-3.5" /> Shifokor
                        </Link>
                        <Link href="/admin/banners/create" class="flex items-center gap-2 px-3 py-2.5 rounded-xl bg-gray-50 dark:bg-gray-800 hover:bg-blue-50 dark:hover:bg-blue-950/30 text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-blue-700 dark:hover:text-blue-400 transition">
                            <Plus class="size-3.5" /> Banner
                        </Link>
                        <Link href="/admin/users/create" class="flex items-center gap-2 px-3 py-2.5 rounded-xl bg-gray-50 dark:bg-gray-800 hover:bg-blue-50 dark:hover:bg-blue-950/30 text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-blue-700 dark:hover:text-blue-400 transition">
                            <Plus class="size-3.5" /> Foydalanuvchi
                        </Link>
                        <Link href="/admin/recommendations/create" class="flex items-center gap-2 px-3 py-2.5 rounded-xl bg-gray-50 dark:bg-gray-800 hover:bg-blue-50 dark:hover:bg-blue-950/30 text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-blue-700 dark:hover:text-blue-400 transition">
                            <Plus class="size-3.5" /> Tavsiya
                        </Link>
                    </div>
                </div>
                <div class="p-6 rounded-2xl bg-blue-50 dark:bg-blue-950/20 border border-blue-100 dark:border-blue-900/40">
                    <h2 class="text-sm font-semibold text-blue-800 dark:text-blue-200 mb-3 flex items-center gap-2"><Info class="size-4" /> Qo'llanma</h2>
                    <ol class="space-y-2 text-sm text-blue-700 dark:text-blue-300 list-decimal ml-4">
                        <li>Yangi shifoxona yarating (admin akkaunt avtomatik yaratiladi)</li>
                        <li>Shifoxona admini kasalliklar va shifokorlar qo'shadi</li>
                        <li>Har bir kasallik uchun tavsiya matnlari yoziladi</li>
                        <li>Foydalanuvchilar chat orqali AI tavsiya oladi</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</template>
