<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import { logout } from '@/routes';
import { send } from '@/routes/verification';

defineOptions({
    layout: {
        title: 'Emailni tasdiqlash',
        description:
            'Ro\'yxatdan o\'tganingizda ko\'rsatilgan email manziliga tasdiqlash havolasi yuborildi.',
    },
});

defineProps<{
    status?: string;
}>();
</script>

<template>
    <Head title="Emailni tasdiqlash" />

    <div
        v-if="status === 'verification-link-sent'"
        class="mb-4 text-center text-sm font-medium text-green-600"
    >
        Ro'yxatdan o'tganingizda ko'rsatilgan email manziliga yangi tasdiqlash
        havolasi yuborildi.
    </div>

    <Form
        v-bind="send.form()"
        class="space-y-6 text-center"
        v-slot="{ processing }"
    >
        <Button :disabled="processing" variant="secondary">
            <Spinner v-if="processing" />
            Tasdiqlash emailini qayta yuborish
        </Button>

        <TextLink :href="logout()" as="button" class="mx-auto block text-sm">
            Chiqish
        </TextLink>
    </Form>
</template>
