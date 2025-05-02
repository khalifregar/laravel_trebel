<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GptService;
use App\Models\AiHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

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
            $userId = $user?->id;
            $userName = $user?->name ?? 'Sobat';
            $prompt = $request->input('prompt');

            // 🔥 Panggil GPT Service
            $ai = $this->gptService->chat($prompt, $userName, $userId);
            $mood = $ai['mood'] ?? null;
            $response = $ai['response'];

            // 🧠 Simpan history ke DB
            if ($userId) {
                AiHistory::create([
                    'user_id' => $userId,
                    'prompt' => $prompt,
                    'response' => $response,
                    'mood' => $mood,
                ]);
            }

            // 👋 Tambahkan greeting friendly
            $greeting = $this->generateGreeting($userName, $mood);

            return response()->json([
                'success' => true,
                'data' => $greeting . $response,
                'mood' => $mood,
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

    public function greeting(Request $request)
    {
        try {
            $user = Auth::user();
            $userId = $user?->id;
            $userName = $user?->name ?? 'Sobat';

            // Ambil mood terakhir user dari Redis cache (optional)
            $lastMood = null;
            if ($userId) {
                $lastMood = Cache::get("nuno:memory:user:{$userId}");
                $lastMood = $lastMood ? json_decode($lastMood, true)['last_mood'] ?? null : null;
            }

            $greeting = $this->generateGreeting($userName, $lastMood);

            return response()->json([
                'success' => true,
                'greeting' => $greeting,
                'mood' => $lastMood,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil greeting',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    private function generateGreeting(string $userName, ?string $lastMood = null): string
    {
        if ($lastMood) {
            return "😄 Hai {$userName}! Kamu lagi ngerasa {$lastMood} ya? Nih ada sesuatu buat kamu dari NUNO AI:\n\n";
        }

        return $this->randomGreeting($userName);
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
