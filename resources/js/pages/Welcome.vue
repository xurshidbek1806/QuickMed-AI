<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    HeartPulse, MessageCircle, Mic, Shield, Stethoscope, Clock,
    ArrowRight, Sparkles, Zap, Hospital, Hand, UserRound, Cake,
    Bug, FileText, Cross
} from 'lucide-vue-next';
</script>

<template>
    <Head title="QuickMedAI — AI tibbiy maslahat yordamchisi">
        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />
    </Head>

    <div class="min-h-screen bg-gradient-to-b from-[#0F172A] to-[#0C4A6E] text-white overflow-hidden font-sans">
        <!-- Animated background blurs -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-sky-500/10 rounded-full blur-3xl animate-pulse" />
            <div class="absolute top-1/3 -left-40 w-80 h-80 bg-emerald-500/10 rounded-full blur-3xl animate-pulse [animation-delay:2s]" />
            <div class="absolute bottom-20 right-1/4 w-72 h-72 bg-violet-500/8 rounded-full blur-3xl animate-pulse [animation-delay:4s]" />
        </div>

        <!-- Header -->
        <header class="relative z-10 flex items-center justify-between px-6 py-4 max-w-7xl mx-auto">
            <div class="flex items-center gap-3">
                <div class="size-11 rounded-2xl bg-gradient-to-br from-sky-500 to-cyan-400 flex items-center justify-center shadow-lg shadow-sky-500/30">
                    <HeartPulse class="size-6 text-white" />
                </div>
                <div>
                    <span class="font-extrabold text-xl tracking-tight">QuickMedAI</span>
                    <p class="text-[11px] text-sky-300/70 -mt-0.5">AI tibbiy yordamchi</p>
                </div>
            </div>
            <nav>
                <Link
                    v-if="$page.props.auth.user"
                    href="/dashboard"
                    class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-sky-500 to-cyan-400 hover:from-sky-400 hover:to-cyan-300 text-white text-sm font-semibold transition-all shadow-lg shadow-sky-500/30 hover:scale-[1.02]"
                >
                    Dashboard
                </Link>
                <Link
                    v-else
                    href="/login"
                    class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-sky-500 to-cyan-400 hover:from-sky-400 hover:to-cyan-300 text-white text-sm font-semibold transition-all shadow-lg shadow-sky-500/30 hover:scale-[1.02]"
                >
                    Kirish
                </Link>
            </nav>
        </header>

        <!-- Hero — mobile home_screen CTA card style -->
        <section class="relative z-10 px-6 pt-12 pb-16 max-w-7xl mx-auto">
            <div class="max-w-2xl mx-auto text-center">
                <p class="text-slate-400 text-base mb-2 inline-flex items-center gap-1.5">Assalomu alaykum! <Hand class="size-5 text-amber-400" /></p>

                <div class="mt-6 mx-auto max-w-lg p-8 rounded-3xl bg-[#1E293B]/60 border border-[#334155]/50 shadow-2xl backdrop-blur-xl">
                    <div class="mx-auto size-[72px] rounded-[22px] bg-gradient-to-br from-sky-500 to-cyan-400 flex items-center justify-center shadow-xl shadow-sky-500/35">
                        <MessageCircle class="size-8 text-white" />
                    </div>
                    <h1 class="mt-5 text-2xl sm:text-3xl font-extrabold tracking-tight">AI Tashxis</h1>
                    <p class="mt-3 text-slate-400 text-sm leading-relaxed max-w-sm mx-auto">
                        Simptomlaringizni kiriting va sun'iy intellekt yordamida dastlabki tashxis oling.
                    </p>
                    <div class="mt-7">
                        <Link
                            href="/chat"
                            class="inline-flex items-center gap-3 px-7 py-3.5 rounded-2xl bg-gradient-to-r from-sky-500 to-cyan-400 hover:from-sky-400 hover:to-cyan-300 text-white font-semibold text-sm transition-all shadow-xl shadow-sky-500/30 hover:shadow-sky-400/40 hover:scale-[1.02]"
                        >
                            Boshlash
                            <ArrowRight class="size-4" />
                        </Link>
                    </div>
                </div>
            </div>
        </section>

        <!-- Feature Cards — mobile 2x2 grid -->
        <section class="relative z-10 px-6 pb-16 max-w-7xl mx-auto">
            <div class="max-w-2xl mx-auto">
                <h2 class="text-lg font-bold mb-4">Imkoniyatlar</h2>
                <div class="grid grid-cols-2 gap-3">
                    <div class="p-5 rounded-2xl bg-[#1E293B]/50 border border-[#334155]/25">
                        <div class="size-11 rounded-[13px] bg-gradient-to-br from-cyan-500 to-sky-500 flex items-center justify-center">
                            <Mic class="size-5 text-white" />
                        </div>
                        <p class="mt-3.5 text-base font-bold text-white">Ovozli</p>
                        <p class="mt-1 text-xs text-slate-500">Ovoz orqali kiritish</p>
                    </div>
                    <div class="p-5 rounded-2xl bg-[#1E293B]/50 border border-[#334155]/25">
                        <div class="size-11 rounded-[13px] bg-gradient-to-br from-violet-500 to-purple-400 flex items-center justify-center">
                            <Zap class="size-5 text-white" />
                        </div>
                        <p class="mt-3.5 text-base font-bold text-white">Tezkor</p>
                        <p class="mt-1 text-xs text-slate-500">30 soniyada natija</p>
                    </div>
                    <div class="p-5 rounded-2xl bg-[#1E293B]/50 border border-[#334155]/25">
                        <div class="size-11 rounded-[13px] bg-gradient-to-br from-emerald-500 to-green-400 flex items-center justify-center">
                            <Shield class="size-5 text-white" />
                        </div>
                        <p class="mt-3.5 text-base font-bold text-white">Xavfsiz</p>
                        <p class="mt-1 text-xs text-slate-500">Ma'lumotlar himoyalangan</p>
                    </div>
                    <div class="p-5 rounded-2xl bg-[#1E293B]/50 border border-[#334155]/25">
                        <div class="size-11 rounded-[13px] bg-gradient-to-br from-amber-500 to-yellow-400 flex items-center justify-center">
                            <Hospital class="size-5 text-white" />
                        </div>
                        <p class="mt-3.5 text-base font-bold text-white">Shifokor</p>
                        <p class="mt-1 text-xs text-slate-500">Eng yaqin mutaxassis</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- How it works — 5 steps like mobile -->
        <section class="relative z-10 px-6 pb-16 max-w-7xl mx-auto">
            <div class="max-w-2xl mx-auto">
                <h2 class="text-lg font-bold mb-4">Qanday ishlaydi?</h2>
                <div class="space-y-2.5">
                    <div v-for="(s, i) in [
                        { icon: UserRound, label: 'Jinsni tanlang', color: 'text-sky-400' },
                        { icon: Cake, label: 'Yoshingizni kiriting', color: 'text-amber-400' },
                        { icon: Bug, label: 'Kasallikni tanlang', color: 'text-rose-400' },
                        { icon: FileText, label: 'Simptomlarni yozing', color: 'text-emerald-400' },
                        { icon: Sparkles, label: 'AI natijani oling', color: 'text-violet-400' },
                    ]" :key="i"
                        class="flex items-center gap-3.5 p-4 rounded-[14px] bg-[#1E293B]/50 border border-[#334155]/25"
                    >
                        <div class="size-10 rounded-xl bg-gradient-to-br from-sky-500 to-cyan-400 flex items-center justify-center text-white text-sm font-bold shrink-0">
                            {{ i + 1 }}
                        </div>
                        <component :is="s.icon" :class="['size-5', s.color]" />
                        <p class="text-[15px] font-medium text-white">{{ s.label }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Why QuickMedAI -->
        <section class="relative z-10 px-6 py-16 max-w-7xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-3xl sm:text-4xl font-bold">Nima uchun <span class="text-sky-400">QuickMedAI</span>?</h2>
                <p class="mt-3 text-white/50 max-w-lg mx-auto">Zamonaviy texnologiyalar va tibbiyot tajribasini birlashtirgan platforma</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-5xl mx-auto">
                <div class="group p-6 rounded-2xl bg-white/5 border border-white/10 hover:border-sky-500/30 hover:bg-sky-500/5 transition-all duration-300">
                    <div class="size-12 rounded-xl bg-sky-500/10 flex items-center justify-center mb-4 group-hover:bg-sky-500/20 transition">
                        <Sparkles class="size-6 text-sky-400" />
                    </div>
                    <h3 class="font-semibold text-white text-lg mb-2">AI tahlil</h3>
                    <p class="text-sm text-white/50 leading-relaxed">OpenAI GPT-4o texnologiyasi orqali simptomlaringizni chuqur tahlil qiladi.</p>
                </div>
                <div class="group p-6 rounded-2xl bg-white/5 border border-white/10 hover:border-emerald-500/30 hover:bg-emerald-500/5 transition-all duration-300">
                    <div class="size-12 rounded-xl bg-emerald-500/10 flex items-center justify-center mb-4 group-hover:bg-emerald-500/20 transition">
                        <Mic class="size-6 text-emerald-400" />
                    </div>
                    <h3 class="font-semibold text-white text-lg mb-2">Ovozli kiritish</h3>
                    <p class="text-sm text-white/50 leading-relaxed">Whisper AI o'zbek tilida ovozingizni tanib oladi.</p>
                </div>
                <div class="group p-6 rounded-2xl bg-white/5 border border-white/10 hover:border-violet-500/30 hover:bg-violet-500/5 transition-all duration-300">
                    <div class="size-12 rounded-xl bg-violet-500/10 flex items-center justify-center mb-4 group-hover:bg-violet-500/20 transition">
                        <Stethoscope class="size-6 text-violet-400" />
                    </div>
                    <h3 class="font-semibold text-white text-lg mb-2">Shifokor tavsiyasi</h3>
                    <p class="text-sm text-white/50 leading-relaxed">Kasalligingizga mos eng yaxshi mutaxassislarni tanlaydi.</p>
                </div>
                <div class="group p-6 rounded-2xl bg-white/5 border border-white/10 hover:border-amber-500/30 hover:bg-amber-500/5 transition-all duration-300">
                    <div class="size-12 rounded-xl bg-amber-500/10 flex items-center justify-center mb-4 group-hover:bg-amber-500/20 transition">
                        <Clock class="size-6 text-amber-400" />
                    </div>
                    <h3 class="font-semibold text-white text-lg mb-2">24/7 mavjud</h3>
                    <p class="text-sm text-white/50 leading-relaxed">AI doim ishga tayyor — kun va tun.</p>
                </div>
                <div class="group p-6 rounded-2xl bg-white/5 border border-white/10 hover:border-rose-500/30 hover:bg-rose-500/5 transition-all duration-300">
                    <div class="size-12 rounded-xl bg-rose-500/10 flex items-center justify-center mb-4 group-hover:bg-rose-500/20 transition">
                        <Shield class="size-6 text-rose-400" />
                    </div>
                    <h3 class="font-semibold text-white text-lg mb-2">Xavfsiz</h3>
                    <p class="text-sm text-white/50 leading-relaxed">Ma'lumotlaringiz himoyalangan.</p>
                </div>
                <div class="group p-6 rounded-2xl bg-white/5 border border-white/10 hover:border-cyan-500/30 hover:bg-cyan-500/5 transition-all duration-300">
                    <div class="size-12 rounded-xl bg-cyan-500/10 flex items-center justify-center mb-4 group-hover:bg-cyan-500/20 transition">
                        <MessageCircle class="size-6 text-cyan-400" />
                    </div>
                    <h3 class="font-semibold text-white text-lg mb-2">O'zbek tilida</h3>
                    <p class="text-sm text-white/50 leading-relaxed">Butunlay o'zbek tilida ishlaydi.</p>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="relative z-10 px-6 py-20 max-w-4xl mx-auto text-center">
            <div class="p-10 sm:p-16 rounded-3xl bg-gradient-to-r from-sky-500/10 via-cyan-500/10 to-emerald-500/10 border border-white/10">
                <h2 class="text-3xl sm:text-4xl font-bold mb-4">Mobil ilovani yuklab oling</h2>
                <p class="text-white/50 max-w-lg mx-auto mb-8">QuickMedAI mobil ilovasi orqali AI tashxis oling. Tez va qulay!</p>
                <Link
                    href="/login"
                    class="inline-flex items-center gap-3 px-8 py-4 rounded-2xl bg-gradient-to-r from-sky-500 to-cyan-400 hover:from-sky-400 hover:to-cyan-300 text-white font-semibold text-base transition-all shadow-xl shadow-sky-500/30 hover:shadow-sky-400/40 hover:scale-[1.02]"
                >
                    Admin panelga kirish
                    <ArrowRight class="size-4" />
                </Link>
            </div>
        </section>

        <!-- Footer -->
        <footer class="relative z-10 border-t border-white/5 px-6 py-8 max-w-7xl mx-auto">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2 text-white/40 text-sm">
                    <HeartPulse class="size-4" />
                    <span>&copy; {{ new Date().getFullYear() }} QuickMedAI</span>
                </div>
                <p class="text-xs text-white/30 max-w-md text-center sm:text-right">
                    <Cross class="size-3.5 inline-block mr-1 text-white/40" /> Bu platforma tibbiy maslahat o'rnini bosmaydi. Sog'liq muammolari uchun doimo shifokorga murojaat qiling.
                </p>
            </div>
        </footer>
    </div>
</template>
