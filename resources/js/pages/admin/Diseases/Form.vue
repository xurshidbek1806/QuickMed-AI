<script setup lang="ts">
import { useForm, Link } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';

const props = defineProps<{
    disease?: any;
    clinics?: Array<{ id: number; name: string }>;
    baseUrl?: string;
}>();

const isEdit = !!props.disease;
const base   = props.baseUrl ?? '/admin/diseases';

const form = useForm({
    clinic_id:   props.disease?.clinic_id ?? '',
    name:        props.disease?.name ?? '',
    category:    props.disease?.category ?? '',
    description: props.disease?.description ?? '',
    min_age:     props.disease?.min_age ?? 0,
    max_age:     props.disease?.max_age ?? 150,
    is_active:   props.disease?.is_active ?? true,
});

function submit() {
    if (isEdit) {
        form.put(`${base}/${props.disease.id}`);
    } else {
        form.post(base);
    }
}
</script>

<template>
    <div class="max-w-2xl space-y-6">
        <div class="flex items-center gap-3">
            <Link :href="base" class="p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                <ArrowLeft class="size-5 text-gray-600 dark:text-gray-400" />
            </Link>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">
                {{ isEdit ? 'Kasallikni tahrirlash' : 'Yangi kasallik' }}
            </h1>
        </div>

        <form @submit.prevent="submit" class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5 space-y-4">
            <div v-if="clinics?.length">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Shifoxona</label>
                <select v-model="form.clinic_id" class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-sky-400">
                    <option value="">Umumiy (barcha uchun)</option>
                    <option v-for="c in clinics" :key="c.id" :value="c.id">{{ c.name }}</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nomi *</label>
                <input v-model="form.name" type="text" required class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-sky-400" />
                <p v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kategoriya</label>
                <input v-model="form.category" type="text" placeholder="Masalan: Nafas yo'llari, Yurak..." class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-sky-400" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tavsif</label>
                <textarea v-model="form.description" rows="3" class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-sm resize-none focus:outline-none focus:ring-2 focus:ring-sky-400" />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Minimal yosh</label>
                    <input v-model.number="form.min_age" type="number" min="0" max="150" class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-sky-400" />
                    <p v-if="form.errors.min_age" class="text-red-500 text-xs mt-1">{{ form.errors.min_age }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Maksimal yosh</label>
                    <input v-model.number="form.max_age" type="number" min="0" max="150" class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-sky-400" />
                    <p v-if="form.errors.max_age" class="text-red-500 text-xs mt-1">{{ form.errors.max_age }}</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <input v-model="form.is_active" type="checkbox" id="is_active" class="size-4 rounded" />
                <label for="is_active" class="text-sm text-gray-700 dark:text-gray-300">Faol holat</label>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" :disabled="form.processing" class="px-6 py-2.5 rounded-xl bg-sky-500 hover:bg-sky-600 disabled:opacity-50 text-white font-medium text-sm transition">
                    {{ form.processing ? 'Saqlanmoqda...' : isEdit ? 'Saqlash' : 'Qo\'shish' }}
                </button>
                <Link :href="base" class="px-6 py-2.5 rounded-xl bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium text-sm transition">
                    Bekor qilish
                </Link>
            </div>
        </form>
    </div>
</template>
