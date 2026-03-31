<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { Plus, Pencil, Trash2 } from 'lucide-vue-next';

const props = defineProps<{
    diseases: {
        data: Array<{ id: string; name: string; category: string; min_age: number; max_age: number; is_active: boolean; clinic?: { name: string } }>;
        current_page: number; last_page: number;
    };
    baseUrl?: string; // '/admin/diseases' or '/clinic/diseases'
}>();

const base = props.baseUrl ?? '/admin/diseases';

function destroy(id: string, name: string) {
    if (confirm(`"${name}" kasalligini o'chirishni tasdiqlaysizmi?`)) {
        router.delete(`${base}/${id}`);
    }
}
</script>

<template>
    <div class="space-y-5">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-gray-900 dark:text-white">Kasalliklar</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Jami: {{ diseases.data.length }}</p>
            </div>
            <Link :href="`${base}/create`" class="flex items-center gap-2 px-4 py-2 rounded-xl bg-sky-500 hover:bg-sky-600 text-white text-sm font-medium transition">
                <Plus class="size-4" /> Yangi kasallik
            </Link>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-700">
                    <tr>
                        <th class="text-left px-4 py-3 font-medium text-gray-600 dark:text-gray-400">Nomi</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-600 dark:text-gray-400">Kategoriya</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-600 dark:text-gray-400">Yosh</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-600 dark:text-gray-400">Holat</th>
                        <th class="px-4 py-3" />
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    <tr v-if="!diseases.data.length">
                        <td colspan="5" class="px-4 py-8 text-center text-gray-400">Hozircha kasallik yo'q</td>
                    </tr>
                    <tr v-for="d in diseases.data" :key="d.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-900 dark:text-white">{{ d.name }}</p>
                            <p v-if="d.clinic" class="text-xs text-gray-400">{{ d.clinic.name }}</p>
                        </td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ d.category ?? '—' }}</td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ d.min_age }}–{{ d.max_age }} yosh</td>
                        <td class="px-4 py-3">
                            <span :class="['px-2 py-0.5 rounded-full text-xs font-medium', d.is_active ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-gray-100 text-gray-500']">
                                {{ d.is_active ? 'Faol' : 'Nofaol' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-1 justify-end">
                                <Link :href="`${base}/${d.id}/edit`" class="p-1.5 rounded-lg hover:bg-sky-50 dark:hover:bg-sky-900/20 text-sky-500 transition">
                                    <Pencil class="size-4" />
                                </Link>
                                <button @click="destroy(d.id, d.name)" class="p-1.5 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 text-red-500 transition">
                                    <Trash2 class="size-4" />
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
