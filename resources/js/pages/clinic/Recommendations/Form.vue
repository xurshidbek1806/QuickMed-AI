<script setup lang="ts">
import { useForm, Link } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';

const props = defineProps<{
    recommendation?: any;
    diseases: Array<{ id: string; name: string }>;
    doctors:  Array<{ id: string; name: string; specialization: string }>;
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
        form.put(`/clinic/recommendations/${props.recommendation.id}`);
    } else {
        form.post('/clinic/recommendations');
    }
}
</script>

<template>
    <div class="max-w-2xl space-y-6">
        <div class="flex items-center gap-3">
            <Link href="/clinic/recommendations" class="p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                <ArrowLeft class="size-5 text-gray-600 dark:text-gray-400" />
            </Link>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">
                {{ isEdit ? 'Tavsiyani tahrirlash' : 'Yangi tavsiya' }}
            </h1>
        </div>

        <form @submit.prevent="submit" class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kasallik *</label>
                <select v-model="form.disease_id" required class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400">
                    <option value="">Tanlang</option>
                    <option v-for="d in diseases" :key="d.id" :value="d.id">{{ d.name }}</option>
                </select>
                <p v-if="form.errors.disease_id" class="text-red-500 text-xs mt-1">{{ form.errors.disease_id }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Shifokor (ixtiyoriy)</label>
                <select v-model="form.doctor_id" class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400">
                    <option value="">Shifokor belgilanmagan</option>
                    <option v-for="d in doctors" :key="d.id" :value="d.id">{{ d.name }} — {{ d.specialization }}</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tavsiya matni *</label>
                <textarea
                    v-model="form.recommendation_text"
                    required
                    rows="6"
                    placeholder="AI foydalanuvchilarga ko'rsatiladigan tavsiya matnini yozing..."
                    class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-sm resize-none focus:outline-none focus:ring-2 focus:ring-emerald-400"
                />
                <p v-if="form.errors.recommendation_text" class="text-red-500 text-xs mt-1">{{ form.errors.recommendation_text }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ustuvorlik (0–100, katta = birinchi)</label>
                <input v-model.number="form.priority" type="number" min="0" max="100" class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400" />
            </div>

            <div class="flex items-center gap-2">
                <input v-model="form.is_active" type="checkbox" id="is_active" class="size-4 rounded" />
                <label for="is_active" class="text-sm text-gray-700 dark:text-gray-300">Faol holat</label>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" :disabled="form.processing" class="px-6 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-600 disabled:opacity-50 text-white font-medium text-sm transition">
                    {{ form.processing ? 'Saqlanmoqda...' : isEdit ? 'Saqlash' : 'Qo\'shish' }}
                </button>
                <Link href="/clinic/recommendations" class="px-6 py-2.5 rounded-xl bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium text-sm transition">
                    Bekor qilish
                </Link>
            </div>
        </form>
    </div>
</template>
