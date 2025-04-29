<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class MoodHelper
{
    public static function detectMoodFromPrompt(string $text): ?string
    {
        $keywords = self::loadKeywords();

        foreach ($keywords as $category => $wordList) {
            if (!is_array($wordList)) continue;

            foreach ($wordList as $word) {
                if (str_contains($text, strtolower($word))) {
                    return $category;
                }
            }
        }

        return null;
    }

    public static function loadKeywords(): array
    {
        $path = storage_path('app/ai_keywords.json');
        if (!file_exists($path)) {
            Log::warning('Keyword file not found at ' . $path);
            return [];
        }

        $json = file_get_contents($path);
        return json_decode($json, true) ?? [];
    }
}
