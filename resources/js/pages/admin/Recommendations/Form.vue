<script setup lang="ts">
import { useForm, Link } from '@inertiajs/vue3';
import { ArrowLeft, Save } from 'lucide-vue-next';

const props = defineProps<{
    recommendation?: {
        id: string;
        disease_id: string;
        doctor_id: string;
        recommendation_text: string;
        priority: number;
        is_active: boolean;
    };
    diseases: Array<{ id: string; name: string }>;
    doctors: Array<{ id: string; name: string }>;
}>();

const isEdit = !!props.recommendation;

const form = useForm({
    disease_id:          props.recommendation?.disease_id ?? '',
    doctor_id:           props.recommendation?.doctor_id ?? '',
    recommendation_text: props.recommendation?.recommendation_text ?? '',
    priority:            props.recommendation?.priority ?? 0,
    is_active:           props.recommendation?.is_active ?? true,
});

function submit() {
    if (isEdit) {
        form.put(`/admin/recommendations/${props.recommendation!.id}`);
    } else {
        form.post('/admin/recommendations');
    }
}
</script>

<template>
    <div class="space-y-5 max-w-2xl">
        <div class="flex items-center gap-3">
            <Link href="/admin/recommendations" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                <ArrowLeft class="size-5 text-gray-500" />
            </Link>
            <div>
                <h1 class="text-xl font-bold text-gray-900 dark:text-white">
                    {{ isEdit ? 'Tavsiyani tahrirlash' : 'Yangi tavsiya' }}
                </h1>
            </div>
        </div>

        <form @submit.prevent="submit" class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kasallik *</label>
                <select v-model="form.disease_id" required
                    class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-sky-500 text-sm">
                    <option value="">Tanlang...</option>
                    <option v-for="d in diseases" :key="d.id" :value="d.id">{{ d.name }}</option>
                </select>
                <p v-if="form.errors.disease_id" class="text-red-500 text-xs mt-1">{{ form.errors.disease_id }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Shifokor *</label>
                <select v-model="form.doctor_id" required
                    class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-sky-500 text-sm">
                    <option value="">Tanlang...</option>
                    <option v-for="d in doctors" :key="d.id" :value="d.id">{{ d.name }}</option>
                </select>
                <p v-if="form.errors.doctor_id" class="text-red-500 text-xs mt-1">{{ form.errors.doctor_id }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tavsiya matni *</label>
                <textarea v-model="form.recommendation_text" required rows="4"
                    class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-sky-500 text-sm"
                    placeholder="Tavsiya matnini kiriting..." />
                <p v-if="form.errors.recommendation_text" class="text-red-500 text-xs mt-1">{{ form.errors.recommendation_text }}</p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ustunlik (priority)</label>
                    <input v-model.number="form.priority" type="number" min="0"
                        class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-sky-500 text-sm" />
                </div>
                <div class="flex items-end pb-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input v-model="form.is_active" type="checkbox"
                            class="rounded border-gray-300 dark:border-gray-600 text-sky-500 focus:ring-sky-500" />
                        <span class="text-sm text-gray-700 dark:text-gray-300">Faol</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" :disabled="form.processing"
                    class="flex items-center gap-2 px-5 py-2.5 rounded-xl bg-sky-500 hover:bg-sky-600 text-white text-sm font-medium disabled:opacity-50 transition">
                    <Save class="size-4" />
                    {{ isEdit ? 'Saqlash' : 'Yaratish' }}
                </button>
                <Link href="/admin/recommendations" class="px-4 py-2.5 rounded-xl text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    Bekor qilish
                </Link>
            </div>
        </form>
    </div>
</template>
