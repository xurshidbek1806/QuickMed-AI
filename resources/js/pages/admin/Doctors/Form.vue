<script setup lang="ts">
import { useForm, Link } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps<{
    doctor?: any;
    clinics?: Array<{ id: number; name: string }>;
    baseUrl?: string;
}>();

const isEdit = !!props.doctor;
const base   = props.baseUrl ?? '/admin/doctors';
const photoPreview = ref<string | null>(props.doctor?.photo ?? null);

const form = useForm({
    clinic_id:      props.doctor?.clinic_id ?? '',
    name:           props.doctor?.name ?? '',
    specialization: props.doctor?.specialization ?? '',
    phone_number:   props.doctor?.phone_number ?? '',
    location_url:   props.doctor?.location_url ?? '',
    photo:          null as File | null,
    is_active:      props.doctor?.is_active ?? true,
});

function onPhoto(e: Event) {
    const file = (e.target as HTMLInputElement).files?.[0];
    if (!file) return;
    form.photo = file;
    photoPreview.value = URL.createObjectURL(file);
}

function submit() {
    const opts = { forceFormData: true };
    if (isEdit) {
        form.post(`${base}/${props.doctor.id}?_method=PUT`, opts);
    } else {
        form.post(base, opts);
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
                {{ isEdit ? 'Shifokorni tahrirlash' : 'Yangi shifokor' }}
            </h1>
        </div>

        <form @submit.prevent="submit" class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5 space-y-4">
            <!-- Photo -->
            <div class="flex items-center gap-4">
                <div class="size-20 rounded-2xl overflow-hidden bg-gray-100 dark:bg-gray-700 flex items-center justify-center shrink-0">
                    <img v-if="photoPreview" :src="photoPreview" class="size-full object-cover" />
                    <span v-else class="text-gray-400 text-3xl font-bold">{{ form.name?.[0] ?? '?' }}</span>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Rasm (ixtiyoriy)</p>
                    <input type="file" accept="image/*" @change="onPhoto" class="text-sm text-gray-500 file:mr-3 file:px-3 file:py-1.5 file:rounded-lg file:border-0 file:bg-sky-50 file:text-sky-600 hover:file:bg-sky-100" />
                </div>
            </div>

            <div v-if="clinics?.length">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Shifoxona</label>
                <select v-model="form.clinic_id" class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-sky-400">
                    <option value="">Tanlang</option>
                    <option v-for="c in clinics" :key="c.id" :value="c.id">{{ c.name }}</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">To'liq ism *</label>
                <input v-model="form.name" type="text" required class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-sky-400" />
                <p v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mutaxassislik</label>
                <input v-model="form.specialization" type="text" placeholder="Masalan: Kardiolog, Nevropatolog..." class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-sky-400" />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Telefon</label>
                    <input v-model="form.phone_number" type="tel" placeholder="+998 XX XXX XX XX" class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-sky-400" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Manzil havolasi</label>
                    <input v-model="form.location_url" type="url" placeholder="Google Maps URL" class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-sky-400" />
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
