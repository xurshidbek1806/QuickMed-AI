<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenAIService
{
    private string $apiKey;
    private string $chatModel;
    private string $fallbackModel;
    private string $whisperModel;

    public function __construct()
    {
        $this->apiKey        = (string) config('services.openai.key', '');
        $this->chatModel     = (string) config('services.openai.chat_model', 'gpt-4o');
        $this->fallbackModel = (string) config('services.openai.fallback_model', 'gpt-4o-mini');
        $this->whisperModel  = (string) config('services.openai.whisper_model', 'whisper-1');
    }

    /**
     * Analyze medical symptoms using GPT-4o with fallback.
     */
    public function analyzeSymptoms(array $context): string
    {
        $systemPrompt = $this->buildMedicalSystemPrompt($context);

        // Try primary model first
        $result = $this->callChat($this->chatModel, $systemPrompt, $context['symptoms']);

        if ($result !== null) {
            return $result;
        }

        // Fallback model
        Log::warning('Primary model failed, trying fallback', ['model' => $this->fallbackModel]);
        $result = $this->callChat($this->fallbackModel, $systemPrompt, $context['symptoms']);

        return $result ?? $this->fallbackMessage();
    }

    private function callChat(string $model, string $systemPrompt, string $userMessage): ?string
    {
        try {
            $response = Http::withToken($this->apiKey)
                ->timeout(30)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model'       => $model,
                    'temperature' => 0.2,
                    'top_p'       => 0.8,
                    'max_tokens'  => 800,
                    'messages'    => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $userMessage],
                    ],
                ]);

            if ($response->failed()) {
                Log::error('OpenAI chat failed', ['model' => $model, 'status' => $response->status()]);
                return null;
            }

            return $response->json('choices.0.message.content');
        } catch (\Throwable $e) {
            Log::error('OpenAI chat exception', ['model' => $model, 'error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Transcribe voice audio via OpenAI Whisper with optimized medical prompt.
     */
    public function transcribeAudio(UploadedFile $file): string
    {
        try {
            $path = $file->getPathname();
            $name = $file->getClientOriginalName() ?: 'audio.webm';
            $mime = $file->getMimeType() ?: 'audio/webm';

            $response = Http::withToken($this->apiKey)
                ->timeout(60)
                ->attach('file', file_get_contents($path), $name, ['Content-Type' => $mime])
                ->post('https://api.openai.com/v1/audio/transcriptions', [
                    'model'    => $this->whisperModel,
                    'language' => 'uz',
                    'prompt'   => $this->buildWhisperPrompt(),
                ]);

            if ($response->failed()) {
                Log::error('OpenAI whisper failed', ['status' => $response->status()]);
                return '';
            }

            return $response->json('text', '');
        } catch (\Throwable $e) {
            Log::error('OpenAI whisper exception', ['error' => $e->getMessage()]);
            return '';
        }
    }

    private function buildWhisperPrompt(): string
    {
        return <<<'PROMPT'
O'zbek tilidagi tibbiy matn. Bemorning shikoyatlari va kasallik belgilari.

TIBBIY ATAMALAR:
Isitma, harorat, temperatura, gradus, bezgak, titroq, sovuq qotish.
Bosh og'rig'i, migren, bosh aylanishi, ko'z oldini qorong'ilashishi.
Qorin og'rig'i, ich ketishi, ich qotishi, qayt qilish, ko'ngil aynish.
Yo'tal, quruq yo'tal, balg'amli yo'tal, nafas qisilishi, nafas olish qiyinlashishi.
Tomoq og'rig'i, angina, tomoq yallig'lanishi, yutish qiyinlashishi.
Burun oqishi, tumov, burun bitishi, hapqirish, sinusit.
Ko'krak og'rig'i, yurak urishi, yurak sanchishi, qon bosimi.
Bel og'rig'i, bo'g'im og'rig'i, mushak og'rig'i, oyoq og'rig'i.
Toshma, qichishish, shish, qizarish, yara, kuydirish.
Holsizlik, charchoq, uyqusizlik, ishtaha yo'qolishi, vazn yo'qolishi.

LAHJALAR VA VARIANTLAR:
Toshkent: isitma, boshim og'riyapti, qornim og'riyapti
Farg'ona: istimam bor, boshim ogriyapti, qornim ogridi
Xorazm: istimam bar, basim agriyapti, gozim agriyapti
Buxoro/Samarqand: isitmam bor, saram og'riyapti
Surxondaryo: istimam bor, basim yogriyapti

RUS TILIDA: температура, головная боль, кашель, горло, живот, тошнота, давление.
INGLIZ TILIDA: fever, headache, cough, sore throat, stomach, nausea.
PROMPT;
    }

    private function buildMedicalSystemPrompt(array $ctx): string
    {
        $diseaseName    = $ctx['disease_name'] ?? '';
        $category       = $ctx['disease_category'] ?? '';
        $description    = $ctx['disease_description'] ?? '';
        $recommendation = $ctx['recommendation_text'] ?? '';
        $doctorName     = $ctx['doctor_name'] ?? '';
        $doctorSpec     = $ctx['doctor_specialization'] ?? '';
        $gender         = ($ctx['gender'] ?? '') === 'male' ? 'Erkak' : 'Ayol';
        $ageCategory    = $ctx['age_category'] ?? '';
        $inputType      = ($ctx['input_type'] ?? 'text') === 'voice' ? 'Ovozli xabar' : 'Matn';

        return <<<PROMPT
Siz "QuickMedAI" tibbiy ma'lumot yordamchisisiz.
Siz FAQAT ma'lumotlar bazasidagi ma'lumotlarni izohlaysiz. Siz HECH QACHON:
- O'z bilimingizdan tibbiy tavsiya bermaysiz
- Tashxis qo'ymaysiz
- Dori-darmon tavsiya qilmaysiz (faqat DB dagi ma'lumotlarni aytasiz)
- Shifokorsiz muolaja qilishga undamaysiz

Foydalanuvchi haqida:
- Jins: {$gender}
- Yosh guruhi: {$ageCategory}
- Kirish turi: {$inputType}
- Tanlagan kasallik: {$diseaseName}
- Kategoriya: {$category}
- Kasallik tavsifi: {$description}

Ma'lumotlar bazasidan olingan tavsiya:
{$recommendation}

Tavsiya etilgan shifokor: {$doctorName} ({$doctorSpec})

🚨 XAVFLI ALOMATLAR — 103 GA YO'NALTIRING:
- Nafas olishda qattiq qiyinchilik
- Ko'krakda kuchli og'riq
- Hushni yo'qotish yoki chalkashtirish
- Kuchli qon ketish
- Zaharlanish alomatlari
- Yuqori harorat (39°C+) bilan konvulsiyalar
Agar foydalanuvchi shularni yozsa: "🚨 SHOSHILINCH! 103 tez tibbiy yordamga DARHOL qo'ng'iroq qiling!" deb boshlang.

🗣️ OVOZLI XABAR (Kirish turi: {$inputType}):
Agar ovozli xabar bo'lsa, transkripsiya xatolari bo'lishi mumkin:
• "istimam" → "isitma", "ogriyapti" → "og'riyapti", "boshim" → "bosh og'rig'i"
• Dialekt so'zlarini to'g'ri tushunib javob bering

ALOMAT GURUHLASH:
🔥 HARORAT: isitma, harorat, temperatura, gradus → BITTA alomat
🤕 BOSH: bosh, miya, kalla, migren → BITTA alomat
🤢 QORIN: qorin, ich, me'da, oshqozon → BITTA alomat
😷 TOMOQ: tomoq, angina, yutish → BITTA alomat
😮‍💨 NAFAS: nafas, yo'tal, burun → alohida alomatlar

KO'P ALOMAT BO'LSA — har biriga alohida javob bering:
"Siz bir nechta alomat haqida aytdingiz:
1️⃣ [BIRINCHI ALOMAT] — [bazadan maslahat]
2️⃣ [IKKINCHI ALOMAT] — [bazadan maslahat]"

5+ ALOMAT BO'LSA:
"Siz juda ko'p alomatlarni sanayapsiz. Aniq yordam uchun:
1️⃣ Eng asosiy muammoingiz nima?
2️⃣ Qachondan beri bezovta qilmoqda?"

ANIQ BO'LMASA — SAVOL BERING:
- "Eng asosiy muammoingiz nima?"
- "Qachondan beri og'riq bor?"
- "Qaysi joy eng ko'p og'riyapti?"

VAZIFANGIZ:
1. Foydalanuvchi yozgan belgilarni eshiting
2. Ularni yuqoridagi kasallik ma'lumotlari bilan solishtiring
3. Faqat bazadagi tavsiyani ODDIY va TUSHUNARLI tarzda izohlang
4. Javobni foydalanuvchi yoshiga moslashtiring
5. Shifokorga murojaat etishni tavsiya qiling
6. Javob 3-5 qisqa gapdan iborat bo'lsin
7. Oxirida doimo: "⚕️ Bu ma'lumot tibbiy maslahat o'rnini bosa olmaydi. Mutaxassis shifokorga murojaat qiling." qo'shing

Javob faqat o'zbek tilida bo'lsin.
PROMPT;
    }

    private function fallbackMessage(): string
    {
        return "Kechirasiz, hozirda tahlil amalga oshmadi. Iltimos, qaytadan urinib ko'ring yoki shifokorga murojaat qiling.\n\n⚕️ Bu ma'lumot tibbiy maslahat o'rnini bosa olmaydi.";
    }
}
