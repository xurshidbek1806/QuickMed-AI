<script setup lang="ts">
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import {
    LayoutDashboard, Stethoscope, Users, Image, ClipboardList, Menu, X,
    LogOut, Settings, HeartPulse
} from 'lucide-vue-next';

const page = usePage();
const user = computed(() => page.props.auth?.user as any);
const sidebarOpen = ref(false);

const nav = [
    { label: 'Dashboard', href: '/clinic', icon: LayoutDashboard },
    { label: 'Kasalliklar', href: '/clinic/diseases', icon: Stethoscope },
    { label: 'Shifokorlar', href: '/clinic/doctors', icon: Users },
    { label: 'Tavsiyalar', href: '/clinic/recommendations', icon: ClipboardList },
    { label: 'Bannerlar', href: '/clinic/banners', icon: Image },
];

function isActive(href: string) {
    return page.url.startsWith(href) && (href !== '/clinic' || page.url === '/clinic');
}
</script>

<template>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 flex">
        <!-- Sidebar -->
        <aside
            :class="[
                'fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 flex flex-col transition-transform',
                sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'
            ]"
        >
            <div class="flex items-center gap-3 px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                <div class="size-9 rounded-lg bg-emerald-500 flex items-center justify-center">
                    <HeartPulse class="size-5 text-white" />
                </div>
                <div>
                    <p class="font-bold text-gray-900 dark:text-white text-sm leading-tight">QuickMedAI</p>
                    <p class="text-[11px] text-emerald-600 dark:text-emerald-400 truncate max-w-[140px]">
                        {{ (user as any)?.clinic?.name ?? 'Shifoxona' }}
                    </p>
                </div>
            </div>

            <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
                <Link
                    v-for="item in nav"
                    :key="item.href"
                    :href="item.href"
                    :class="[
                        'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors',
                        isActive(item.href)
                            ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400'
                            : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white'
                    ]"
                >
                    <component :is="item.icon" class="size-4 shrink-0" />
                    {{ item.label }}
                </Link>
            </nav>

            <div class="px-3 py-4 border-t border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-3 px-3 py-2 rounded-lg">
                    <div class="size-8 rounded-full bg-emerald-100 dark:bg-emerald-900 flex items-center justify-center text-emerald-600 dark:text-emerald-400 font-semibold text-sm">
                        {{ user?.name?.[0]?.toUpperCase() }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ user?.name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ user?.email }}</p>
                    </div>
                </div>
                <div class="mt-1 space-y-0.5">
                    <Link href="/settings/profile" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700/50">
                        <Settings class="size-4" /> Sozlamalar
                    </Link>
                    <Link href="/logout" method="post" as="button" class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20">
                        <LogOut class="size-4" /> Chiqish
                    </Link>
                </div>
            </div>
        </aside>

        <div v-if="sidebarOpen" class="fixed inset-0 z-40 bg-black/50 lg:hidden" @click="sidebarOpen = false" />

        <div class="flex-1 lg:ml-64 flex flex-col">
            <header class="sticky top-0 z-30 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-4 sm:px-6 py-3 flex items-center gap-4">
                <button class="lg:hidden p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700" @click="sidebarOpen = !sidebarOpen">
                    <Menu v-if="!sidebarOpen" class="size-5" />
                    <X v-else class="size-5" />
                </button>
                <div class="flex-1" />
                <Link href="/" class="text-sm text-emerald-600 hover:underline">← Saytga qaytish</Link>
            </header>

            <main class="flex-1 p-4 sm:p-6">
                <slot />
            </main>
        </div>
    </div>
</template>
