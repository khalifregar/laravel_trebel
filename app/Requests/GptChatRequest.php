<?php

namespace App\Requests;

class GptChatRequest
{
    public static function make(string $systemPrompt, string $userPrompt): array
    {
        return [
            'model'       => 'gpt-3.5-turbo', // ✅ Ganti ini,
            'temperature' => 1.0,
            'top_p'       => 1.0,
            'max_tokens'  => 1024,
            'messages'    => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user',   'content' => $userPrompt],
            ],
        ];
    }
}
