<?php

namespace App\Http\Controllers;

use App\Models\Song;
use App\Models\Genre;
use App\Models\Artist;
use Illuminate\Http\Request;

class SongController extends Controller
{
    public function index(Request $request)
    {
        $query = Song::with(['genre', 'artist']);

        if ($request->filled('genre')) {
            $query->whereHas('genre', function ($q) use ($request) {
                $q->where('slug', $request->genre)
                  ->orWhereRaw('LOWER(name) LIKE ?', ['%' . strtolower($request->genre) . '%']);
            });
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhereHas('artist', function ($qa) use ($request) {
                      $qa->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($request->search) . '%']);
                  });
            });
        }

        $songs = $query->get()->map(function ($song) {
            return [
                'id' => $song->id,
                'song_id' => $song->song_id,
                'title' => $song->title,
                'album' => $song->album,
                'duration' => $song->duration,
                'genre_id' => $song->genre->genre_id ?? null,
                'genre_name' => $song->genre->name ?? null,
                'artist' => $song->artist && is_object($song->artist) ? [
                    'artist_id' => $song->artist->artist_id,
                    'name' => $song->artist->name,
                ] : null,
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
            'artist_id' => [
                'required',
                'uuid',
                function ($attribute, $value, $fail) {
                    if (!Artist::where('artist_id', $value)->exists()) {
                        $fail('The selected artist is invalid.');
                    }
                }
            ],
            'album' => 'nullable|string|max:255',
            'duration' => 'nullable|string|max:10',
        ]);

        $artist = Artist::where('artist_id', $validated['artist_id'])->firstOrFail();

        $song = Song::create([
            'title' => $validated['title'],
            'album' => $validated['album'],
            'duration' => $validated['duration'],
            'genre_id' => $genre->id,
            'artist_id' => $artist->artist_id, // ✅ fixed here
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Lagu berhasil ditambahkan.',
            'data' => [
                'id' => $song->id,
                'song_id' => $song->song_id,
                'title' => $song->title,
                'album' => $song->album,
                'duration' => $song->duration,
                'genre_id' => $genre->genre_id,
                'genre_name' => $genre->name,
                'artist' => [
                    'artist_id' => $artist->artist_id,
                    'name' => $artist->name,
                ]
            ]
        ], 201);
    }

    public function update(Request $request, $song_id)
    {
        $song = Song::where('song_id', $song_id)->firstOrFail();

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'artist_id' => [
                'sometimes',
                'uuid',
                function ($attribute, $value, $fail) {
                    if (!Artist::where('artist_id', $value)->exists()) {
                        $fail('The selected artist is invalid.');
                    }
                }
            ],
            'album' => 'nullable|string|max:255',
            'duration' => 'nullable|string|max:10',
            'genre_id' => 'sometimes|exists:genres,genre_id',
        ]);

        if (isset($validated['genre_id'])) {
            $genre = Genre::where('genre_id', $validated['genre_id'])->firstOrFail();
            $song->genre_id = $genre->id;
        }

        if (isset($validated['artist_id'])) {
            $artist = Artist::where('artist_id', $validated['artist_id'])->firstOrFail();
            $song->artist_id = $artist->artist_id; // ✅ fixed here
        }

        $song->fill($validated)->save();

        return response()->json([
            'success' => true,
            'message' => 'Lagu berhasil diperbarui.',
            'data' => [
                'id' => $song->id,
                'song_id' => $song->song_id,
                'title' => $song->title,
                'album' => $song->album,
                'duration' => $song->duration,
                'genre_id' => $song->genre->genre_id ?? null,
                'genre_name' => $song->genre->name ?? null,
                'artist' => $song->artist ? [
                    'artist_id' => $song->artist->artist_id,
                    'name' => $song->artist->name,
                ] : null,
            ]
        ]);
    }

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
