<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { Plus, Pencil, Trash2, Link as LinkIcon } from 'lucide-vue-next';

defineProps<{
    recommendations: {
        data: Array<{
            id: string;
            recommendation_text: string;
            priority: number;
            is_active: boolean;
            disease: { id: string; name: string } | null;
            doctor: { id: string; name: string } | null;
        }>;
        links: Array<{ url: string | null; label: string; active: boolean }>;
    };
}>();

function destroy(id: string) {
    if (confirm('Tavsiyani o\'chirmoqchimisiz?')) {
        router.delete(`/admin/recommendations/${id}`);
    }
}
</script>

<template>
    <div class="space-y-5">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-gray-900 dark:text-white">Tavsiyalar</h1>
                <p class="text-sm text-gray-500 mt-0.5">Kasallik-shifokor tavsiyalari</p>
            </div>
            <Link href="/admin/recommendations/create"
                class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-sky-500 hover:bg-sky-600 text-white text-sm font-medium transition">
                <Plus class="size-4" /> Qo'shish
            </Link>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="text-left px-4 py-3 font-medium text-gray-600 dark:text-gray-300">Kasallik</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-600 dark:text-gray-300">Shifokor</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-600 dark:text-gray-300">Tavsiya</th>
                        <th class="text-center px-4 py-3 font-medium text-gray-600 dark:text-gray-300">Ustunlik</th>
                        <th class="text-center px-4 py-3 font-medium text-gray-600 dark:text-gray-300">Holat</th>
                        <th class="text-right px-4 py-3 font-medium text-gray-600 dark:text-gray-300">Amallar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    <tr v-for="rec in recommendations.data" :key="rec.id"
                        class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                        <td class="px-4 py-3 text-gray-900 dark:text-white">{{ rec.disease?.name ?? '—' }}</td>
                        <td class="px-4 py-3 text-gray-900 dark:text-white">{{ rec.doctor?.name ?? '—' }}</td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-400 max-w-xs truncate">
                            {{ rec.recommendation_text.length > 60 ? rec.recommendation_text.slice(0, 60) + '…' : rec.recommendation_text }}
                        </td>
                        <td class="px-4 py-3 text-center text-gray-900 dark:text-white">{{ rec.priority }}</td>
                        <td class="px-4 py-3 text-center">
                            <span :class="rec.is_active
                                ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400'
                                : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400'"
                                class="inline-block px-2 py-0.5 rounded-full text-xs font-medium">
                                {{ rec.is_active ? 'Faol' : 'Nofaol' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-1">
                                <Link :href="`/admin/recommendations/${rec.id}/edit`"
                                    class="p-2 rounded-lg hover:bg-sky-50 dark:hover:bg-sky-900/20 text-sky-600 transition">
                                    <Pencil class="size-4" />
                                </Link>
                                <button @click="destroy(rec.id)"
                                    class="p-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 text-red-500 transition">
                                    <Trash2 class="size-4" />
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="!recommendations.data.length">
                        <td colspan="6" class="px-4 py-8 text-center text-gray-400">Tavsiyalar topilmadi</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div v-if="recommendations.links.length > 3" class="flex justify-center gap-1">
            <template v-for="link in recommendations.links" :key="link.label">
                <Link v-if="link.url" :href="link.url"
                    :class="link.active ? 'bg-sky-500 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'"
                    class="px-3 py-1.5 rounded-lg text-sm border border-gray-200 dark:border-gray-700 transition"
                    v-html="link.label" />
                <span v-else class="px-3 py-1.5 text-sm text-gray-400" v-html="link.label" />
            </template>
        </div>
    </div>
</template>
