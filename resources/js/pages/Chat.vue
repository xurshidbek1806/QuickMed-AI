<script setup lang="ts">
import { ref, computed, nextTick, onMounted } from 'vue';
import {
    Send, Mic, MicOff, StopCircle, RotateCcw, Phone, MapPin,
    ChevronLeft, ChevronRight, Loader2, HeartPulse, User, Bot,
    Volume2, AlertTriangle, ExternalLink, Check, Baby, PersonStanding,
    UserRound, Users, UserCog, UserCheck, Cross, Bug, Calendar,
    FileText, Crosshair, PenLine, MicVocal
} from 'lucide-vue-next';

// ── Types ─────────────────────────────────────────────────────────────────────
interface Disease { id: string; name: string; category: string; description: string }
interface Doctor  { id: string; name: string; specialization: string; phone_number: string; location_url: string; photo: string | null }
interface Banner  { id: number; title: string; description: string; media_url: string | null; link: string | null; media_type: string }

type Step =
    | 'gender' | 'age' | 'disease' | 'input_type'
    | 'text_input' | 'voice_input' | 'analyzing' | 'result';

interface Message {
    id:       number;
    role:     'ai' | 'user';
    type:     'text' | 'options' | 'diseases' | 'result' | 'loading' | 'error';
    content:  string;
    options?: { label: string; value: string; icon?: string }[];
    diseases?: Disease[];
    result?: { analysis: string; disease: any; doctors: Doctor[] };
    page?: number;
    total?: number;
}

// ── Props ─────────────────────────────────────────────────────────────────────
const props = defineProps<{ banners: Banner[] }>();

// ── State ─────────────────────────────────────────────────────────────────────
const messages  = ref<Message[]>([]);
const step      = ref<Step>('gender');
const gender    = ref('');
const ageCategory = ref('');
const selectedDisease = ref<Disease | null>(null);
const symptoms  = ref('');
const msgIdSeq  = ref(0);
const chatEnd   = ref<HTMLDivElement>();
const textInput = ref('');
const diseasePage = ref(1);
const diseaseTotal = ref(0);
const diseases  = ref<Disease[]>([]);
const diseaseSearch = ref('');
const loadingDiseases = ref(false);

// Voice
const isRecording = ref(false);
const mediaRecorder = ref<MediaRecorder | null>(null);
const audioChunks   = ref<Blob[]>([]);
const transcribing  = ref(false);

const AGE_CATEGORIES = [
    { value: 'godak',     label: "Chaqaloq",       desc: '0-1 yosh',   icon: Baby },
    { value: 'bola',      label: 'Bola',            desc: '1-12 yosh',  icon: PersonStanding },
    { value: 'osmir',     label: 'O\'smir',         desc: '12-18 yosh', icon: UserRound },
    { value: 'yoshlar',   label: 'Yoshlar',          desc: '18-30 yosh', icon: User },
    { value: 'orta_yosh', label: 'O\'rta yosh',     desc: '30-60 yosh', icon: UserCog },
    { value: 'keksalar',  label: 'Keksalar',         desc: '60+ yosh',   icon: UserCheck },
];

// ── Helpers ───────────────────────────────────────────────────────────────────
function addMsg(msg: Omit<Message, 'id'>) {
    messages.value.push({ id: ++msgIdSeq.value, ...msg });
    nextTick(() => chatEnd.value?.scrollIntoView({ behavior: 'smooth' }));
}

function addAI(content: string, extra: Partial<Message> = {}) {
    addMsg({ role: 'ai', type: 'text', content, ...extra });
}

function addUser(content: string) {
    addMsg({ role: 'user', type: 'text', content });
}

function removeLastIfLoading() {
    const last = messages.value[messages.value.length - 1];
    if (last?.type === 'loading') messages.value.pop();
}

// ── Init ──────────────────────────────────────────────────────────────────────
onMounted(() => {
    addAI(
        "Assalomu alaykum! Men QuickMedAI \u2014 sog'liqni saqlash bo'yicha virtual yordamchiman.\n\nMen sizga tibbiy ma'lumotlar asosida tavsiya beraman. Keling, boshlaylik.",
        {
            type: 'options',
            options: [
                { label: 'Erkak', value: 'male' },
                { label: 'Ayol',  value: 'female' },
            ],
        }
    );
});

