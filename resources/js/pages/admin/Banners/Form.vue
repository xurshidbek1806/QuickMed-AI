<script setup lang="ts">
import { useForm, Link } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps<{
    banner?: any;
    clinics?: Array<{ id: number; name: string }>;
    baseUrl?: string;
}>();

const isEdit = !!props.banner;
const base   = props.baseUrl ?? '/admin/banners';
const mediaPreview = ref<string | null>(props.banner?.media_url ?? null);

const form = useForm({
    clinic_id:   props.banner?.clinic_id ?? '',
    title:       props.banner?.title ?? '',
    description: props.banner?.description ?? '',
    media:       null as File | null,
    link:        props.banner?.link ?? '',
    media_type:  props.banner?.media_type ?? 'image',
    position:    props.banner?.position ?? 'sidebar',
    sort_order:  props.banner?.sort_order ?? 0,
    is_active:   props.banner?.is_active ?? true,
    starts_at:   props.banner?.starts_at ?? '',
    ends_at:     props.banner?.ends_at ?? '',
});

function onMedia(e: Event) {
    const file = (e.target as HTMLInputElement).files?.[0];
    if (!file) return;
    form.media = file;
    mediaPreview.value = URL.createObjectURL(file);
    if (file.type.startsWith('video/')) form.media_type = 'video';
    else form.media_type = 'image';
}

function submit() {
    const opts = { forceFormData: true };
    if (isEdit) {
        form.post(`${base}/${props.banner.id}?_method=PUT`, opts);
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
                {{ isEdit ? 'Bannerni tahrirlash' : 'Yangi banner' }}
            </h1>
        </div>

        <form @submit.prevent="submit" class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5 space-y-4">
            <!-- Media preview -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Media fayl</label>
                <div v-if="mediaPreview" class="mb-2 rounded-xl overflow-hidden aspect-video bg-gray-100 dark:bg-gray-700">
                    <video v-if="form.media_type === 'video'" :src="mediaPreview" controls class="size-full object-cover" />
                    <img v-else :src="mediaPreview" class="size-full object-cover" />
                </div>
                <input type="file" accept="image/*,video/*" @change="onMedia" class="text-sm text-gray-500 file:mr-3 file:px-3 file:py-1.5 file:rounded-lg file:border-0 file:bg-sky-50 file:text-sky-600 hover:file:bg-sky-100" />
            </div>

            <div v-if="clinics?.length">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Shifoxona</label>
                <select v-model="form.clinic_id" class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-sky-400">
                    <option value="">Umumiy</option>
                    <option v-for="c in clinics" :key="c.id" :value="c.id">{{ c.name }}</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sarlavha *</label>
                <input v-model="form.title" type="text" required class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-sky-400" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tavsif</label>
                <textarea v-model="form.description" rows="2" class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-sm resize-none focus:outline-none focus:ring-2 focus:ring-sky-400" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Havola (ixtiyoriy)</label>
                <input v-model="form.link" type="url" placeholder="https://..." class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-sky-400" />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Joylashuv</label>
                    <select v-model="form.position" class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-sky-400">
                        <option value="sidebar">Sidebar</option>
                        <option value="top">Yuqori</option>
                        <option value="chat">Chat</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tartib raqami</label>
                    <input v-model.number="form.sort_order" type="number" min="0" class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-sky-400" />
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Boshlanish sanasi</label>
                    <input v-model="form.starts_at" type="datetime-local" class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-sky-400" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tugash sanasi</label>
                    <input v-model="form.ends_at" type="datetime-local" class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-sky-400" />
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
