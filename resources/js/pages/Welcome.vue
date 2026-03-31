<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { HeartPulse, MessageCircle, Mic, Shield, Stethoscope, Clock, ArrowRight, Sparkles } from 'lucide-vue-next';

withDefaults(
    defineProps<{
        canRegister: boolean;
    }>(),
    {
        canRegister: true,
    },
);
</script>

<template>
    <Head title="QuickMedAI вЂ” AI tibbiy maslahat yordamchisi">
        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />
    </Head>

    <div class="min-h-screen bg-gradient-to-b from-slate-950 via-sky-950 to-slate-950 text-white overflow-hidden">
        <!-- Animated background -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-sky-500/10 rounded-full blur-3xl animate-pulse" />
            <div class="absolute top-1/3 -left-40 w-80 h-80 bg-emerald-500/10 rounded-full blur-3xl animate-pulse [animation-delay:2s]" />
            <div class="absolute bottom-20 right-1/4 w-72 h-72 bg-violet-500/8 rounded-full blur-3xl animate-pulse [animation-delay:4s]" />
        </div>

        <!-- Header / Nav -->
        <header class="relative z-10 flex items-center justify-between px-6 py-4 max-w-7xl mx-auto">
            <div class="flex items-center gap-3">
                <div class="size-10 rounded-xl bg-sky-500 flex items-center justify-center shadow-lg shadow-sky-500/30">
                    <HeartPulse class="size-5 text-white" />
                </div>
                <span class="font-bold text-lg tracking-tight">QuickMedAI</span>
            </div>
            <nav class="flex items-center gap-3">
                <Link
                    v-if="$page.props.auth.user"
                    href="/dashboard"
                    class="px-5 py-2 rounded-xl bg-sky-500 hover:bg-sky-400 text-white text-sm font-medium transition shadow-lg shadow-sky-500/30"
                >
                    Dashboard
                </Link>
                <template v-else>
                    <Link
                        href="/login"
                        class="px-4 py-2 rounded-xl text-sm text-white/70 hover:text-white transition"
                    >
                        Kirish
                    </Link>
                    <Link
                        href="/chat"
                        class="px-5 py-2 rounded-xl bg-sky-500 hover:bg-sky-400 text-white text-sm font-medium transition shadow-lg shadow-sky-500/30"
                    >
                        Boshlash
                    </Link>
                </template>
            </nav>
        </header>

        <!-- Hero -->
        <section class="relative z-10 px-6 pt-16 pb-24 max-w-7xl mx-auto">
            <div class="max-w-3xl mx-auto text-center">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-sky-500/10 border border-sky-500/20 text-sky-300 text-sm mb-8">
                    <Sparkles class="size-4" />
                    AI texnologiyasi bilan ishlaydigan tibbiy maslahat
                </div>

                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-tight tracking-tight">
                    Sog'lig'ingiz haqida
                    <span class="bg-gradient-to-r from-sky-400 via-cyan-300 to-emerald-400 bg-clip-text text-transparent">
                        aqlli maslahat
                    </span>
                    oling
                </h1>

                <p class="mt-6 text-lg text-white/60 max-w-2xl mx-auto leading-relaxed">
                    QuickMedAI вЂ” sun'iy intellekt yordamida simptomlaringizni tahlil qiladi,
                    kasalliklarni aniqlashga yordam beradi va eng yaxshi shifokorlarni tavsiya qiladi.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mt-10">
                    <Link
                        href="/chat"
                        class="group flex items-center gap-3 px-8 py-4 rounded-2xl bg-sky-500 hover:bg-sky-400 text-white font-semibold text-base transition-all shadow-xl shadow-sky-500/30 hover:shadow-sky-400/40 hover:scale-[1.02]"
                    >
                        <MessageCircle class="size-5" />
                        Hoziroq boshlash
                        <ArrowRight class="size-4 group-hover:translate-x-1 transition-transform" />
                    </Link>
                    <Link
                        href="/login"
                        class="flex items-center gap-2 px-6 py-4 rounded-2xl bg-white/5 hover:bg-white/10 border border-white/10 hover:border-white/20 text-white/80 hover:text-white text-base transition"
                    >
                        Admin panelga kirish
                    </Link>
                </div>
            </div>

            <!-- Chat mockup -->
            <div class="mt-20 max-w-2xl mx-auto">
                <div class="bg-white/5 backdrop-blur-xl rounded-3xl border border-white/10 p-6 shadow-2xl">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-white/10">
                        <div class="size-8 rounded-full bg-sky-500 flex items-center justify-center">
                            <HeartPulse class="size-4 text-white" />
                        </div>
                        <div>
                            <p class="text-sm font-medium text-white">QuickMedAI yordamchi</p>
                            <p class="text-xs text-sky-300">Online</p>
                        </div>
                        <div class="ml-auto flex gap-1">
                            <div class="size-2 rounded-full bg-emerald-400 animate-pulse" />
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="flex gap-3">
                            <div class="size-7 rounded-full bg-sky-500/20 flex items-center justify-center shrink-0">
                                <HeartPulse class="size-3.5 text-sky-400" />
                            </div>
                            <div class="px-4 py-3 rounded-2xl bg-white/10 text-sm text-white/90 max-w-[80%]">
                                Assalomu alaykum! Belgilaringizni aytib bering, men yordam beraman рџЏҐ
                            </div>
                        </div>
                        <div class="flex gap-3 flex-row-reverse">
                            <div class="size-7 rounded-full bg-emerald-500/20 flex items-center justify-center shrink-0">
                                <span class="text-xs">рџ‘¤</span>
                            </div>
                            <div class="px-4 py-3 rounded-2xl bg-sky-500 text-sm text-white max-w-[80%] shadow-lg shadow-sky-500/20">
                                Boshim og'riyapti va isitmam bor
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <div class="size-7 rounded-full bg-sky-500/20 flex items-center justify-center shrink-0">
                                <HeartPulse class="size-3.5 text-sky-400" />
                            </div>
                            <div class="px-4 py-3 rounded-2xl bg-white/10 text-sm text-white/90 max-w-[80%]">
                                <div class="flex items-center gap-2 text-sky-300">
                                    <Sparkles class="size-3.5" />
                                    <span class="text-xs font-medium">AI tahlil qilmoqda...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features -->
        <section class="relative z-10 px-6 py-20 max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold">Nima uchun <span class="text-sky-400">QuickMedAI</span>?</h2>
                <p class="mt-3 text-white/50 max-w-lg mx-auto">Zamonaviy texnologiyalar va tibbiyot tajribasini birlashtirgan platforma</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="group p-6 rounded-2xl bg-white/5 border border-white/10 hover:border-sky-500/30 hover:bg-sky-500/5 transition-all duration-300">
                    <div class="size-12 rounded-xl bg-sky-500/10 flex items-center justify-center mb-4 group-hover:bg-sky-500/20 transition">
                        <Sparkles class="size-6 text-sky-400" />
                    </div>
                    <h3 class="font-semibold text-white text-lg mb-2">AI tahlil</h3>
                    <p class="text-sm text-white/50 leading-relaxed">OpenAI GPT-4o texnologiyasi orqali simptomlaringizni chuqur tahlil qiladi va aniq tavsiyalar beradi.</p>
                </div>

                <div class="group p-6 rounded-2xl bg-white/5 border border-white/10 hover:border-emerald-500/30 hover:bg-emerald-500/5 transition-all duration-300">
                    <div class="size-12 rounded-xl bg-emerald-500/10 flex items-center justify-center mb-4 group-hover:bg-emerald-500/20 transition">
                        <Mic class="size-6 text-emerald-400" />
                    </div>
                    <h3 class="font-semibold text-white text-lg mb-2">Ovozli kiritish</h3>
                    <p class="text-sm text-white/50 leading-relaxed">Simptomlaringizni ovoz bilan aytib bering вЂ” Whisper AI o'zbek tilida tanib oladi.</p>
                </div>

                <div class="group p-6 rounded-2xl bg-white/5 border border-white/10 hover:border-violet-500/30 hover:bg-violet-500/5 transition-all duration-300">
                    <div class="size-12 rounded-xl bg-violet-500/10 flex items-center justify-center mb-4 group-hover:bg-violet-500/20 transition">
                        <Stethoscope class="size-6 text-violet-400" />
                    </div>
                    <h3 class="font-semibold text-white text-lg mb-2">Shifokor tavsiyasi</h3>
                    <p class="text-sm text-white/50 leading-relaxed">Kasalligingizga mos eng yaxshi mutaxassislarni tanlaydi va aloqa ma'lumotlarini beradi.</p>
                </div>

                <div class="group p-6 rounded-2xl bg-white/5 border border-white/10 hover:border-amber-500/30 hover:bg-amber-500/5 transition-all duration-300">
                    <div class="size-12 rounded-xl bg-amber-500/10 flex items-center justify-center mb-4 group-hover:bg-amber-500/20 transition">
                        <Clock class="size-6 text-amber-400" />
                    </div>
                    <h3 class="font-semibold text-white text-lg mb-2">24/7 mavjud</h3>
                    <p class="text-sm text-white/50 leading-relaxed">Kun va tunning istalgan vaqtida maslahat oling вЂ” AI doim ishga tayyor.</p>
                </div>

                <div class="group p-6 rounded-2xl bg-white/5 border border-white/10 hover:border-rose-500/30 hover:bg-rose-500/5 transition-all duration-300">
                    <div class="size-12 rounded-xl bg-rose-500/10 flex items-center justify-center mb-4 group-hover:bg-rose-500/20 transition">
                        <Shield class="size-6 text-rose-400" />
                    </div>
                    <h3 class="font-semibold text-white text-lg mb-2">Xavfsiz va maxfiy</h3>
                    <p class="text-sm text-white/50 leading-relaxed">Shaxsiy ma'lumotlaringiz himoyalangan. Ro'yxatdan o'tmasdan foydalanish mumkin.</p>
                </div>

                <div class="group p-6 rounded-2xl bg-white/5 border border-white/10 hover:border-cyan-500/30 hover:bg-cyan-500/5 transition-all duration-300">
                    <div class="size-12 rounded-xl bg-cyan-500/10 flex items-center justify-center mb-4 group-hover:bg-cyan-500/20 transition">
                        <MessageCircle class="size-6 text-cyan-400" />
                    </div>
                    <h3 class="font-semibold text-white text-lg mb-2">O'zbek tilida</h3>
                    <p class="text-sm text-white/50 leading-relaxed">Butunlay o'zbek tilida ishlaydi вЂ” savol bering, javob oling, hamma narsa o'zbekcha.</p>
                </div>
            </div>
        </section>

        <!-- How it works -->
        <section class="relative z-10 px-6 py-20 max-w-5xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold">Qanday <span class="text-sky-400">ishlaydi</span>?</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="size-14 rounded-2xl bg-sky-500/10 border border-sky-500/20 flex items-center justify-center mx-auto mb-4 text-sky-400 font-bold text-xl">1</div>
                    <h3 class="font-semibold text-white mb-1">Jins va yosh</h3>
                    <p class="text-sm text-white/40">Ma'lumotlaringizni tanlang</p>
                </div>
                <div class="text-center">
                    <div class="size-14 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center mx-auto mb-4 text-emerald-400 font-bold text-xl">2</div>
                    <h3 class="font-semibold text-white mb-1">Kasallik tanlash</h3>
                    <p class="text-sm text-white/40">Ro'yxatdan kasallikni belgilang</p>
                </div>
                <div class="text-center">
                    <div class="size-14 rounded-2xl bg-violet-500/10 border border-violet-500/20 flex items-center justify-center mx-auto mb-4 text-violet-400 font-bold text-xl">3</div>
                    <h3 class="font-semibold text-white mb-1">Simptomlar</h3>
                    <p class="text-sm text-white/40">Matn yoki ovoz bilan aytib bering</p>
                </div>
                <div class="text-center">
                    <div class="size-14 rounded-2xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center mx-auto mb-4 text-amber-400 font-bold text-xl">4</div>
                    <h3 class="font-semibold text-white mb-1">AI natija</h3>
                    <p class="text-sm text-white/40">Tavsiya va shifokor oling</p>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="relative z-10 px-6 py-20 max-w-4xl mx-auto text-center">
            <div class="p-10 sm:p-16 rounded-3xl bg-gradient-to-r from-sky-500/10 via-cyan-500/10 to-emerald-500/10 border border-white/10">
                <h2 class="text-3xl sm:text-4xl font-bold mb-4">Hoziroq sinab ko'ring</h2>
                <p class="text-white/50 max-w-lg mx-auto mb-8">Ro'yxatdan o'tish shart emas. Bir daqiqada AI bilan sog'lig'ingiz haqida maslahat oling.</p>
                <Link
                    href="/chat"
                    class="inline-flex items-center gap-3 px-8 py-4 rounded-2xl bg-sky-500 hover:bg-sky-400 text-white font-semibold text-base transition-all shadow-xl shadow-sky-500/30 hover:shadow-sky-400/40 hover:scale-[1.02]"
                >
                    <MessageCircle class="size-5" />
                    Chatni boshlash
                    <ArrowRight class="size-4" />
                </Link>
            </div>
        </section>

        <!-- Footer -->
        <footer class="relative z-10 border-t border-white/5 px-6 py-8 max-w-7xl mx-auto">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2 text-white/40 text-sm">
                    <HeartPulse class="size-4" />
                    <span>В© {{ new Date().getFullYear() }} QuickMedAI</span>
                </div>
                <p class="text-xs text-white/30 max-w-md text-center sm:text-right">
                    вљ•пёЏ Bu platforma tibbiy maslahat o'rnini bosmaydi. Sog'liq muammolari uchun doimo shifokorga murojaat qiling.
                </p>
            </div>
        </footer>
    </div>
</template>
