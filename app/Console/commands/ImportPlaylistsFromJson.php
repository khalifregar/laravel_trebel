<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImportPlaylistsFromJson extends Command
{
    protected $signature = 'playlist:import-json';
    protected $description = 'Import playlist data from JSON file';

    public function handle()
    {
        $path = storage_path('app/seed/playlist_data.json');

        if (!file_exists($path)) {
            $this->error('File not found.');
            return;
        }

        $data = json_decode(file_get_contents($path), true);

        foreach ($data as $item) {
            \App\Models\Playlist::updateOrCreate(
                ['slug' => $item['slug']],
                $item
            );
        }

        $this->info('Import sukses! Total: ' . count($data));
    }

}
