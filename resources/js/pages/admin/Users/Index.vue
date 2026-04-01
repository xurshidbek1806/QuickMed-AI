<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { Plus, Pencil, Trash2, UserCircle, ShieldCheck, Building2 } from 'lucide-vue-next';

const props = defineProps<{
    users: {
        data: Array<{
            id: string; name: string; email: string; role: string;
            clinic?: { id: string; name: string } | null;
            created_at: string;
        }>;
        current_page: number; last_page: number;
    }
}>();

function destroy(id: string, name: string) {
    if (confirm(`"${name}" foydalanuvchisini o'chirishni tasdiqlaysizmi?`)) {
        router.delete(`/admin/users/${id}`);
    }
}

function roleLabel(role: string) {
    return role === 'super_admin' ? 'Super Admin' : 'Shifoxona Admin';
}

function roleBadge(role: string) {
    return role === 'super_admin'
        ? 'bg-violet-100 text-violet-700 dark:bg-violet-900/30 dark:text-violet-400'
        : 'bg-sky-100 text-sky-700 dark:bg-sky-900/30 dark:text-sky-400';
}
</script>

<template>
    <div class="space-y-5">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-gray-900 dark:text-white">Foydalanuvchilar</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Admin foydalanuvchilarni boshqarish</p>
            </div>
            <Link href="/admin/users/create" class="flex items-center gap-2 px-4 py-2 rounded-xl bg-sky-500 hover:bg-sky-600 text-white text-sm font-medium transition">
                <Plus class="size-4" /> Yangi foydalanuvchi
            </Link>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-700">
                    <tr>
                        <th class="text-left px-4 py-3 font-medium text-gray-600 dark:text-gray-400">#</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-600 dark:text-gray-400">Ism</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-600 dark:text-gray-400">Email</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-600 dark:text-gray-400">Rol</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-600 dark:text-gray-400">Shifoxona</th>
                        <th class="px-4 py-3" />
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    <tr v-if="!users.data.length">
                        <td colspan="6" class="px-4 py-8 text-center text-gray-400">Foydalanuvchilar topilmadi</td>
                    </tr>
                    <tr v-for="(u, i) in users.data" :key="u.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                        <td class="px-4 py-3 text-gray-400">{{ i + 1 }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <div class="size-8 rounded-lg bg-violet-100 dark:bg-violet-900/30 flex items-center justify-center">
                                    <UserCircle class="size-4 text-violet-500" />
                                </div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ u.name }}</p>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ u.email }}</td>
                        <td class="px-4 py-3">
                            <span :class="['px-2 py-0.5 rounded-full text-xs font-medium', roleBadge(u.role)]">
                                {{ roleLabel(u.role) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                            <div v-if="u.clinic" class="flex items-center gap-1">
                                <Building2 class="size-3.5 text-sky-500" />
                                {{ u.clinic.name }}
                            </div>
                            <span v-else class="text-gray-400">—</span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-1 justify-end">
                                <Link :href="`/admin/users/${u.id}/edit`" class="p-1.5 rounded-lg hover:bg-sky-50 dark:hover:bg-sky-900/20 text-sky-500 transition">
                                    <Pencil class="size-4" />
                                </Link>
                                <button @click="destroy(u.id, u.name)" class="p-1.5 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 text-red-500 transition">
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
