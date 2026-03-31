<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenAIService
{
    private string $apiKey;
    private string $chatModel;
    private string $whisperModel;

    public function __construct()
    {
        $this->apiKey       = (string) config('services.openai.key', '');
        $this->chatModel    = (string) config('services.openai.chat_model', 'gpt-4o');
        $this->whisperModel = (string) config('services.openai.whisper_model', 'whisper-1');
    }

    /**
     * Analyze medical symptoms using GPT-4o.
     * AI is strictly an interpreter of database content — never diagnoses or generates advice.
     */
    public function analyzeSymptoms(array $context): string
    {
        $systemPrompt = $this->buildMedicalSystemPrompt($context);

        try {
            $response = Http::withToken($this->apiKey)
                ->timeout(30)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model'       => $this->chatModel,
                    'temperature' => 0.2,
                    'max_tokens'  => 800,
                    'messages'    => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $context['symptoms']],
                    ],
                ]);

            if ($response->failed()) {
                Log::error('OpenAI chat failed', ['status' => $response->status(), 'body' => $response->body()]);
                return $this->fallbackMessage();
            }

            return $response->json('choices.0.message.content', $this->fallbackMessage());
        } catch (\Throwable $e) {
            Log::error('OpenAI chat exception', ['error' => $e->getMessage()]);
            return $this->fallbackMessage();
        }
    }

    /**
     * Transcribe voice audio via OpenAI Whisper.
     */
    public function transcribeAudio(UploadedFile $file): string
    {
        try {
            $path    = $file->getPathname();
            $name    = $file->getClientOriginalName() ?: 'audio.webm';
            $mime    = $file->getMimeType() ?: 'audio/webm';

            $response = Http::withToken($this->apiKey)
                ->timeout(60)
                ->attach('file', file_get_contents($path), $name, ['Content-Type' => $mime])
                ->post('https://api.openai.com/v1/audio/transcriptions', [
                    'model'    => $this->whisperModel,
                    'language' => 'uz',
                    'prompt'   => "O'zbek tilida, shuningdek rus tilida tibbiy belgilar va kasallik nomlari bo'lishi mumkin.",
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

    private function buildMedicalSystemPrompt(array $ctx): string
    {
        $diseaseName    = $ctx['disease_name'] ?? '';
        $category       = $ctx['disease_category'] ?? '';
        $description    = $ctx['disease_description'] ?? '';
        $recommendation = $ctx['recommendation_text'] ?? '';
        $doctorName     = $ctx['doctor_name'] ?? '';
        $doctorSpec     = $ctx['doctor_specialization'] ?? '';
        $gender         = $ctx['gender'] === 'male' ? 'Erkak' : 'Ayol';
        $ageCategory    = $ctx['age_category'] ?? '';

        return <<<PROMPT
Siz "QuickMedAI" tibbiy ma'lumot yordamchisisiz. Siz faqat ma'lumotlar bazasidagi ma'lumotlarni
izohlaysiz va foydalanuvchiga tushunarli tarzda yetkazasiz. Siz HECH QACHON:
- O'z bilimingizdan tibbiy tavsiya bermaysiz
- Tashxis qo'ymaysiz
- Dori-darmon tavsiya qilmaysiz (faqat DB dagi ma'lumotlarni aytasiz)
- Shifokorsiz muolaja qilishga undamaysiz

Foydalanuvchi haqida:
- Jins: {$gender}
- Yosh guruhi: {$ageCategory}
- Tanlagan kasallik/holat: {$diseaseName}
- Kategoriya: {$category}
- Kasallik tavsifi: {$description}

Ma'lumotlar bazasidan olingan tavsiya:
{$recommendation}

Tavsiya etilgan shifokor: {$doctorName} ({$doctorSpec})

Vazifangiz:
1. Foydalanuvchi yozgan belgilarni eshiting
2. Ularni yuqoridagi kasallik ma'lumotlari bilan solishtiring
3. Faqat yuqoridagi ma'lumotlar bazasidagi tavsiyani UZBEK TILIDA oddiy va tushunarli tarzda izohlang
4. Shifokorga murojaat etishni tavsiya qiling
5. Javob 3-5 qisqa gapdan iborat bo'lsin
6. Oxirida doimo: "⚕️ Bu ma'lumot tibbiy maslahat sifatida qabul qilinmasin. Mutaxassis shifokorga murojaat qiling." qo'shing

Javob faqat o'zbek tilida bo'lsin.
PROMPT;
    }

    private function fallbackMessage(): string
    {
        return "Kechirasiz, hozirda tahlil amalga oshmadi. Iltimos, qaytadan urinib ko'ring yoki shifokorga murojaat qiling.\n\n⚕️ Bu ma'lumot tibbiy maslahat sifatida qabul qilinmasin.";
    }
}
