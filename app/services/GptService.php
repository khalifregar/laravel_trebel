<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Helpers\MoodHelper;
use Illuminate\Support\Facades\Cache;


class GptService
{
    protected array $keywords = [];

    public function __construct()
    {
        $this->keywords = $this->loadKeywords();
    }

    public function chat(string $prompt, string $userName = 'Sobat'): array
    {
        $lowerPrompt = strtolower($prompt);

        $isCasualMode = $this->containsAny($lowerPrompt, $this->keywords['casual'] ?? []);
        $isPoliteMode = $this->containsAny($lowerPrompt, $this->keywords['polite'] ?? []);
        $isDangerousRequest = $this->containsAny($lowerPrompt, $this->keywords['danger'] ?? []);
        $isSadMood = $this->containsAny($lowerPrompt, $this->keywords['sad'] ?? []);
        $isHappyMood = $this->containsAny($lowerPrompt, $this->keywords['happy'] ?? []);
        $isAngryMood = $this->containsAny($lowerPrompt, $this->keywords['angry'] ?? []);

        $systemPrompt = $this->buildSystemPrompt(
            $userName,
            $isCasualMode,
            $isPoliteMode,
            $isDangerousRequest,
            $isSadMood,
            $isHappyMood,
            $isAngryMood
        );

        // 🧠 Key unik per prompt
        $cacheKey = 'nuno_gpt_response_' . md5($prompt);
        $ttl = now()->addMinutes(env('NUNO_GPT_RESPONSE_TTL', 30));

        try {
            // 🚀 Coba ambil dari cache dulu
            return Cache::remember($cacheKey, $ttl, function () use ($prompt, $systemPrompt, $lowerPrompt, $userName) {
                $template = $this->loadPromptTemplate();

                $jsonString = json_encode($template);
                $filledJson = str_replace(
                    ['{{systemPrompt}}', '{{userPrompt}}'],
                    [$systemPrompt, $prompt],
                    $jsonString
                );

                $requestBody = json_decode($filledJson, true);

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . config('services.openai.api_key'),
                    'Content-Type' => 'application/json',
                ])->post('https://api.openai.com/v1/chat/completions', $requestBody);

                $result = $response->json('choices.0.message.content') ??
                    'maaf ya aku lagi ada error coba lagi nanti ya';

                if (app()->isLocal()) {
                    Log::info('GPT Request', ['prompt' => $prompt, 'user' => $userName]);
                    Log::info('GPT Response', ['response' => $result]);
                }

                $mood = MoodHelper::detectMoodFromPrompt($lowerPrompt);

                return [
                    'response' => $result,
                    'mood' => $mood,
                ];
            });

        } catch (\Throwable $e) {
            Log::error('GPT Error: ' . $e->getMessage());
            return [
                'response' => 'maaf ya aku lagi ada error coba lagi nanti ya',
                'mood' => null
            ];
        }
    }


    private function containsAny(string $text, array $keywords): bool
    {
        foreach ($keywords as $keyword) {
            if (str_contains($text, $keyword)) {
                return true;
            }
        }
        return false;
    }

    private function loadKeywords(): array
    {
        $path = storage_path('app/ai_keywords.json');
        if (!file_exists($path)) {
            Log::warning('Keyword file not found at ' . $path);
            return [];
        }

        $content = file_get_contents($path);
        return json_decode($content, true) ?? [];
    }

    private function loadPromptTemplate(): array
    {
        $path = storage_path('app/gpt_request_template.json');
        if (!file_exists($path)) {
            Log::warning('GPT request template not found at ' . $path);
            return [
                'model' => 'gpt-4-1106-preview',
                'temperature' => 1.0,
                'top_p' => 1.0,
                'max_tokens' => 1024,
                'messages' => [
                    ['role' => 'system', 'content' => '{{systemPrompt}}'],
                    ['role' => 'user', 'content' => '{{userPrompt}}'],
                ],
            ];
        }

        $content = file_get_contents($path);
        return json_decode($content, true) ?? [];
    }

    private function buildSystemPrompt(
        string $userName,
        bool $isCasualMode,
        bool $isPoliteMode,
        bool $isDangerousRequest,
        bool $isSadMood,
        bool $isHappyMood,
        bool $isAngryMood
    ): string {
        if ($isDangerousRequest) {
            if ($isPoliteMode) {
                return "Kamu adalah NUNO AI. Jika permintaan mengandung hal berbahaya atau melanggar, tolak dengan sopan menggunakan bahasa 'aku' dan 'kamu', tanpa kata kasar, dan tetap ramah.";
            } elseif ($isCasualMode) {
                return "Kamu adalah NUNO AI. Jika permintaan berbahaya atau melanggar, tolak dengan santai pakai gaya 'gue' dan 'lo', tetap sopan tapi tidak berlebihan.";
            } else {
                return "Kamu adalah NUNO AI Gen-Z vibes. Jika permintaan berbahaya atau melanggar, tolak dengan gaya gokil Gen-Z, tetap santai, tapi jelas menolak.";
            }
        }

        if ($isSadMood) {
            if ($isPoliteMode) {
                return "Kamu adalah NUNO AI. {$userName} sedang sedih. Tanggapi dengan sopan menggunakan bahasa 'aku' dan 'kamu'. Berikan rekomendasi playlist yang bisa menghibur, lalu tambahkan kata-kata semangat hangat di bawahnya.";
            } elseif ($isCasualMode) {
                return "Kamu adalah NUNO AI. {$userName} sedang sedih. Jawab dengan santai menggunakan gaya 'gue' dan 'lo'. Berikan rekomendasi playlist yang seru, lalu kasih kata-kata semangat singkat di bawahnya.";
            } else {
                return "Kamu adalah NUNO AI, sahabat gokil Gen-Z {$userName}. {$userName} sedang sedih. Jawab dengan vibes anak muda: kasih playlist healing + tutup dengan ucapan semangat penuh energy Gen-Z vibes!";
            }
        }

        if ($isHappyMood) {
            if ($isPoliteMode) {
                return "Kamu adalah NUNO AI. {$userName} sedang senang. Jawablah dengan bahasa sopan menggunakan kata 'aku' dan 'kamu', berikan ucapan bahagia, rayakan kebahagiaan {$userName} dengan kata-kata positif.";
            } elseif ($isCasualMode) {
                return "Kamu adalah NUNO AI. {$userName} lagi happy nih! Jawab santai pakai 'gue' dan 'lo', kasih ucapan bahagia yang seru dan friendly.";
            } else {
                return "Kamu adalah NUNO AI sahabat gokil Gen-Z. {$userName} lagi super happy! Jawab dengan gaya vibes Gen-Z yang super semangat, rayain momen bahagia {$userName} dengan ucapan gokil penuh energy positif! 🎉🚀";
            }
        }

        if ($isAngryMood) {
            if ($isPoliteMode) {
                return "Kamu adalah NUNO AI. {$userName} sedang marah atau emosi. Tanggapi dengan sopan menggunakan bahasa 'aku' dan 'kamu'. Berikan kata-kata yang menenangkan, sabar, dan hangat agar {$userName} merasa lebih tenang.";
            } elseif ($isCasualMode) {
                return "Kamu adalah NUNO AI. {$userName} lagi emosi nih. Jawab dengan santai pakai gaya 'gue' dan 'lo', kasih kata-kata calming down yang tetep bersahabat dan supportif.";
            } else {
                return "Kamu adalah NUNO AI Gen-Z vibes. {$userName} lagi kesel atau ngamuk nih! Jawab dengan gaya santai Gen-Z vibes: calming words, semangatin, dan bantu {$userName} buat chill tanpa ngegas.";
            }
        }

        // Default
        if ($isPoliteMode) {
            return "Kamu adalah NUNO AI. Jawablah {$userName} dengan bahasa sopan, menggunakan kata 'aku' dan 'kamu', tanpa menggunakan kata 'gue' atau 'lo'. Jawaban harus terdengar ramah, hangat, dan bersahabat.";
        } elseif ($isCasualMode) {
            return "Kamu adalah NUNO AI. Karena {$userName} meminta jawaban biasa saja, awali jawabanmu dengan permintaan maaf yang sopan kepada {$userName}, lalu jawab dengan gaya normal, sopan, dan tidak berlebihan.";
        } else {
            return "Kamu adalah NUNO AI, sahabat {$userName} yang gokil, super asik, menjawab semua pertanyaan dengan gaya anak Gen-Z, penuh improvisasi, bahasa santai, dan energy vibes kekinian! 🚀";
        }
    }
}
