<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use App\Requests\GptChatRequest;

class GptService
{
    public function chat(string $prompt, string $userName = 'Sobat', int $userId = null): array
    {
        $memoryKey = "nuno:memory:user:" . ($userId ?? 'guest');
        $lastMood = optional(json_decode(Redis::get($memoryKey), true))['last_mood'] ?? null;

        $systemPrompt = "Kamu adalah TUNO AI (Nuno), asisten musik ramah, jenaka, dan kekinian. Gaya bicaramu santai dan gaul.
        Tugasmu adalah membantu pengguna menemukan lagu atau playlist berdasarkan suasana hati mereka (contoh: sedih, senang, galau, tenang, semangat), atau sekadar ngobrol ringan soal musik.";

        if ($lastMood) {
            $prompt = "Mood sebelumnya adalah: {$lastMood}. Sekarang user bilang: {$prompt}";
        }

        $apiKey = config('services.openai.api_key');
        Log::debug('💡 Current API Key', ['key' => $apiKey]);

        if (empty($apiKey)) {
            Log::error('TUNO GPT Error: Missing OpenAI API key.');
            return $this->errorResponse('Nuno lagi nggak bisa nyambung ke AI. Coba periksa konfigurasi ya.');
        }

        try {
            $requestBody = GptChatRequest::make($systemPrompt, $prompt);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type'  => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', $requestBody);

            $responseData = $response->json();
            Log::debug('🧠 GPT Full JSON Response', ['data' => $responseData]);

            $error = $responseData['error'] ?? null;

            if ($error) {
                if (($error['type'] ?? null) === 'insufficient_quota') {
                    Log::warning('TUNO GPT Warning: Insufficient quota');
                    return $this->errorResponse('🎧 Nuno lagi kehabisan energi buat mikir 😵. Coba lagi nanti ya, atau hubungi admin buat isi ulang!');
                }

                Log::error('TUNO GPT Unknown Error', ['error' => $error]);
                return $this->errorResponse('Nuno lagi bingung nih. Coba lagi nanti ya.');
            }

            $result = $responseData['choices'][0]['message']['content'] ?? null;

            if (!$result) {
                return $this->errorResponse();
            }

            $detectedMood = $this->detectMood($result);

            Redis::setex($memoryKey, 3600, json_encode([
                'last_prompt'   => $prompt,
                'last_response' => $result,
                'last_mood'     => $detectedMood,
            ]));

            if (app()->isLocal()) {
                Log::info('TUNO Prompt', ['prompt' => $prompt]);
                Log::info('TUNO Response', ['response' => $result]);
            }

            return [
                'response' => $result,
                'mood'     => $detectedMood,
            ];
        } catch (\Throwable $e) {
            Log::error('TUNO GPT Error: ' . $e->getMessage());
            return $this->errorResponse();
        }
    }

    private function detectMood(string $text): ?string
    {
        preg_match('/mood\s*[:\-]?\s*(\w+)/i', $text, $matches);
        return $matches[1] ?? null;
    }

    private function errorResponse(string $message = 'Maaf ya, aku lagi error. Coba lagi nanti, ya.'): array
    {
        return [
            'response' => $message,
            'mood'     => null,
        ];
    }
}