// ── Gender ────────────────────────────────────────────────────────────────────
function selectGender(val: string) {
    if (step.value !== 'gender') return;
    gender.value = val;
    const label = val === 'male' ? 'Erkak' : 'Ayol';
    addUser(label);
    step.value = 'age';
    addAI('Yaxshi! Endi yosh guruhingizni tanlang:', {
        type: 'options',
        options: AGE_CATEGORIES.map(a => ({ label: a.label, value: a.value })),
    });
}

// ── Age ───────────────────────────────────────────────────────────────────────
function selectAge(val: string) {
    if (step.value !== 'age') return;
    ageCategory.value = val;
    const found = AGE_CATEGORIES.find(a => a.value === val);
    addUser(`${found?.label} (${found?.desc})`);
    step.value = 'disease';
    loadDiseases(1);
}

// ── Diseases ──────────────────────────────────────────────────────────────────
async function loadDiseases(page: number) {
    loadingDiseases.value = true;
    diseasePage.value     = page;

    const params = new URLSearchParams({
        age_category: ageCategory.value,
        page:         String(page),
        ...(diseaseSearch.value ? { search: diseaseSearch.value } : {}),
    });

    try {
        const res  = await fetch(`/api/diseases?${params}`);
        const data = await res.json();
        diseases.value    = data.data;
        diseaseTotal.value = data.last_page;

        addAI(
            page === 1 && !diseaseSearch.value
                ? 'Qaysi kasallik yoki holat bo\'yicha ma\'lumot olmoqchisiz?'
                : '',
            {
                type:     'diseases',
                diseases: data.data,
                page,
                total:    data.last_page,
            }
        );
    } catch (e) {
        addAI('Kasalliklar ro\'yxatini yuklab bo\'lmadi. Qaytadan urinib ko\'ring.', { type: 'error' });
    } finally {
        loadingDiseases.value = false;
    }
}

function selectDisease(d: Disease) {
    if (step.value !== 'disease') return;
    selectedDisease.value = d;
    addUser(d.name);
    step.value = 'input_type';
    addAI(`"${d.name}" bo\'yicha belgilaringizni qanday aytmoqchisiz?`, {
        type: 'options',
        options: [
            { label: 'Matn yozaman',    value: 'text'  },
            { label: 'Ovoz bilan aytaman', value: 'voice' },
        ],
    });
}

// ── Input type ────────────────────────────────────────────────────────────────
function selectInputType(val: string) {
    if (step.value !== 'input_type') return;
    if (val === 'text') {
        addUser('Matn yozaman');
        step.value = 'text_input';
        addAI('Belgilaringizni yozing. Masalan: "Boshim og\'riyapti, isitma bor, tomoqim achishmoqda..."');
    } else {
        addUser('Ovoz bilan aytaman');
        step.value = 'voice_input';
        addAI('Mikrofon tugmasini bosib, belgilaringizni gapiring. Tugagach to\'xtatish tugmasini bosing.');
    }
}

// ── Text submit ───────────────────────────────────────────────────────────────
async function submitText() {
    const text = textInput.value.trim();
    if (!text || step.value !== 'text_input') return;
    textInput.value = '';
    symptoms.value  = text;
    addUser(text);
    await analyze(text);
}

// ── Voice ─────────────────────────────────────────────────────────────────────
async function toggleRecording() {
    if (isRecording.value) {
        mediaRecorder.value?.stop();
        isRecording.value = false;
    } else {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
            const mr = new MediaRecorder(stream);
            audioChunks.value = [];
            mr.ondataavailable = e => audioChunks.value.push(e.data);
            mr.onstop = async () => {
                stream.getTracks().forEach(t => t.stop());
                await sendVoice();
            };
            mr.start();
            mediaRecorder.value = mr;
            isRecording.value = true;
        } catch {
            addAI('Mikrofonga ruxsat berilmadi. Iltimos, brauzer sozlamalarini tekshiring.', { type: 'error' });
        }
    }
}

