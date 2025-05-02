<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Song;

class SongSeeder extends Seeder
{
    public function run(): void
    {
        Song::insert([
            ['title' => 'Lagu 1', 'artist' => 'Artis A', 'album' => 'Album A', 'duration' => '03:21'],
            ['title' => 'Lagu 2', 'artist' => 'Artis B', 'album' => 'Album B', 'duration' => '02:59'],
            ['title' => 'Lagu 3', 'artist' => 'Artis C', 'album' => 'Album C', 'duration' => '04:10'],
        ]);

        $this->call([
            SongSeeder::class,
        ]);
    }
}
