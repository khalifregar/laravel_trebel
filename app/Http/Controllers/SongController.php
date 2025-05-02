<?php

namespace App\Http\Controllers;

use App\Models\Song;
use App\Models\Genre;
use Illuminate\Http\Request;

class SongController extends Controller
{
    public function index(Request $request)
    {
        $query = Song::with('genre');

        if ($request->filled('genre')) {
            $query->whereHas('genre', function ($q) use ($request) {
                $q->where('slug', $request->genre)
                    ->orWhere('name', 'like', '%' . $request->genre . '%');
            });
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('artist', 'like', '%' . $request->search . '%');
            });
        }

        $songs = $query->get()->map(function ($song) {
            return [
                'id' => $song->id,
                'song_id' => $song->song_id,
                'title' => $song->title,
                'artist' => $song->artist,
                'album' => $song->album,
                'duration' => $song->duration,
                'genre_id' => $song->genre->genre_id ?? null,
                'genre_name' => $song->genre->name ?? null,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $songs,
        ]);
    }

    public function store(Request $request, $slug)
    {
        $genre = Genre::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'artist' => 'nullable|string|max:255',
            'album' => 'nullable|string|max:255',
            'duration' => 'nullable|string|max:10',
        ]);

        $song = Song::create([
            'title' => $validated['title'],
            'artist' => $validated['artist'],
            'album' => $validated['album'],
            'duration' => $validated['duration'],
            'genre_id' => $genre->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Lagu berhasil ditambahkan.',
            'data' => [
                'id' => $song->id,
                'song_id' => $song->song_id,
                'title' => $song->title,
                'artist' => $song->artist,
                'album' => $song->album,
                'duration' => $song->duration,
                'genre_id' => $genre->genre_id,
                'genre_name' => $genre->name,
            ]
        ], 201);
    }

    // ✅ PATCH /api/songs/{song_id}
    public function update(Request $request, $song_id)
    {
        $song = Song::where('song_id', $song_id)->firstOrFail();

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'artist' => 'nullable|string|max:255',
            'album' => 'nullable|string|max:255',
            'duration' => 'nullable|string|max:10',
            'genre_id' => 'sometimes|exists:genres,genre_id',
        ]);

        if (isset($validated['genre_id'])) {
            $genre = Genre::where('genre_id', $validated['genre_id'])->firstOrFail();
            $song->genre_id = $genre->id;
        }

        $song->fill($validated)->save();

        return response()->json([
            'success' => true,
            'message' => 'Lagu berhasil diperbarui.',
            'data' => [
                'id' => $song->id,
                'song_id' => $song->song_id,
                'title' => $song->title,
                'artist' => $song->artist,
                'album' => $song->album,
                'duration' => $song->duration,
                'genre_id' => $song->genre->genre_id ?? null,
                'genre_name' => $song->genre->name ?? null,
            ]
        ]);
    }

    // ✅ DELETE /api/songs/{song_id}
    public function destroy($song_id)
    {
        $song = Song::where('song_id', $song_id)->firstOrFail();
        $song->delete();

        return response()->json([
            'success' => true,
            'message' => 'Lagu berhasil dihapus.',
        ]);
    }
}
