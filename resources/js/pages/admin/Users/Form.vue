<script setup lang="ts">
import { useForm, Link } from '@inertiajs/vue3';
import { ArrowLeft, Save } from 'lucide-vue-next';

const props = defineProps<{
    user?: {
        id: string; name: string; email: string; role: string;
        clinic_id: string | null; clinic?: { id: string; name: string } | null;
    };
    clinics: Array<{ id: string; name: string }>;
}>();

const isEdit = !!props.user;

const form = useForm({
    name:      props.user?.name ?? '',
    email:     props.user?.email ?? '',
    password:  '',
    role:      props.user?.role ?? 'clinic_admin',
    clinic_id: props.user?.clinic_id ?? '',
});

function submit() {
    if (isEdit) {
        form.put(`/admin/users/${props.user!.id}`);
    } else {
        form.post('/admin/users');
    }
}
</script>

<template>
    <div class="space-y-5 max-w-2xl">
        <div class="flex items-center gap-3">
            <Link href="/admin/users" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                <ArrowLeft class="size-5 text-gray-500" />
            </Link>
            <div>
                <h1 class="text-xl font-bold text-gray-900 dark:text-white">
                    {{ isEdit ? 'Foydalanuvchini tahrirlash' : 'Yangi foydalanuvchi' }}
                </h1>
            </div>
        </div>

        <form @submit.prevent="submit" class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ism *</label>
                <input v-model="form.name" type="text" required
                    class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-sky-500 text-sm" />
                <p v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email *</label>
                <input v-model="form.email" type="email" required
                    class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-sky-500 text-sm" />
                <p v-if="form.errors.email" class="text-red-500 text-xs mt-1">{{ form.errors.email }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Parol {{ isEdit ? '(bo\'sh qoldiring — o\'zgarmaydi)' : '*' }}
                </label>
                <input v-model="form.password" type="password" :required="!isEdit" minlength="8"
                    class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-sky-500 text-sm" />
                <p v-if="form.errors.password" class="text-red-500 text-xs mt-1">{{ form.errors.password }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Rol *</label>
                <select v-model="form.role" required
                    class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-sky-500 text-sm">
                    <option value="super_admin">Super Admin</option>
                    <option value="clinic_admin">Shifoxona Admin</option>
                </select>
            </div>

            <div v-if="form.role === 'clinic_admin'">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Shifoxona</label>
                <select v-model="form.clinic_id"
                    class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-sky-500 text-sm">
                    <option value="">Tanlang...</option>
                    <option v-for="c in clinics" :key="c.id" :value="c.id">{{ c.name }}</option>
                </select>
                <p v-if="form.errors.clinic_id" class="text-red-500 text-xs mt-1">{{ form.errors.clinic_id }}</p>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" :disabled="form.processing"
                    class="flex items-center gap-2 px-5 py-2.5 rounded-xl bg-sky-500 hover:bg-sky-600 text-white text-sm font-medium disabled:opacity-50 transition">
                    <Save class="size-4" />
                    {{ isEdit ? 'Saqlash' : 'Yaratish' }}
                </button>
                <Link href="/admin/users" class="px-4 py-2.5 rounded-xl text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    Bekor qilish
                </Link>
            </div>
        </form>
    </div>
</template>
