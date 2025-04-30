<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\GptService;
use App\Helpers\MoodHelper;
use App\Models\AiHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AiController extends Controller
{
    protected GptService $gptService;

    public function __construct(GptService $gptService)
    {
        $this->gptService = $gptService;
    }

    public function recommend(Request $request)
    {
        $request->validate([
            'prompt' => ['required', 'string', 'max:1000'],
        ]);

        try {
            $user = Auth::user();
            $userId = $user?->id ?? null;
            $userName = $user?->name ?? 'Sobat';
            $prompt = $request->input('prompt');
            $lowerPrompt = strtolower($prompt);
            $lastMoodKey = $userId ? "nuno_last_mood_user_{$userId}" : null;
            $ttl = now()->addHours(env('NUNO_MOOD_CACHE_TTL', 1));

            $lastMood = null;
            if ($lastMoodKey) {
                $lastMood = Cache::get($lastMoodKey);
                if (!$lastMood) {
                    $lastMood = AiHistory::where('user_id', $userId)
                        ->whereNotNull('mood')
                        ->latest()
                        ->value('mood');

                    if ($lastMood) {
                        Cache::put($lastMoodKey, $lastMood, $ttl);
                    }
                }
            }

            if ($userId) {
                $todayMoodKey = "nuno_today_mood_user_{$userId}";

                $dominantTodayMood = Cache::remember($todayMoodKey, now()->addHours(24), function () use ($userId) {
                    return AiHistory::where('user_id', $userId)
                        ->whereDate('created_at', now())
                        ->whereNotNull('mood')
                        ->select('mood', DB::raw('count(*) as total'))
                        ->groupBy('mood')
                        ->orderByDesc('total')
                        ->value('mood'); // cuma ambil mood terbanyak
                });
            }

            $ai = $this->gptService->chat($prompt, $userName);
            $mood = $ai['mood'];
            $response = $ai['response'];

            if ($lastMoodKey && $mood) {
                Cache::put($lastMoodKey, $mood, $ttl);
            }

            if ($userId) {
                AiHistory::create([
                    'user_id' => $userId,
                    'prompt' => $prompt,
                    'response' => $response,
                    'mood' => $mood,
                ]);
            }

            $greeting = $this->generateGreeting($userName, $lastMood);

            return response()->json([
                'success' => true,
                'data' => $greeting . $response,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses permintaan AI',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function moodTracking(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                ], 401);
            }

            $histories = AiHistory::where('user_id', $user->id)
                ->whereNotNull('mood')
                ->select(
                    DB::raw('DATE(created_at) as date'),
                    'mood',
                    DB::raw('count(*) as total')
                )
                ->groupBy('date', 'mood')
                ->orderBy('date', 'desc')
                ->get();

            $result = [];
            foreach ($histories as $history) {
                $date = $history->date;
                $mood = $history->mood;
                $total = $history->total;
                if (!isset($result[$date])) {
                    $result[$date] = [];
                }
                $result[$date][$mood] = $total;
            }

            return response()->json([
                'success' => true,
                'data' => $result,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses tracking mood',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    private function generateGreeting(string $userName, ?string $lastMood): string
    {
        $userId = Auth::id();
        $dominantTodayMood = $userId ? Cache::get("nuno_today_mood_user_{$userId}") : null;

        if ($dominantTodayMood && $dominantTodayMood !== $lastMood) {
            return "🔄 Hari ini kamu lebih sering {$dominantTodayMood}, padahal tadi kamu {$lastMood}. Seru ya liat mood kamu berubah! 😄\n\n";
        }

        $greeting = \App\Helpers\MoodGreetingHelper::getGreeting($lastMood, $userName);

        return $greeting ? $greeting . "\n\n" : $this->randomGreeting($userName);
    }




    private function randomGreeting(string $userName): string
    {
        $greetingOptions = [
            "👋 Yo {$userName}! Gasken, bareng NUNO AI si partner setia lo! 🚀",
            "😎 Halo {$userName}! Siap nemenin lo seru-seruan bareng NUNO AI nih! 🎶",
            "🔥 Wazzup {$userName}! Bareng NUNO AI, hidup lo auto vibes! ✨",
            "🎧 Hai hai {$userName}! NUNO AI di sini, siap buat hari lo makin asik! 🚀",
            "🚀 Yo {$userName}! Gaspol bareng NUNO AI, sahabat terbaik lo seantero jagad!",
            "👾 Hey {$userName}! Santai aja, ada NUNO AI yang siap gasin semua keresahan lo!",
        ];

        return $greetingOptions[array_rand($greetingOptions)] . "\n\n";
    }
}
