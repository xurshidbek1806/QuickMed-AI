<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { Plus, Pencil, Trash2 } from 'lucide-vue-next';

const props = defineProps<{
    recommendations: {
        data: Array<{
            id: string; recommendation_text: string; priority: number; is_active: boolean;
            disease?: { name: string }; doctor?: { name: string; specialization: string }
        }>;
        current_page: number; last_page: number;
    }
}>();

function destroy(id: string) {
    if (confirm("Bu tavsiyani o'chirishni tasdiqlaysizmi?")) {
        router.delete(`/clinic/recommendations/${id}`);
    }
}
</script>

<template>
    <div class="space-y-5">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-gray-900 dark:text-white">Tavsiyalar</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Jami: {{ recommendations.data.length }}</p>
            </div>
            <Link href="/clinic/recommendations/create" class="flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-medium transition">
                <Plus class="size-4" /> Yangi tavsiya
            </Link>
        </div>

        <div class="space-y-3">
            <div v-if="!recommendations.data.length" class="text-center py-10 text-gray-400">
                Hozircha tavsiya yo'q
            </div>
            <div
                v-for="r in recommendations.data"
                :key="r.id"
                class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-4"
            >
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap mb-2">
                            <span v-if="r.disease" class="px-2 py-0.5 rounded-full bg-sky-100 dark:bg-sky-900/30 text-sky-700 dark:text-sky-400 text-xs font-medium">
                                🦠 {{ r.disease.name }}
                            </span>
                            <span v-if="r.doctor" class="px-2 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 text-xs font-medium">
                                👨‍⚕️ {{ r.doctor.name }}
                            </span>
                            <span class="px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-xs">
                                Ustuvorlik: {{ r.priority }}
                            </span>
                            <span :class="['ml-auto px-2 py-0.5 rounded-full text-xs font-medium', r.is_active ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-gray-100 text-gray-500']">
                                {{ r.is_active ? 'Faol' : 'Nofaol' }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-700 dark:text-gray-300 line-clamp-3">{{ r.recommendation_text }}</p>
                    </div>
                    <div class="flex items-center gap-1 shrink-0">
                        <Link :href="`/clinic/recommendations/${r.id}/edit`" class="p-1.5 rounded-lg hover:bg-sky-50 dark:hover:bg-sky-900/20 text-sky-500 transition">
                            <Pencil class="size-4" />
                        </Link>
                        <button @click="destroy(r.id)" class="p-1.5 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 text-red-500 transition">
                            <Trash2 class="size-4" />
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
