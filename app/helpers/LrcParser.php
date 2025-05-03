<?php

namespace App\Helpers;

class LrcParser
{
    /**
     * Parse content dari file .lrc menjadi array dengan timestamp dan teks.
     *
     * Contoh output:
     * [
     *   ['time' => 12.0, 'text' => 'Aku ingin begini'],
     *   ['time' => 15.0, 'text' => 'Aku ingin begitu'],
     * ]
     */
    public static function parse(string $lrc): array
    {
        $lines = explode("\n", $lrc);
        $result = [];

        foreach ($lines as $line) {
            // Match format [mm:ss.xx] atau [m:ss.xx]
            if (preg_match('/\[(\d+):(\d+\.\d+)\](.*)/', $line, $matches)) {
                $minutes = (int) $matches[1];
                $seconds = (float) $matches[2];
                $text = trim($matches[3]);

                $time = round($minutes * 60 + $seconds, 2);

                $result[] = [
                    'time' => $time,
                    'text' => $text,
                ];
            }
        }

        return $result;
    }
}