async function sendVoice() {
    transcribing.value = true;
    addMsg({ role: 'ai', type: 'loading', content: 'Ovoz tanib olinmoqda...' });

    const blob     = new Blob(audioChunks.value, { type: 'audio/webm' });
    const formData = new FormData();
    formData.append('audio', blob, 'recording.webm');

    try {
        const res  = await fetch('/api/chat/voice', { method: 'POST', body: formData, headers: { 'X-CSRF-TOKEN': getCsrf() } });
        const data = await res.json();
        removeLastIfLoading();

        if (!res.ok || data.error) {
            addAI(data.error ?? 'Ovozni tanib bo\'lmadi.', { type: 'error' });
        } else {
            symptoms.value = data.text;
            addUser(`"${data.text}"`);
            step.value = 'analyzing';
            await analyze(data.text);
        }
    } catch {
        removeLastIfLoading();
        addAI('Ovoz yuborishda xatolik yuz berdi.', { type: 'error' });
    } finally {
        transcribing.value = false;
    }
}

// ── Analyze ───────────────────────────────────────────────────────────────────
async function analyze(text: string) {
    step.value = 'analyzing';
    addMsg({ role: 'ai', type: 'loading', content: 'AI tahlil qilmoqda...' });

    try {
        const res  = await fetch('/api/chat/analyze', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': getCsrf() },
            body:    JSON.stringify({
                disease_id:   selectedDisease.value!.id,
                gender:       gender.value,
                age_category: ageCategory.value,
                symptoms:     text,
            }),
        });
        const data = await res.json();
        removeLastIfLoading();

        if (!res.ok) {
            addAI('Tahlil amalga oshmadi. Qaytadan urinib ko\'ring.', { type: 'error' });
            step.value = 'text_input';
        } else {
            step.value = 'result';
            addMsg({ role: 'ai', type: 'result', content: '', result: data });
        }
    } catch {
        removeLastIfLoading();
        addAI('Ulanishda xatolik. Internet aloqangizni tekshiring.', { type: 'error' });
        step.value = 'text_input';
    }
}

// ── Restart ───────────────────────────────────────────────────────────────────
function restart() {
    messages.value    = [];
    step.value        = 'gender';
    gender.value      = '';
    ageCategory.value = '';
    selectedDisease.value = null;
    symptoms.value    = '';
    textInput.value   = '';
    diseasePage.value = 1;
    diseases.value    = [];
    onMounted(() => {});
    // Re-init
    addAI("Yangi savol uchun tayyor! Jinsingizni tanlang:", {
        type: 'options',
        options: [
            { label: 'Erkak', value: 'male' },
            { label: 'Ayol',  value: 'female' },
        ],
    });
}

function handleOptionClick(msg: Message, val: string) {
    if (step.value === 'gender')     selectGender(val);
    else if (step.value === 'age')   selectAge(val);
    else if (step.value === 'input_type') selectInputType(val);
}

