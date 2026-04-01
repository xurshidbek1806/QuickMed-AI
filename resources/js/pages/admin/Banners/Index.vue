<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { Plus, Pencil, Trash2, ImageIcon, Video, FileText, File } from 'lucide-vue-next';

const props = defineProps<{
    banners: {
        data: Array<{ id: number; title: string; media_type: string; position: string; is_active: boolean; clinic?: { name: string }; media_url?: string }>;
        current_page: number; last_page: number;
    };
    baseUrl?: string;
}>();

const base = props.baseUrl ?? '/admin/banners';

const positionLabel: Record<string, string> = { sidebar: 'Sidebar', top: 'Yuqori', chat: 'Chat' };
const typeLabel: Record<string, string> = { image: 'Rasm', video: 'Video', text: 'Matn' };

function destroy(id: number, name: string) {
    if (confirm(`"${name}" bannerni o'chirishni tasdiqlaysizmi?`)) {
        router.delete(`${base}/${id}`);
    }
}
</script>

<template>
    <div class="space-y-5">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-gray-900 dark:text-white">Bannerlar</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Jami: {{ banners.data.length }}</p>
            </div>
            <Link :href="`${base}/create`" class="flex items-center gap-2 px-4 py-2 rounded-xl bg-sky-500 hover:bg-sky-600 text-white text-sm font-medium transition">
                <Plus class="size-4" /> Yangi banner
            </Link>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div v-if="!banners.data.length" class="col-span-full text-center py-10 text-gray-400">
                Hozircha banner yo'q
            </div>
            <div
                v-for="b in banners.data"
                :key="b.id"
                class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden"
            >
                <div class="aspect-video bg-gray-100 dark:bg-gray-700 relative">
                    <img v-if="b.media_url && b.media_type !== 'video'" :src="b.media_url" class="size-full object-cover" />
                    <video v-else-if="b.media_url" :src="b.media_url" class="size-full object-cover" muted />
                    <div v-else class="size-full flex items-center justify-center text-gray-400">
                        <component :is="b.media_type === 'video' ? Video : b.media_type === 'image' ? ImageIcon : FileText" class="size-8" />
                    </div>
                    <span class="absolute top-2 right-2 px-2 py-0.5 rounded-full text-[11px] font-medium bg-black/60 text-white">
                        {{ positionLabel[b.position] }}
                    </span>
                </div>
                <div class="p-3">
                    <p class="font-semibold text-gray-900 dark:text-white text-sm truncate">{{ b.title }}</p>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="text-xs text-gray-400">{{ typeLabel[b.media_type] }}</span>
                        <span v-if="b.clinic" class="text-xs text-gray-400">· {{ b.clinic.name }}</span>
                        <span :class="['ml-auto px-2 py-0.5 rounded-full text-[11px] font-medium', b.is_active ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-gray-100 text-gray-500']">
                            {{ b.is_active ? 'Faol' : 'Nofaol' }}
                        </span>
                    </div>
                    <div class="flex items-center gap-2 mt-3 pt-2 border-t border-gray-100 dark:border-gray-700">
                        <Link :href="`${base}/${b.id}/edit`" class="flex-1 text-center px-3 py-1.5 rounded-lg bg-sky-50 dark:bg-sky-900/20 text-sky-600 text-xs font-medium hover:bg-sky-100 transition">
                            <Pencil class="size-3 inline mr-1" /> Tahrirlash
                        </Link>
                        <button @click="destroy(b.id, b.title)" class="flex-1 px-3 py-1.5 rounded-lg bg-red-50 dark:bg-red-900/20 text-red-600 text-xs font-medium hover:bg-red-100 transition">
                            <Trash2 class="size-3 inline mr-1" /> O'chirish
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
