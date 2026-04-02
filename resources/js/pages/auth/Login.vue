<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { register } from '@/routes';
import { store } from '@/routes/login';
import { request } from '@/routes/password';
import { Mail, Lock, LogIn, UserPlus } from 'lucide-vue-next';

defineOptions({
    layout: {
        title: 'Hisobingizga kiring',
        description: 'Email va parolingizni kiritib tizimga kiring',
    },
});

defineProps<{
    status?: string;
    canResetPassword: boolean;
    canRegister: boolean;
}>();
</script>

<template>
    <Head title="Kirish" />

    <div
        v-if="status"
        class="mb-4 rounded-xl bg-emerald-50 border border-emerald-200/60 px-4 py-3 text-center text-sm font-medium text-emerald-700"
    >
        {{ status }}
    </div>

    <Form
        v-bind="store.form()"
        :reset-on-success="['password']"
        v-slot="{ errors, processing }"
        class="flex flex-col gap-5"
    >
        <div class="grid gap-5">
            <div class="grid gap-1.5">
                <Label for="email" class="text-gray-600 text-sm font-medium">Email manzil</Label>
                <div class="relative">
                    <Mail class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 size-4 text-gray-400" />
                    <Input
                        id="email"
                        type="email"
                        name="email"
                        required
                        autofocus
                        :tabindex="1"
                        autocomplete="email"
                        placeholder="email@example.com"
                        class="pl-10 h-11 rounded-xl border-gray-200 bg-gray-50/50 text-gray-800 placeholder:text-gray-400 focus-visible:bg-white focus-visible:border-blue-400 focus-visible:ring-blue-100 focus-visible:ring-[3px] transition-all"
                    />
                </div>
                <InputError :message="errors.email" />
            </div>

            <div class="grid gap-1.5">
                <div class="flex items-center justify-between">
                    <Label for="password" class="text-gray-600 text-sm font-medium">Parol</Label>
                    <TextLink
                        v-if="canResetPassword"
                        :href="request()"
                        class="text-xs text-blue-500 hover:text-blue-600 transition-colors"
                        :tabindex="5"
                    >
                        Parolni unutdingizmi?
                    </TextLink>
                </div>
                <PasswordInput
                    id="password"
                    name="password"
                    required
                    :tabindex="2"
                    autocomplete="current-password"
                    placeholder="Parolingizni kiriting"
                    class="h-11 rounded-xl border-gray-200 bg-gray-50/50 text-gray-800 placeholder:text-gray-400 focus-visible:bg-white focus-visible:border-blue-400 focus-visible:ring-blue-100 focus-visible:ring-[3px] transition-all"
                />
                <InputError :message="errors.password" />
            </div>

            <div class="flex items-center">
                <Label for="remember" class="flex items-center space-x-2.5 cursor-pointer">
                    <Checkbox id="remember" name="remember" :tabindex="3" />
                    <span class="text-sm text-gray-500">Meni eslab qol</span>
                </Label>
            </div>

            <Button
                type="submit"
                class="mt-2 w-full h-11 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow-lg shadow-blue-600/20 hover:shadow-blue-600/30 transition-all hover:scale-[1.01] active:scale-[0.99]"
                :tabindex="4"
                :disabled="processing"
                data-test="login-button"
            >
                <Spinner v-if="processing" />
                <LogIn v-else class="size-4 mr-1.5" />
                Kirish
            </Button>
        </div>

        <div
            class="text-center text-sm text-gray-400 pt-1"
            v-if="canRegister"
        >
            Hisobingiz yo'qmi?
            <TextLink :href="register()" :tabindex="5" class="text-blue-500 hover:text-blue-600 font-medium transition-colors">
                Ro'yxatdan o'tish
            </TextLink>
        </div>
    </Form>
</template>
