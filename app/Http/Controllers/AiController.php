<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\GptService;
use App\Helpers\MoodHelper;
use App\Models\AiHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB; // Tambahkan ini di atas

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

            // Ambil mood terakhir dari cache
            $lastMood = $userId ? Cache::get("nuno_last_mood_user_{$userId}") : null;

            // Panggil GPT service
            $ai = $this->gptService->chat($prompt, $userName);
            $mood = $ai['mood'];
            $response = $ai['response'];

            // Simpan mood ke cache (1 jam)
            if ($userId && $mood) {
                Cache::put("nuno_last_mood_user_{$userId}", $mood, now()->addHour());
            }

            // Simpan histori ke DB
            if ($userId) {
                AiHistory::create([
                    'user_id' => $userId,
                    'prompt' => $prompt,
                    'response' => $response,
                    'mood' => $mood, // ← mood dari GPTService
                ]);
            }

            // Buat greeting berdasarkan mood sebelumnya
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

    // Tambahkan method baru di bawah method recommend
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
        return match ($lastMood) {
            'sad' => "😢 Hai {$userName}, semoga kamu udah merasa lebih baik ya. Yuk kita ngobrol lagi bareng NUNO AI~\n\n",
            'happy' => "✨ Hai {$userName}, masih happy kan? Seru banget loh ngobrol sama kamu kemarin! 😄\n\n",
            'angry' => "😤 Hai {$userName}, semoga sekarang udah lebih chill ya. Gue di sini buat nemenin lo lagi~\n\n",
            default => $this->randomGreeting($userName),
        };
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
