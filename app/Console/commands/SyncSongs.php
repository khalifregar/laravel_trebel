<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Song;
use App\Models\Genre;

class SyncSongs extends Command
{
    protected $signature = 'sync:songs';
    protected $description = 'Sync songs from songs.json into the database';

    public function handle()
    {
        $path = storage_path('app/data/songs/songs.json');

        if (!file_exists($path)) {
            $this->error("❌ File not found at: $path");
            return;
        }

        $json = file_get_contents($path);
        $this->info('✅ File loaded successfully!');
        $this->line('🔍 Preview: ' . substr($json, 0, 100));

        $data = json_decode($json, true);

        if (!is_array($data)) {
            $this->error('❌ Invalid JSON format.');
            return;
        }

        Song::query()->delete();


        foreach ($data as $item) {
            // ✅ cari genre berdasarkan nama
            $genre = Genre::where('name', $item['genre'])->first();

            if (!$genre) {
                $this->warn("⚠️ Genre '{$item['genre']}' not found. Skipped: {$item['title']}");
                continue;
            }

            Song::create([
                'title' => $item['title'],
                'artist' => $item['artist'] ?? null,
                'album' => $item['album'] ?? null,
                'duration' => $item['duration'] ?? null,
                'genre_id' => $genre->id, // pakai ID internal
            ]);
        }

        $this->info('✅ Songs synced successfully from JSON.');
    }
}
