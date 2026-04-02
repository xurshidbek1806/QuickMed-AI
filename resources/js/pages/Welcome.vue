<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    HeartPulse, MessageCircle, Mic, Shield, Stethoscope, Clock,
    ArrowRight, Sparkles, Zap, Hospital, UserRound, Cake,
    Bug, FileText, Cross, Activity, Globe
} from 'lucide-vue-next';
</script>

<template>
    <Head title="QuickMedAI — AI tibbiy maslahat yordamchisi">
        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900" rel="stylesheet" />
    </Head>

    <div class="min-h-screen bg-[#fafbfc] text-gray-900 overflow-hidden font-sans">

        <!-- ═══ Navbar ═══ -->
        <header class="sticky top-0 z-50 border-b border-gray-100 bg-white/80 backdrop-blur-xl">
            <div class="max-w-6xl mx-auto flex items-center justify-between px-6 h-16">
                <div class="flex items-center gap-2.5">
                    <div class="size-9 rounded-xl bg-gradient-to-br from-blue-600 to-cyan-500 flex items-center justify-center shadow-md shadow-blue-500/20">
                        <HeartPulse class="size-5 text-white" />
                    </div>
                    <span class="font-extrabold text-lg tracking-tight">QuickMed<span class="text-blue-600">AI</span></span>
                </div>
                <nav class="hidden sm:flex items-center gap-8 text-[13px] font-medium text-gray-500">
                    <a href="#features" class="hover:text-gray-900 transition">Imkoniyatlar</a>
                    <a href="#how" class="hover:text-gray-900 transition">Qanday ishlaydi</a>
                    <a href="#why" class="hover:text-gray-900 transition">Afzalliklar</a>
                </nav>
                <div class="flex items-center gap-3">
                    <Link
                        v-if="$page.props.auth.user"
                        href="/dashboard"
                        class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold transition shadow-sm"
                    >
                        Dashboard
                    </Link>
                    <template v-else>
                        <Link href="/login" class="hidden sm:inline-flex px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:text-gray-900 transition">
                            Kirish
                        </Link>
                        <Link href="/register" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold transition shadow-sm">
                            Boshlash
                        </Link>
                    </template>
                </div>
            </div>
        </header>

        <!-- ═══ Hero ═══ -->
        <section class="relative overflow-hidden">
            <!-- Subtle grid -->
            <div class="absolute inset-0 bg-[linear-gradient(to_right,#f0f0f0_1px,transparent_1px),linear-gradient(to_bottom,#f0f0f0_1px,transparent_1px)] bg-[size:64px_64px] opacity-50" />
            <!-- Radial glow -->
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[500px] bg-[radial-gradient(ellipse_at_center,rgba(59,130,246,0.12),transparent_70%)] rounded-full" />

            <div class="relative max-w-6xl mx-auto px-6 pt-20 pb-24 text-center">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-blue-50 border border-blue-100 text-blue-700 text-xs font-semibold mb-8">
                    <Activity class="size-3.5" />
                    GPT-4o bilan ishlaydigan tibbiy yordamchi
                </div>

                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-[1.1] max-w-3xl mx-auto">
                    Sun'iy intellekt bilan
                    <span class="text-gradient">tibbiy tashxis</span>
                    oling
                </h1>

                <p class="mt-6 text-lg text-gray-500 max-w-xl mx-auto leading-relaxed">
                    Simptomlaringizni kiriting — AI bir necha soniyada tahlil qilib, sizga mos shifokor va tavsiyalarni beradi.
                </p>

                <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                    <Link
                        href="/chat"
                        class="group inline-flex items-center gap-2.5 px-7 py-3.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-[15px] transition-all shadow-lg shadow-blue-500/25 hover:shadow-blue-500/35 hover:scale-[1.02] active:scale-[0.98]"
                    >
                        AI Tashxis boshlash
                        <ArrowRight class="size-4 transition-transform group-hover:translate-x-0.5" />
                    </Link>
                    <a
                        href="#how"
                        class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl border border-gray-200 hover:border-gray-300 bg-white hover:bg-gray-50 text-gray-700 font-medium text-[15px] transition-all"
                    >
                        Qanday ishlaydi?
                    </a>
                </div>

                <!-- Hero metrics -->
                <div class="mt-16 grid grid-cols-3 gap-6 max-w-md mx-auto">
                    <div>
                        <p class="text-2xl sm:text-3xl font-extrabold text-gray-900">30s</p>
                        <p class="text-xs text-gray-400 mt-1">Tashxis vaqti</p>
                    </div>
                    <div>
                        <p class="text-2xl sm:text-3xl font-extrabold text-gray-900">24/7</p>
                        <p class="text-xs text-gray-400 mt-1">Ishlaydi</p>
                    </div>
                    <div>
                        <p class="text-2xl sm:text-3xl font-extrabold text-gray-900">O'z</p>
                        <p class="text-xs text-gray-400 mt-1">tilida</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ═══ Features ═══ -->
        <section id="features" class="py-20 bg-white border-t border-gray-100">
            <div class="max-w-6xl mx-auto px-6">
                <div class="text-center mb-14">
                    <p class="text-sm font-semibold text-blue-600 mb-2">Imkoniyatlar</p>
                    <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Nima qila oladi?</h2>
                    <p class="mt-3 text-gray-400 max-w-lg mx-auto">Zamonaviy AI texnologiyalari bilan tibbiy maslahat olish osonlashdi</p>
                </div>

                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <div class="group p-6 rounded-2xl bg-gray-50 border border-gray-100 hover:border-blue-200 hover:bg-blue-50/50 transition-all duration-300">
                        <div class="size-11 rounded-xl bg-blue-100 flex items-center justify-center mb-4 group-hover:bg-blue-200 transition">
                            <Mic class="size-5 text-blue-600" />
                        </div>
                        <h3 class="font-bold text-gray-900 mb-1.5">Ovozli kiritish</h3>
                        <p class="text-sm text-gray-500 leading-relaxed">O'zbek tilida ovoz bilan simptomlarni kiriting. Whisper AI tanib oladi.</p>
                    </div>
                    <div class="group p-6 rounded-2xl bg-gray-50 border border-gray-100 hover:border-emerald-200 hover:bg-emerald-50/50 transition-all duration-300">
                        <div class="size-11 rounded-xl bg-emerald-100 flex items-center justify-center mb-4 group-hover:bg-emerald-200 transition">
                            <Zap class="size-5 text-emerald-600" />
                        </div>
                        <h3 class="font-bold text-gray-900 mb-1.5">30 soniyada natija</h3>
                        <p class="text-sm text-gray-500 leading-relaxed">GPT-4o bir zumda simptomlarni tahlil qilib tashxis beradi.</p>
                    </div>
                    <div class="group p-6 rounded-2xl bg-gray-50 border border-gray-100 hover:border-violet-200 hover:bg-violet-50/50 transition-all duration-300">
                        <div class="size-11 rounded-xl bg-violet-100 flex items-center justify-center mb-4 group-hover:bg-violet-200 transition">
                            <Hospital class="size-5 text-violet-600" />
                        </div>
                        <h3 class="font-bold text-gray-900 mb-1.5">Shifokor topish</h3>
                        <p class="text-sm text-gray-500 leading-relaxed">Kasalligingizga mos eng yaqin mutaxassislarni tavsiya qiladi.</p>
                    </div>
                    <div class="group p-6 rounded-2xl bg-gray-50 border border-gray-100 hover:border-amber-200 hover:bg-amber-50/50 transition-all duration-300">
                        <div class="size-11 rounded-xl bg-amber-100 flex items-center justify-center mb-4 group-hover:bg-amber-200 transition">
                            <Shield class="size-5 text-amber-600" />
                        </div>
                        <h3 class="font-bold text-gray-900 mb-1.5">Xavfsiz</h3>
                        <p class="text-sm text-gray-500 leading-relaxed">Ma'lumotlaringiz himoyalangan. Shaxsiy axborot saqlanmaydi.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ═══ How it works ═══ -->
        <section id="how" class="py-20 border-t border-gray-100">
            <div class="max-w-6xl mx-auto px-6">
                <div class="text-center mb-14">
                    <p class="text-sm font-semibold text-blue-600 mb-2">Jarayon</p>
                    <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight">5 oddiy qadam</h2>
                </div>

                <div class="max-w-2xl mx-auto space-y-4">
                    <div v-for="(s, i) in [
                        { icon: UserRound, label: 'Jinsni tanlang', desc: 'Erkak yoki ayol' },
                        { icon: Cake, label: 'Yosh guruhini kiriting', desc: 'Chaqaloqdan keksagacha' },
                        { icon: Bug, label: 'Kasallikni tanlang', desc: 'Ro\'yxatdan yoki qidiring' },
                        { icon: FileText, label: 'Simptomlarni yozing', desc: 'Matn yoki ovoz bilan' },
                        { icon: Sparkles, label: 'AI natijani oling', desc: 'Tashxis + shifokor tavsiyasi' },
                    ]" :key="i"
                        class="flex items-center gap-5 p-5 rounded-2xl bg-white border border-gray-100 shadow-sm hover:shadow-md transition-shadow duration-300"
                    >
                        <div class="size-11 rounded-xl bg-blue-600 flex items-center justify-center text-white text-sm font-bold shrink-0 shadow-sm">
                            {{ i + 1 }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-gray-900">{{ s.label }}</p>
                            <p class="text-sm text-gray-400 mt-0.5">{{ s.desc }}</p>
                        </div>
                        <component :is="s.icon" class="size-5 text-gray-300 shrink-0" />
                    </div>
                </div>
            </div>
        </section>

        <!-- ═══ Why QuickMedAI ═══ -->
        <section id="why" class="py-20 bg-white border-t border-gray-100">
            <div class="max-w-6xl mx-auto px-6">
                <div class="text-center mb-14">
                    <p class="text-sm font-semibold text-blue-600 mb-2">Afzalliklar</p>
                    <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Nima uchun <span class="text-gradient">QuickMedAI</span>?</h2>
                    <p class="mt-3 text-gray-400 max-w-lg mx-auto">Zamonaviy texnologiyalar va tibbiyot tajribasini birlashtirgan platforma</p>
                </div>

                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5 max-w-4xl mx-auto">
                    <div class="group p-6 rounded-2xl bg-gray-50 border border-gray-100 hover:border-blue-200 transition-all duration-300">
                        <div class="size-10 rounded-lg bg-blue-100 flex items-center justify-center mb-3.5 group-hover:bg-blue-200 transition">
                            <Sparkles class="size-5 text-blue-600" />
                        </div>
                        <h3 class="font-bold text-gray-900 mb-1">GPT-4o AI tahlil</h3>
                        <p class="text-sm text-gray-500 leading-relaxed">OpenAI ning eng so'nggi modeli orqali chuqur tahlil.</p>
                    </div>
                    <div class="group p-6 rounded-2xl bg-gray-50 border border-gray-100 hover:border-emerald-200 transition-all duration-300">
                        <div class="size-10 rounded-lg bg-emerald-100 flex items-center justify-center mb-3.5 group-hover:bg-emerald-200 transition">
                            <Mic class="size-5 text-emerald-600" />
                        </div>
                        <h3 class="font-bold text-gray-900 mb-1">Ovoz tanish</h3>
                        <p class="text-sm text-gray-500 leading-relaxed">Whisper AI o'zbek tilida ovozingizni tanib oladi.</p>
                    </div>
                    <div class="group p-6 rounded-2xl bg-gray-50 border border-gray-100 hover:border-violet-200 transition-all duration-300">
                        <div class="size-10 rounded-lg bg-violet-100 flex items-center justify-center mb-3.5 group-hover:bg-violet-200 transition">
                            <Stethoscope class="size-5 text-violet-600" />
                        </div>
                        <h3 class="font-bold text-gray-900 mb-1">Shifokor tavsiyasi</h3>
                        <p class="text-sm text-gray-500 leading-relaxed">Kasalligingizga mos mutaxassislarni topadi.</p>
                    </div>
                    <div class="group p-6 rounded-2xl bg-gray-50 border border-gray-100 hover:border-amber-200 transition-all duration-300">
                        <div class="size-10 rounded-lg bg-amber-100 flex items-center justify-center mb-3.5 group-hover:bg-amber-200 transition">
                            <Clock class="size-5 text-amber-600" />
                        </div>
                        <h3 class="font-bold text-gray-900 mb-1">24/7 mavjud</h3>
                        <p class="text-sm text-gray-500 leading-relaxed">AI doim ishga tayyor — kun va tun.</p>
                    </div>
                    <div class="group p-6 rounded-2xl bg-gray-50 border border-gray-100 hover:border-rose-200 transition-all duration-300">
                        <div class="size-10 rounded-lg bg-rose-100 flex items-center justify-center mb-3.5 group-hover:bg-rose-200 transition">
                            <Shield class="size-5 text-rose-600" />
                        </div>
                        <h3 class="font-bold text-gray-900 mb-1">Xavfsiz va ishonchli</h3>
                        <p class="text-sm text-gray-500 leading-relaxed">Ma'lumotlaringiz himoyalangan.</p>
                    </div>
                    <div class="group p-6 rounded-2xl bg-gray-50 border border-gray-100 hover:border-sky-200 transition-all duration-300">
                        <div class="size-10 rounded-lg bg-sky-100 flex items-center justify-center mb-3.5 group-hover:bg-sky-200 transition">
                            <Globe class="size-5 text-sky-600" />
                        </div>
                        <h3 class="font-bold text-gray-900 mb-1">O'zbek tilida</h3>
                        <p class="text-sm text-gray-500 leading-relaxed">Butunlay o'zbek tilida ishlaydi.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ═══ CTA ═══ -->
        <section class="py-20 border-t border-gray-100">
            <div class="max-w-6xl mx-auto px-6">
                <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-700 p-12 sm:p-16 text-center">
                    <div class="absolute inset-0 bg-[linear-gradient(to_right,rgba(255,255,255,0.05)_1px,transparent_1px),linear-gradient(to_bottom,rgba(255,255,255,0.05)_1px,transparent_1px)] bg-[size:40px_40px]" />
                    <div class="absolute top-0 right-0 w-80 h-80 bg-white/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3" />

                    <div class="relative">
                        <h2 class="text-3xl sm:text-4xl font-extrabold text-white mb-4">Hoziroq boshlang</h2>
                        <p class="text-blue-200 max-w-md mx-auto mb-8">QuickMedAI bilan sog'liqni saqlash osonroq. AI tashxis oling — bepul!</p>
                        <Link
                            href="/chat"
                            class="group inline-flex items-center gap-2.5 px-8 py-4 rounded-xl bg-white hover:bg-gray-50 text-blue-700 font-bold text-base transition-all shadow-xl hover:shadow-2xl hover:scale-[1.02] active:scale-[0.98]"
                        >
                            Tashxis boshlash
                            <ArrowRight class="size-4 transition-transform group-hover:translate-x-0.5" />
                        </Link>
                    </div>
                </div>
            </div>
        </section>

        <!-- ═══ Footer ═══ -->
        <footer class="border-t border-gray-100 bg-white">
            <div class="max-w-6xl mx-auto px-6 py-8">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-2.5">
                        <div class="size-7 rounded-lg bg-gradient-to-br from-blue-600 to-cyan-500 flex items-center justify-center">
                            <HeartPulse class="size-4 text-white" />
                        </div>
                        <span class="text-sm font-bold text-gray-900">QuickMed<span class="text-blue-600">AI</span></span>
                        <span class="text-xs text-gray-400 ml-2">&copy; {{ new Date().getFullYear() }}</span>
                    </div>
                    <p class="text-xs text-gray-400 max-w-sm text-center sm:text-right leading-relaxed">
                        Bu platforma tibbiy maslahat o'rnini bosmaydi. Sog'liq muammolari uchun doimo shifokorga murojaat qiling.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>
