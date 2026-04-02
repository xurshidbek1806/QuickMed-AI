<script setup lang="ts">
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import {
    LayoutDashboard, Building2, Stethoscope, Users, Image, ChevronDown,
    LogOut, Settings, Menu, X, HeartPulse, ChevronRight, ExternalLink
} from 'lucide-vue-next';

const page = usePage();
const user = computed(() => page.props.auth?.user as any);
const sidebarOpen = ref(false);

const nav = [
    { label: 'Dashboard', href: '/admin', icon: LayoutDashboard },
    { label: 'Shifoxonalar', href: '/admin/clinics', icon: Building2 },
    { label: 'Kasalliklar', href: '/admin/diseases', icon: Stethoscope },
    { label: 'Shifokorlar', href: '/admin/doctors', icon: Users },
    { label: 'Bannerlar', href: '/admin/banners', icon: Image },
    { label: 'Foydalanuvchilar', href: '/admin/users', icon: Users },
    { label: 'Tavsiyalar', href: '/admin/recommendations', icon: HeartPulse },
];

function isActive(href: string) {
    return page.url.startsWith(href) && (href !== '/admin' || page.url === '/admin');
}
</script>

<template>
    <div class="min-h-screen bg-[#fafbfc] dark:bg-gray-950 flex">
        <!-- Sidebar -->
        <aside
            :class="[
                'fixed inset-y-0 left-0 z-50 w-[260px] flex flex-col transition-transform duration-300 ease-in-out',
                'bg-white dark:bg-gray-900 border-r border-gray-200/80 dark:border-gray-800',
                sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'
            ]"
        >
            <!-- Logo -->
            <div class="flex items-center gap-2.5 px-5 h-16 border-b border-gray-100 dark:border-gray-800">
                <div class="size-9 rounded-xl bg-gradient-to-br from-blue-600 to-cyan-500 flex items-center justify-center shadow-md shadow-blue-500/20">
                    <HeartPulse class="size-5 text-white" />
                </div>
                <div>
                    <p class="font-bold text-gray-900 dark:text-white text-sm leading-tight">QuickMed<span class="text-blue-600">AI</span></p>
                    <p class="text-[10px] font-medium text-gray-400 dark:text-gray-500 tracking-wide uppercase">Super Admin</p>
                </div>
            </div>

            <!-- Nav -->
            <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-0.5">
                <p class="px-3 mb-2 text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Asosiy</p>
                <Link
                    v-for="item in nav"
                    :key="item.href"
                    :href="item.href"
                    :class="[
                        'flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-medium transition-all duration-200',
                        isActive(item.href)
                            ? 'bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-400 shadow-sm'
                            : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white'
                    ]"
                >
                    <component :is="item.icon" :class="['size-[18px] shrink-0', isActive(item.href) ? 'text-blue-600' : '']" />
                    {{ item.label }}
                    <ChevronRight v-if="isActive(item.href)" class="size-3.5 ml-auto text-blue-400" />
                </Link>
            </nav>

            <!-- User -->
            <div class="px-3 py-4 border-t border-gray-100 dark:border-gray-800">
                <div class="flex items-center gap-3 px-3 py-2">
                    <div class="size-9 rounded-xl bg-gradient-to-br from-blue-100 to-cyan-100 dark:from-blue-900 dark:to-cyan-900 flex items-center justify-center text-blue-700 dark:text-blue-300 font-bold text-sm">
                        {{ user?.name?.[0]?.toUpperCase() }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ user?.name }}</p>
                        <p class="text-[11px] text-gray-400 dark:text-gray-500 truncate">{{ user?.email }}</p>
                    </div>
                </div>
                <div class="mt-2 flex gap-1">
                    <Link href="/settings/profile" class="flex-1 flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg text-xs font-medium text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-700 transition">
                        <Settings class="size-3.5" /> Sozlamalar
                    </Link>
                    <Link href="/logout" method="post" as="button" class="flex-1 flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg text-xs font-medium text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition">
                        <LogOut class="size-3.5" /> Chiqish
                    </Link>
                </div>
            </div>
        </aside>

        <!-- Overlay (mobile) -->
        <Transition enter-active-class="transition-opacity duration-300" leave-active-class="transition-opacity duration-300" enter-from-class="opacity-0" leave-to-class="opacity-0">
            <div v-if="sidebarOpen" class="fixed inset-0 z-40 bg-black/40 backdrop-blur-sm lg:hidden" @click="sidebarOpen = false" />
        </Transition>

        <!-- Main content -->
        <div class="flex-1 lg:ml-[260px] flex flex-col min-h-screen">
            <!-- Top bar -->
            <header class="sticky top-0 z-30 h-16 border-b border-gray-200/80 dark:border-gray-800 bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl px-4 sm:px-6 flex items-center gap-4">
                <button class="lg:hidden p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 transition" @click="sidebarOpen = !sidebarOpen">
                    <Menu v-if="!sidebarOpen" class="size-5 text-gray-500" />
                    <X v-else class="size-5 text-gray-500" />
                </button>
                <div class="flex-1" />
                <Link href="/" class="flex items-center gap-1.5 text-xs font-medium text-gray-400 hover:text-blue-600 transition">
                    <ExternalLink class="size-3" /> Saytga qaytish
                </Link>
            </header>

            <!-- Page -->
            <main class="flex-1 p-5 sm:p-8">
                <slot />
            </main>
        </div>
    </div>
</template>
