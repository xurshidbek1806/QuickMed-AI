<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Disease;
use App\Models\Recommendation;
use App\Services\OpenAIService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Inertia\Response;

class ChatController extends Controller
{
    public function __construct(private OpenAIService $ai) {}

    public function index(): Response
    {
        $banners = Banner::active()->forPosition('sidebar')->orderBy('sort_order')->get()->map(fn($b) => [
            'id'         => $b->id,
            'title'      => $b->title,
            'description'=> $b->description,
            'media_url'  => $b->media_url,
            'link'       => $b->link,
            'media_type' => $b->media_type,
        ]);

        return Inertia::render('Chat', compact('banners'));
    }

    /**
     * GET /api/diseases?age_category=bola&page=1
     */
    public function getDiseases(Request $request): JsonResponse
    {
        $ageCategory = $request->query('age_category', '');
        $search      = $request->query('search', '');

        $query = Disease::active();

        if ($ageCategory) {
            $query->forAgeCategory($ageCategory);
        }

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        $diseases = $query->orderBy('name')
            ->paginate(12, ['id', 'name', 'category', 'description', 'min_age', 'max_age']);

        return response()->json($diseases);
    }

    /**
     * POST /api/chat/analyze
     */
    public function analyze(Request $request): JsonResponse
    {
        $validated = Validator::make($request->all(), [
            'disease_id'   => ['required', 'uuid', 'exists:diseases,id'],
            'gender'       => ['required', 'in:male,female'],
            'age_category' => ['required', 'string'],
            'symptoms'     => ['required', 'string', 'min:3', 'max:2000'],
        ])->validate();

        $disease = Disease::with(['recommendations.doctor'])->findOrFail($validated['disease_id']);

        $recommendation = $disease->recommendations()
            ->active()
            ->with('doctor')
            ->orderBy('priority', 'desc')
            ->first();

        $context = [
            'disease_name'        => $disease->name,
            'disease_category'    => $disease->category ?? '',
            'disease_description' => $disease->description ?? '',
            'recommendation_text' => $recommendation?->recommendation_text ?? '',
            'doctor_name'         => $recommendation?->doctor?->name ?? '',
            'doctor_specialization' => $recommendation?->doctor?->specialization ?? '',
            'gender'              => $validated['gender'],
            'age_category'        => $validated['age_category'],
            'symptoms'            => $validated['symptoms'],
        ];

        $aiResponse = $this->ai->analyzeSymptoms($context);

        $doctors = $disease->recommendations()
            ->active()
            ->with('doctor')
            ->whereHas('doctor', fn($q) => $q->active())
            ->orderBy('priority', 'desc')
            ->get()
            ->pluck('doctor')
            ->unique('id')
            ->values()
            ->map(fn($d) => [
                'id'             => $d->id,
                'name'           => $d->name,
                'specialization' => $d->specialization,
                'phone_number'   => $d->phone_number,
                'location_url'   => $d->location_url,
                'photo'          => $d->photo ? asset('storage/' . $d->photo) : null,
            ]);

        return response()->json([
            'analysis' => $aiResponse,
            'disease'  => [
                'id'       => $disease->id,
                'name'     => $disease->name,
                'category' => $disease->category,
            ],
            'doctors'  => $doctors,
        ]);
    }

    /**
     * POST /api/chat/voice
     */
    public function transcribeVoice(Request $request): JsonResponse
    {
        $request->validate([
            'audio' => ['required', 'file', 'max:25600', 'mimes:webm,ogg,mp4,wav,m4a,mp3'],
        ]);

        $text = $this->ai->transcribeAudio($request->file('audio'));

        if (empty($text)) {
            return response()->json(['error' => "Ovozni tanib bo'lmadi. Qaytadan urinib ko'ring."], 422);
        }

        return response()->json(['text' => $text]);
    }

    /**
     * GET /api/banners
     */
    public function getBanners(Request $request): JsonResponse
    {
        $position = $request->query('position', 'sidebar');

        $banners = Banner::active()
            ->forPosition($position)
            ->orderBy('sort_order')
            ->get()
            ->map(fn($b) => [
                'id'         => $b->id,
                'title'      => $b->title,
                'description'=> $b->description,
                'media_url'  => $b->media_url,
                'link'       => $b->link,
                'media_type' => $b->media_type,
            ]);

        return response()->json($banners);
    }
}
