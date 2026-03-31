<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { home } from '@/routes';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div
        class="relative flex min-h-svh flex-col items-center justify-center gap-6 bg-gradient-to-br from-slate-50 to-sky-50 p-6 dark:from-slate-950 dark:to-sky-950 md:p-10"
    >
        <!-- Decorative blurred bg elements -->
        <div class="pointer-events-none absolute inset-0 overflow-hidden">
            <div class="absolute -top-24 -left-24 size-72 rounded-full bg-sky-400/10 blur-3xl dark:bg-sky-500/5" />
            <div class="absolute -bottom-24 -right-24 size-72 rounded-full bg-violet-400/10 blur-3xl dark:bg-violet-500/5" />
        </div>

        <div class="relative w-full max-w-sm">
            <div class="flex flex-col gap-8">
                <div class="flex flex-col items-center gap-4">
                    <Link
                        :href="home()"
                        class="flex flex-col items-center gap-2 font-medium"
                    >
                        <div
                            class="mb-1 flex h-10 w-10 items-center justify-center rounded-xl bg-sky-500 shadow-lg shadow-sky-500/20"
                        >
                            <AppLogoIcon
                                class="size-6 text-white"
                            />
                        </div>
                        <span class="text-sm font-bold text-gray-900 dark:text-white">QuickMedAI</span>
                    </Link>
                    <div class="space-y-2 text-center">
                        <h1 class="text-xl font-medium">{{ title }}</h1>
                        <p class="text-center text-sm text-muted-foreground">
                            {{ description }}
                        </p>
                    </div>
                </div>
                <slot />
            </div>
        </div>
    </div>
</template>
