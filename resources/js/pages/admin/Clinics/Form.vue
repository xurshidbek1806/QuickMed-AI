<script setup lang="ts">
import { useForm, Link } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';

const props = defineProps<{
    clinic?: any;
    clinics?: Array<{ id: number; name: string }>;
}>();

const isEdit = !!props.clinic;

const form = useForm({
    name:           props.clinic?.name ?? '',
    address:        props.clinic?.address ?? '',
    phone:          props.clinic?.phone ?? '',
    email:          props.clinic?.email ?? '',
    website:        props.clinic?.website ?? '',
    description:    props.clinic?.description ?? '',
    is_active:      props.clinic?.is_active ?? true,
    // New clinic admin account
    admin_name:     '',
    admin_email:    '',
    admin_password: '',
});

function submit() {
    if (isEdit) {
        form.put(`/admin/clinics/${props.clinic.id}`);
    } else {
        form.post('/admin/clinics');
    }
}
</script>

<template>
    <div class="max-w-2xl space-y-6">
        <div class="flex items-center gap-3">
            <Link href="/admin/clinics" class="p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                <ArrowLeft class="size-5 text-gray-600 dark:text-gray-400" />
            </Link>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">
                {{ isEdit ? 'Shifoxonani tahrirlash' : 'Yangi shifoxona' }}
            </h1>
        </div>

        <form @submit.prevent="submit" class="space-y-5">
            <!-- Clinic info -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5 space-y-4">
                <h2 class="font-semibold text-gray-800 dark:text-white">Shifoxona ma'lumotlari</h2>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nomi *</label>
                    <input v-model="form.name" type="text" required class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-sky-400" />
                    <p v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Telefon</label>
                        <input v-model="form.phone" type="text" class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-sky-400" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                        <input v-model="form.email" type="email" class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-sky-400" />
                        <p v-if="form.errors.email" class="text-red-500 text-xs mt-1">{{ form.errors.email }}</p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Manzil</label>
                    <input v-model="form.address" type="text" class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-sky-400" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tavsif</label>
                    <textarea v-model="form.description" rows="3" class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-sm resize-none focus:outline-none focus:ring-2 focus:ring-sky-400" />
                </div>

                <div class="flex items-center gap-2">
                    <input v-model="form.is_active" type="checkbox" id="is_active" class="size-4 rounded" />
                    <label for="is_active" class="text-sm text-gray-700 dark:text-gray-300">Faol holat</label>
                </div>
            </div>

            <!-- Admin account (only for new clinic) -->
            <div v-if="!isEdit" class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5 space-y-4">
                <h2 class="font-semibold text-gray-800 dark:text-white">Admin akkaunt</h2>
                <p class="text-xs text-gray-500">Shifoxona uchun admin hisobi avtomatik yaratiladi</p>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">To'liq ism *</label>
                    <input v-model="form.admin_name" type="text" required class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-sky-400" />
                    <p v-if="form.errors.admin_name" class="text-red-500 text-xs mt-1">{{ form.errors.admin_name }}</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email *</label>
                        <input v-model="form.admin_email" type="email" required class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-sky-400" />
                        <p v-if="form.errors.admin_email" class="text-red-500 text-xs mt-1">{{ form.errors.admin_email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Parol *</label>
                        <input v-model="form.admin_password" type="password" required minlength="8" class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-sky-400" />
                        <p v-if="form.errors.admin_password" class="text-red-500 text-xs mt-1">{{ form.errors.admin_password }}</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="px-6 py-2.5 rounded-xl bg-sky-500 hover:bg-sky-600 disabled:opacity-50 text-white font-medium text-sm transition"
                >
                    {{ form.processing ? 'Saqlanmoqda...' : isEdit ? 'Saqlash' : 'Yaratish' }}
                </button>
                <Link href="/admin/clinics" class="px-6 py-2.5 rounded-xl bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium text-sm transition">
                    Bekor qilish
                </Link>
            </div>
        </form>
    </div>
</template>
