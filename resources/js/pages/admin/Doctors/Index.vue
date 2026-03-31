<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { Plus, Pencil, Trash2, Phone, MapPin } from 'lucide-vue-next';

const props = defineProps<{
    doctors: {
        data: Array<{ id: string; name: string; specialization: string; phone_number: string; is_active: boolean; clinic?: { name: string }; photo?: string }>;
        current_page: number; last_page: number;
    };
    baseUrl?: string;
}>();

const base = props.baseUrl ?? '/admin/doctors';

function destroy(id: string, name: string) {
    if (confirm(`"${name}" shifokorni o'chirishni tasdiqlaysizmi?`)) {
        router.delete(`${base}/${id}`);
    }
}
</script>

<template>
    <div class="space-y-5">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-gray-900 dark:text-white">Shifokorlar</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Jami: {{ doctors.data.length }}</p>
            </div>
            <Link :href="`${base}/create`" class="flex items-center gap-2 px-4 py-2 rounded-xl bg-sky-500 hover:bg-sky-600 text-white text-sm font-medium transition">
                <Plus class="size-4" /> Yangi shifokor
            </Link>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div v-if="!doctors.data.length" class="col-span-full text-center py-10 text-gray-400">
                Hozircha shifokor yo'q
            </div>
            <div
                v-for="d in doctors.data"
                :key="d.id"
                class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-4 flex flex-col gap-3"
            >
                <div class="flex items-start gap-3">
                    <img v-if="d.photo" :src="d.photo" class="size-12 rounded-xl object-cover shrink-0" />
                    <div v-else class="size-12 rounded-xl bg-sky-100 dark:bg-sky-900/30 flex items-center justify-center text-sky-500 shrink-0 text-lg font-bold">
                        {{ d.name[0] }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-900 dark:text-white text-sm truncate">{{ d.name }}</p>
                        <p v-if="d.specialization" class="text-xs text-sky-500 mt-0.5">{{ d.specialization }}</p>
                        <p v-if="d.clinic" class="text-xs text-gray-400 mt-0.5">{{ d.clinic.name }}</p>
                    </div>
                    <span :class="['shrink-0 px-2 py-0.5 rounded-full text-[11px] font-medium', d.is_active ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-gray-100 text-gray-500']">
                        {{ d.is_active ? 'Faol' : 'Nofaol' }}
                    </span>
                </div>
                <div class="flex items-center gap-2 flex-wrap text-xs">
                    <a v-if="d.phone_number" :href="`tel:${d.phone_number}`" class="flex items-center gap-1 text-gray-500 hover:text-sky-500">
                        <Phone class="size-3" /> {{ d.phone_number }}
                    </a>
                </div>
                <div class="flex items-center gap-2 mt-auto pt-2 border-t border-gray-100 dark:border-gray-700">
                    <Link :href="`${base}/${d.id}/edit`" class="flex-1 text-center px-3 py-1.5 rounded-lg bg-sky-50 dark:bg-sky-900/20 text-sky-600 text-xs font-medium hover:bg-sky-100 transition">
                        <Pencil class="size-3 inline mr-1" /> Tahrirlash
                    </Link>
                    <button @click="destroy(d.id, d.name)" class="flex-1 px-3 py-1.5 rounded-lg bg-red-50 dark:bg-red-900/20 text-red-600 text-xs font-medium hover:bg-red-100 transition">
                        <Trash2 class="size-3 inline mr-1" /> O'chirish
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
