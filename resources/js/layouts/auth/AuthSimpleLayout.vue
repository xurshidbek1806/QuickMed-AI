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
    <div class="relative flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10 bg-[#fafbfc]">
        <!-- Subtle grid bg -->
        <div class="pointer-events-none absolute inset-0 bg-[linear-gradient(to_right,#f0f0f0_1px,transparent_1px),linear-gradient(to_bottom,#f0f0f0_1px,transparent_1px)] bg-[size:64px_64px] opacity-40" />
        <!-- Radial glow -->
        <div class="pointer-events-none absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[400px] bg-[radial-gradient(ellipse_at_center,rgba(59,130,246,0.08),transparent_70%)] rounded-full" />

        <div class="relative w-full max-w-[400px]">
            <div class="rounded-2xl border border-gray-200/80 bg-white p-8 shadow-xl shadow-gray-200/40">
                <div class="flex flex-col items-center gap-5 mb-8">
                    <Link :href="home()" class="flex flex-col items-center gap-2.5">
                        <div class="size-11 rounded-xl bg-gradient-to-br from-blue-600 to-cyan-500 flex items-center justify-center shadow-lg shadow-blue-500/20">
                            <AppLogoIcon class="size-6 text-white" />
                        </div>
                        <span class="text-sm font-bold text-gray-900">QuickMed<span class="text-blue-600">AI</span></span>
                    </Link>
                    <div class="space-y-1.5 text-center">
                        <h1 class="text-xl font-bold text-gray-900">{{ title }}</h1>
                        <p class="text-sm text-gray-400">{{ description }}</p>
                    </div>
                </div>
                <slot />
            </div>
            <p class="mt-6 text-center text-xs text-gray-400">
                &copy; {{ new Date().getFullYear() }} QuickMedAI. Barcha huquqlar himoyalangan.
            </p>
        </div>
    </div>
</template>
