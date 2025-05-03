<?php

namespace App\Services;

use App\Models\Song;
use App\Models\Lyric;
use Illuminate\Support\Facades\Storage;
use App\Helpers\LrcParser;
use Illuminate\Support\Str;

class LyricService
{
    /**
     * Ambil dan parse lirik berdasarkan UUID lagu.
     *
     * @param string $songUuid
     * @return array
     *
     * @throws \Exception
     */
    public function getLyricsForSong(string $songUuid): array
    {
        $song = Song::where('song_id', $songUuid)->firstOrFail();

        $lyric = $song->lyric;

        if (!$lyric || !Storage::exists("data/songs/{$lyric->file_path}")) {
            throw new \Exception('Lyric file not found');
        }

        $raw = Storage::get("data/songs/{$lyric->file_path}");

        return LrcParser::parse($raw);
    }

    /**
     * Simpan lyric baru untuk lagu tertentu.
     *
     * @param string $songUuid
     * @param array $data (file_path, language, version)
     * @return Lyric
     *
     * @throws \Exception
     */
    public function storeLyricForSong(string $songUuid, array $data): Lyric
    {
        $song = Song::where('song_id', $songUuid)->firstOrFail();

        if ($song->lyric) {
            throw new \Exception('Lyric already exists for this song');
        }

        $filePath = $data['file_path'];

        if (!Storage::exists("data/songs/{$filePath}")) {
            throw new \Exception("Lyric file not found at: data/songs/{$filePath}");
        }

        return $song->lyric()->create([
            'file_path' => $filePath,
            'language' => $data['language'] ?? 'id',
            'version' => $data['version'] ?? null,
        ]);
    }
}
