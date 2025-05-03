<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LyricService;

class LyricController extends Controller
{
    protected LyricService $service;

    public function __construct(LyricService $service)
    {
        $this->service = $service;
    }

    public function show(string $songUuid)
    {
        try {
            $lyrics = $this->service->getLyricsForSong($songUuid);
            return response()->json($lyrics);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function store(Request $request, string $songUuid)
    {
        $validated = $request->validate([
            'file_path' => ['required', 'string'], // contoh: hindia/hindia_membasuh.lrc
            'language' => ['nullable', 'string', 'max:10'],
            'version' => ['nullable', 'string'],
        ]);

        try {
            $lyric = $this->service->storeLyricForSong($songUuid, $validated);

            return response()->json([
                'message' => 'Lyric saved successfully',
                'data' => $lyric
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}