function getCsrf(): string {
    return (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content ?? '';
}

// ── Step Progress ─────────────────────────────────────────────────────────────
const STEPS = [
    { key: 'gender',     label: 'Jins',       icon: UserRound },
    { key: 'age',        label: 'Yosh',       icon: Calendar },
    { key: 'disease',    label: 'Kasallik',   icon: Bug },
    { key: 'input',      label: 'Simptom',    icon: PenLine },
    { key: 'result',     label: 'Natija',     icon: Crosshair },
];

const currentStepIndex = computed(() => {
    const s = step.value;
    if (s === 'gender') return 0;
    if (s === 'age') return 1;
    if (s === 'disease') return 2;
    if (s === 'input_type' || s === 'text_input' || s === 'voice_input') return 3;
    return 4; // analyzing, result
});
</script>

<template>
    <div class="h-screen flex flex-col bg-gradient-to-br from-slate-950 via-sky-950 to-slate-950 text-white overflow-hidden">
        <!-- Header -->
        <header class="shrink-0 px-4 sm:px-6 py-3 bg-white/5 backdrop-blur-xl border-b border-white/10 flex items-center gap-3">
            <a href="/" class="flex items-center gap-3 hover:opacity-80 transition">
                <div class="size-9 rounded-xl bg-gradient-to-br from-sky-500 to-cyan-400 flex items-center justify-center shadow-lg shadow-sky-500/30">
                    <HeartPulse class="size-5 text-white" />
                </div>
                <div>
                    <h1 class="font-bold text-white text-base leading-tight">QuickMedAI</h1>
                    <p class="text-sky-300/80 text-[11px]">AI tibbiy maslahat yordamchisi</p>
                </div>
            </a>
            <div class="ml-auto flex items-center gap-3">
                <button
                    v-if="step === 'result'"
                    @click="restart"
                    class="flex items-center gap-2 px-4 py-2 rounded-xl bg-sky-500/20 hover:bg-sky-500/30 text-sky-300 text-sm font-medium transition border border-sky-500/20"
                >
                    <RotateCcw class="size-3.5" /> Yangi savol
                </button>
                <a href="/login" class="text-xs text-white/40 hover:text-white/70 transition">Admin</a>
            </div>
        </header>

        <!-- Step Progress -->
        <div class="shrink-0 px-4 sm:px-6 py-2.5 bg-white/[0.02] border-b border-white/5">
            <div class="max-w-2xl mx-auto flex items-center gap-1">
                <template v-for="(s, i) in STEPS" :key="s.key">
                    <div class="flex items-center gap-1.5">
                        <div :class="[
                            'size-7 rounded-lg flex items-center justify-center text-xs font-medium transition-all duration-300',
                            i < currentStepIndex
                                ? 'bg-sky-500 text-white shadow-md shadow-sky-500/30'
                                : i === currentStepIndex
                                    ? 'bg-sky-500/20 text-sky-300 border border-sky-500/40 ring-2 ring-sky-500/20'
                                    : 'bg-white/5 text-white/20 border border-white/5'
                        ]">
                            <Check v-if="i < currentStepIndex" class="size-3.5" />
                            <component v-else :is="s.icon" class="size-3.5" />
                        </div>
                        <span :class="[
                            'text-[11px] font-medium hidden sm:inline transition-colors',
                            i <= currentStepIndex ? 'text-white/70' : 'text-white/20'
                        ]">{{ s.label }}</span>
                    </div>
                    <div v-if="i < STEPS.length - 1" :class="[
                        'flex-1 h-px transition-colors duration-300',
                        i < currentStepIndex ? 'bg-sky-500/50' : 'bg-white/5'
                    ]" />
                </template>
            </div>
        </div>

        <!-- Body: chat + sidebar -->
        <div class="flex-1 flex overflow-hidden">
            <!-- Chat area -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Messages -->
                <div class="flex-1 overflow-y-auto px-3 sm:px-6 py-6 space-y-5 scroll-smooth">
                    <TransitionGroup name="msg">
                        <div v-for="msg in messages" :key="msg.id" :class="['flex gap-3', msg.role === 'user' ? 'flex-row-reverse' : 'flex-row']">
                            <!-- Avatar -->
                            <div :class="[
                                'shrink-0 size-8 rounded-xl flex items-center justify-center text-sm',
                                msg.role === 'ai'
                                    ? 'bg-gradient-to-br from-sky-500 to-cyan-400 shadow-lg shadow-sky-500/20'
                                    : 'bg-gradient-to-br from-emerald-500 to-teal-400 shadow-lg shadow-emerald-500/20'
                            ]">
                                <Bot v-if="msg.role === 'ai'" class="size-4" />
                                <User v-else class="size-4" />
                            </div>

                            <!-- Bubble -->
                            <div :class="['max-w-[80%] sm:max-w-[70%]', msg.role === 'user' ? 'items-end' : 'items-start', 'flex flex-col gap-2']">

                                <!-- Loading -->
                                <div v-if="msg.type === 'loading'" class="flex items-center gap-2 px-4 py-3 rounded-2xl bg-white/10 text-sky-200 text-sm">
                                    <Loader2 class="size-4 animate-spin" />
                                    {{ msg.content }}
                                </div>

                                <!-- Error -->
                                <div v-else-if="msg.type === 'error'" class="flex items-start gap-2 px-4 py-3 rounded-2xl bg-red-500/20 border border-red-500/30 text-red-200 text-sm">
                                    <AlertTriangle class="size-4 shrink-0 mt-0.5" />
                                    {{ msg.content }}
                                </div>

                                <!-- Plain text -->
                                <div v-else-if="msg.type === 'text'" :class="[
                                    'px-4 py-3 rounded-2xl text-sm leading-relaxed whitespace-pre-line',
                                    msg.role === 'ai'
                                        ? 'bg-white/10 text-white'
                                        : 'bg-sky-500 text-white shadow-lg shadow-sky-500/20'
                                ]">
                                    {{ msg.content }}
                                </div>

                                <!-- Options (buttons) -->
                                <template v-else-if="msg.type === 'options'">
                                    <div v-if="msg.content" class="px-4 py-3 rounded-2xl bg-white/10 backdrop-blur-sm text-white text-sm leading-relaxed">
                                        {{ msg.content }}
                                    </div>
                                    <div class="flex flex-wrap gap-2 mt-1">
                                        <button
                                            v-for="opt in msg.options"
                                            :key="opt.value"
                                            @click="handleOptionClick(msg, opt.value)"
                                            :disabled="step !== 'gender' && step !== 'age' && step !== 'input_type'"
                                            class="px-4 py-2.5 rounded-xl bg-white/[0.08] hover:bg-sky-500/30 border border-white/10 hover:border-sky-400/40 text-white text-sm transition-all duration-200 hover:shadow-lg hover:shadow-sky-500/10 disabled:opacity-30 disabled:cursor-not-allowed hover:scale-[1.02] active:scale-[0.98]"
                                        >
                                            {{ opt.label }}
                                        </button>
                                    </div>
                                </template>

                                <!-- Disease list -->
                                <template v-else-if="msg.type === 'diseases'">
                                    <div v-if="msg.content" class="px-4 py-3 rounded-2xl bg-white/10 text-white text-sm">
                                        {{ msg.content }}
                                    </div>
                                    <!-- Search -->
                                    <input
                                        v-if="step === 'disease'"
                                        v-model="diseaseSearch"
                                        @keyup.enter="loadDiseases(1)"
                                        placeholder="Qidirish..."
                                        class="w-full px-3 py-2 rounded-xl bg-white/10 border border-white/20 text-sm text-white placeholder-white/40 focus:outline-none focus:border-sky-400"
                                    />
                                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                                        <button
                                            v-for="d in msg.diseases"
                                            :key="d.id"
                                            @click="selectDisease(d)"
                                            :disabled="step !== 'disease'"
                                            class="group px-3 py-3 rounded-xl bg-white/[0.06] hover:bg-sky-500/20 border border-white/10 hover:border-sky-400/40 text-left transition-all duration-200 disabled:opacity-30 disabled:cursor-not-allowed hover:shadow-lg hover:shadow-sky-500/10 hover:scale-[1.02] active:scale-[0.98]"
                                        >
                                            <p class="text-sm font-medium text-white leading-tight group-hover:text-sky-100">{{ d.name }}</p>
                                            <p v-if="d.category" class="text-[11px] text-sky-300/70 mt-1">{{ d.category }}</p>
                                        </button>
                                    </div>
                                    <!-- Pagination -->
                                    <div v-if="(msg.total ?? 1) > 1 && step === 'disease'" class="flex items-center gap-2 mt-1">
                                        <button
                                            @click="loadDiseases((msg.page ?? 1) - 1)"
                                            :disabled="(msg.page ?? 1) <= 1"
                                            class="p-1.5 rounded-lg bg-white/10 hover:bg-white/20 disabled:opacity-30 transition"
                                        >
                                            <ChevronLeft class="size-4" />
                                        </button>
                                        <span class="text-xs text-sky-300">{{ msg.page }} / {{ msg.total }}</span>
                                        <button
                                            @click="loadDiseases((msg.page ?? 1) + 1)"
                                            :disabled="(msg.page ?? 1) >= (msg.total ?? 1)"
                                            class="p-1.5 rounded-lg bg-white/10 hover:bg-white/20 disabled:opacity-30 transition"
                                        >
                                            <ChevronRight class="size-4" />
                                        </button>
                                    </div>
                                </template>

                                <!-- Result -->
                                <template v-else-if="msg.type === 'result' && msg.result">
                                    <!-- AI analysis -->
                                    <div class="px-5 py-5 rounded-2xl bg-gradient-to-br from-sky-500/20 to-cyan-500/10 border border-sky-400/20 text-white text-sm leading-relaxed whitespace-pre-line max-w-[90vw] sm:max-w-[60vw] backdrop-blur-sm">
                                        <div class="flex items-center gap-2 text-sky-300 mb-3 pb-3 border-b border-sky-400/20">
                                            <HeartPulse class="size-4" />
                                            <span class="text-xs font-semibold uppercase tracking-wider">AI tahlil natijasi</span>
                                        </div>
                                        {{ msg.result.analysis }}
                                    </div>

                                    <!-- Doctors -->
                                    <div v-if="msg.result.doctors.length" class="space-y-2.5 w-full max-w-[60vw]">
                                        <p class="text-xs font-semibold text-sky-300 px-1 mt-2 uppercase tracking-wider">Tavsiya etilgan shifokorlar</p>
                                        <div
                                            v-for="doc in msg.result.doctors"
                                            :key="doc.id"
                                            class="flex items-start gap-3 px-4 py-4 rounded-2xl bg-white/[0.06] border border-white/10 hover:border-white/20 transition"
                                        >
                                            <img
                                                v-if="doc.photo"
                                                :src="doc.photo"
                                                class="size-10 rounded-full object-cover shrink-0"
                                            />
                                            <div
                                                v-else
                                                class="size-10 rounded-full bg-sky-500/30 flex items-center justify-center text-sky-300 shrink-0"
                                            >
                                                <User class="size-5" />
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="font-semibold text-white text-sm">{{ doc.name }}</p>
                                                <p v-if="doc.specialization" class="text-sky-300 text-xs">{{ doc.specialization }}</p>
                                                <div class="flex flex-wrap gap-2 mt-2">
                                                    <a
                                                        v-if="doc.phone_number"
                                                        :href="`tel:${doc.phone_number}`"
                                                        class="flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-500/20 hover:bg-emerald-500/40 text-emerald-300 text-xs transition"
                                                    >
                                                        <Phone class="size-3" /> {{ doc.phone_number }}
                                                    </a>
                                                    <a
                                                        v-if="doc.location_url"
                                                        :href="doc.location_url"
                                                        target="_blank"
                                                        rel="noopener noreferrer"
                                                        class="flex items-center gap-1 px-2.5 py-1 rounded-lg bg-sky-500/20 hover:bg-sky-500/40 text-sky-300 text-xs transition"
                                                    >
                                                        <MapPin class="size-3" /> Manzil
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Restart -->
                                    <button
                                        @click="restart"
                                        class="flex items-center gap-2 px-5 py-3 rounded-xl bg-gradient-to-r from-sky-500 to-cyan-400 hover:from-sky-400 hover:to-cyan-300 text-white text-sm font-semibold transition-all shadow-xl shadow-sky-500/30 mt-2 hover:scale-[1.02] active:scale-[0.98]"
                                    >
                                        <RotateCcw class="size-4" /> Yangi savol berish
                                    </button>
                                </template>
                            </div>
                        </div>
                    </TransitionGroup>
                    <div ref="chatEnd" class="h-1" />
                </div>

                <!-- Input area -->
                <div class="shrink-0 px-3 sm:px-6 py-3 bg-white/[0.03] backdrop-blur-xl border-t border-white/10">
                    <!-- Text input -->
                    <div v-if="step === 'text_input'" class="flex gap-2 max-w-3xl mx-auto">
                        <textarea
                            v-model="textInput"
                            @keydown.enter.exact.prevent="submitText"
                            rows="2"
                            placeholder="Belgilaringizni yozing... (Enter = yuborish)"
                            class="flex-1 px-4 py-3 rounded-xl bg-white/[0.06] border border-white/10 text-white placeholder-white/30 text-sm resize-none focus:outline-none focus:border-sky-400/50 focus:ring-2 focus:ring-sky-500/20 leading-relaxed transition"
                        />
                        <button
                            @click="submitText"
                            :disabled="!textInput.trim()"
                            class="px-4 rounded-xl bg-gradient-to-r from-sky-500 to-cyan-400 hover:from-sky-400 hover:to-cyan-300 disabled:opacity-30 disabled:cursor-not-allowed text-white transition-all shadow-lg shadow-sky-500/30 hover:scale-[1.02] active:scale-[0.98]"
                        >
                            <Send class="size-5" />
                        </button>
                    </div>

                    <!-- Voice input -->
                    <div v-else-if="step === 'voice_input'" class="flex justify-center items-center gap-4 py-3">
                        <button
                            @click="toggleRecording"
                            :disabled="transcribing"
                            :class="[
                                'flex items-center gap-3 px-8 py-4 rounded-2xl font-semibold text-sm transition-all shadow-lg disabled:opacity-50',
                                isRecording
                                    ? 'bg-red-500 hover:bg-red-400 text-white shadow-red-500/30 animate-pulse ring-4 ring-red-500/20'
                                    : 'bg-gradient-to-r from-sky-500 to-cyan-400 hover:from-sky-400 hover:to-cyan-300 text-white shadow-sky-500/30 hover:scale-[1.02]'
                            ]"
                        >
                            <component :is="isRecording ? StopCircle : Mic" class="size-5" />
                            {{ transcribing ? 'Tahlil qilinmoqda...' : isRecording ? 'To\'xtatish' : 'Gapirish' }}
                        </button>
                    </div>

                    <!-- Hint -->
                    <p v-else class="text-center text-white/30 text-xs py-2">
                        Yuqoridagi variantlardan birini tanlang
                    </p>
                </div>
            </div>

            <!-- Sidebar: banners -->
            <aside v-if="banners.length" class="hidden xl:flex w-80 shrink-0 flex-col border-l border-white/10 bg-white/5 overflow-y-auto">
                <div class="px-4 py-3 border-b border-white/10">
                    <p class="text-xs font-medium text-sky-300 uppercase tracking-wider">Reklama</p>
                </div>
                <div class="p-3 space-y-3">
                    <component
                        :is="b.link ? 'a' : 'div'"
                        v-for="b in banners"
                        :key="b.id"
                        :href="b.link ?? undefined"
                        :target="b.link ? '_blank' : undefined"
                        :rel="b.link ? 'noopener noreferrer' : undefined"
                        class="block rounded-2xl overflow-hidden bg-white/10 border border-white/10 hover:border-sky-400/40 transition cursor-pointer"
                    >
                        <video
                            v-if="b.media_type === 'video' && b.media_url"
                            :src="b.media_url"
                            autoplay muted loop playsinline
                            class="w-full aspect-video object-cover"
                        />
                        <img
                            v-else-if="b.media_url"
                            :src="b.media_url"
                            :alt="b.title"
                            class="w-full aspect-video object-cover"
                        />
                        <div class="px-3 py-2.5">
                            <p class="text-sm font-semibold text-white">{{ b.title }}</p>
                            <p v-if="b.description" class="text-xs text-white/60 mt-0.5 line-clamp-2">{{ b.description }}</p>
                            <div v-if="b.link" class="flex items-center gap-1 text-sky-400 text-xs mt-1.5">
                                <ExternalLink class="size-3" /> Batafsil
                            </div>
                        </div>
                    </component>
                </div>
                <!-- Disclaimer -->
                <div class="mt-auto px-4 py-4 border-t border-white/10">
                    <p class="text-[11px] text-white/30 leading-relaxed">
                        <Cross class="size-3 inline-block mr-0.5" /> Bu platforma tibbiy maslahat o'rnini bosmaydi. Sog'liq muammolari uchun doimo malakali shifokorga murojaat qiling.
                    </p>
                </div>
            </aside>
        </div>
    </div>
</template>

<style scoped>
.msg-enter-active { transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1); }
.msg-enter-from  { opacity: 0; transform: translateY(16px) scale(0.97); }
</style>
