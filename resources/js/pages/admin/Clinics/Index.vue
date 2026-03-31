<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { Plus, Pencil, Trash2, Building2 } from 'lucide-vue-next';

const props = defineProps<{
    clinics: {
        data: Array<{
            id: number; name: string; phone: string; email: string
            is_active: boolean; diseases_count: number; doctors_count: number; users_count: number
        }>;
        current_page: number; last_page: number;
    }
}>();

function destroy(id: number, name: string) {
    if (confirm(`"${name}" shifoxonasini o'chirishni tasdiqlaysizmi?`)) {
        router.delete(`/admin/clinics/${id}`);
    }
}
</script>

<template>
    <div class="space-y-5">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-gray-900 dark:text-white">Shifoxonalar</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Jami: {{ clinics.data.length }} ta</p>
            </div>
            <Link href="/admin/clinics/create" class="flex items-center gap-2 px-4 py-2 rounded-xl bg-sky-500 hover:bg-sky-600 text-white text-sm font-medium transition">
                <Plus class="size-4" /> Yangi shifoxona
            </Link>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-700">
                    <tr>
                        <th class="text-left px-4 py-3 font-medium text-gray-600 dark:text-gray-400">#</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-600 dark:text-gray-400">Nomi</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-600 dark:text-gray-400">Kasallik</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-600 dark:text-gray-400">Shifokor</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-600 dark:text-gray-400">Holat</th>
                        <th class="px-4 py-3" />
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    <tr v-if="!clinics.data.length">
                        <td colspan="6" class="px-4 py-8 text-center text-gray-400">Hozircha shifoxona yo'q</td>
                    </tr>
                    <tr v-for="(c, i) in clinics.data" :key="c.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                        <td class="px-4 py-3 text-gray-400">{{ i + 1 }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <div class="size-8 rounded-lg bg-sky-100 dark:bg-sky-900/30 flex items-center justify-center">
                                    <Building2 class="size-4 text-sky-500" />
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ c.name }}</p>
                                    <p v-if="c.email" class="text-xs text-gray-400">{{ c.email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ c.diseases_count }}</td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ c.doctors_count }}</td>
                        <td class="px-4 py-3">
                            <span :class="['px-2 py-0.5 rounded-full text-xs font-medium', c.is_active ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-gray-100 text-gray-500']">
                                {{ c.is_active ? 'Faol' : 'Nofaol' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-1 justify-end">
                                <Link :href="`/admin/clinics/${c.id}/edit`" class="p-1.5 rounded-lg hover:bg-sky-50 dark:hover:bg-sky-900/20 text-sky-500 transition">
                                    <Pencil class="size-4" />
                                </Link>
                                <button @click="destroy(c.id, c.name)" class="p-1.5 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 text-red-500 transition">
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
