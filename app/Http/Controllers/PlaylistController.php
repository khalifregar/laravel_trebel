<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use App\Models\Genre;
use App\Models\Song;
use Illuminate\Http\Request;

class PlaylistController extends Controller
{
    public function index()
    {
        $playlists = Playlist::with(['genre', 'songs'])->get();

        $data = $playlists->map(function ($playlist) {
            return [
                'playlist_id' => $playlist->playlist_id,
                'title' => $playlist->title,
                'genre_id' => $playlist->genre->genre_id,
                'genre_name' => $playlist->genre->name,
                'songs' => $playlist->songs->map(function ($song) {
                    return [
                        'song_id' => $song->song_id,
                        'title' => $song->title,
                        'artist' => $song->artist,
                        'album' => $song->album,
                        'duration' => $song->duration,
                    ];
                }),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'genre_id' => 'required|exists:genres,genre_id',
            'songs' => 'required|array',
            'songs.*' => 'exists:songs,song_id',
        ]);

        $genre = Genre::where('genre_id', $validated['genre_id'])->firstOrFail();

        // Cek apakah playlist dengan title & genre sama sudah ada
        $existing = Playlist::where('title', $validated['title'])
            ->where('genre_id', $genre->id)
            ->first();

        if ($existing) {
            $existingSongIds = $existing->songs()->pluck('songs.id')->toArray();
            $newSongIds = Song::whereIn('song_id', $validated['songs'])->pluck('id')->toArray();

            $toAdd = array_diff($newSongIds, $existingSongIds);

            if (empty($toAdd)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Semua lagu sudah ada di playlist ini.'
                ], 409);
            }

            $merged = array_unique(array_merge($existingSongIds, $toAdd));

            if (count($merged) > 60) {
                return response()->json([
                    'success' => false,
                    'message' => 'Menambahkan lagu melebihi batas 60 lagu di playlist.'
                ], 422);
            }

            $existing->songs()->sync($merged);
            $existing->load(['genre', 'songs']);

            return response()->json([
                'success' => true,
                'message' => 'Playlist sudah ada. Lagu-lagu berhasil ditambahkan.',
                'data' => [
                    'playlist_id' => $existing->playlist_id,
                    'title' => $existing->title,
                    'genre_id' => $existing->genre->genre_id,
                    'genre_name' => $existing->genre->name,
                    'songs' => $existing->songs->map(function ($song) {
                        return [
                            'song_id' => $song->song_id,
                            'title' => $song->title,
                            'artist' => $song->artist,
                            'album' => $song->album,
                            'duration' => $song->duration,
                        ];
                    }),
                    'created_at' => $existing->created_at,
                    'updated_at' => $existing->updated_at,
                ]
            ], 200);
        }

        if (count($validated['songs']) > 60) {
            return response()->json([
                'success' => false,
                'message' => 'Playlist tidak boleh memiliki lebih dari 60 lagu.'
            ], 422);
        }

        $playlist = Playlist::create([
            'title' => $validated['title'],
            'genre_id' => $genre->id,
        ]);

        $songIds = Song::whereIn('song_id', $validated['songs'])->pluck('id');
        $playlist->songs()->sync($songIds);
        $playlist->load(['genre', 'songs']);

        return response()->json([
            'success' => true,
            'message' => 'Playlist berhasil dibuat.',
            'data' => [
                'playlist_id' => $playlist->playlist_id,
                'title' => $playlist->title,
                'genre_id' => $playlist->genre->genre_id,
                'genre_name' => $playlist->genre->name,
                'songs' => $playlist->songs->map(function ($song) {
                    return [
                        'song_id' => $song->song_id,
                        'title' => $song->title,
                        'artist' => $song->artist,
                        'album' => $song->album,
                        'duration' => $song->duration,
                    ];
                }),
                'created_at' => $playlist->created_at,
                'updated_at' => $playlist->updated_at,
            ]
        ], 201);
    }

    public function show($playlist_id)
    {
        $playlist = Playlist::with(['genre', 'songs'])
            ->where('playlist_id', $playlist_id)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => [
                'playlist_id' => $playlist->playlist_id,
                'title' => $playlist->title,
                'genre_id' => $playlist->genre->genre_id,
                'genre_name' => $playlist->genre->name,
                'songs' => $playlist->songs->map(function ($song) {
                    return [
                        'song_id' => $song->song_id,
                        'title' => $song->title,
                        'artist' => $song->artist,
                        'album' => $song->album,
                        'duration' => $song->duration,
                    ];
                }),
            ]
        ]);
    }

    public function update(Request $request, $playlist_id)
    {
        $playlist = Playlist::where('playlist_id', $playlist_id)->firstOrFail();

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'genre_id' => 'sometimes|exists:genres,genre_id',
            'songs' => 'sometimes|array',
            'songs.*' => 'exists:songs,song_id',
        ]);

        if (isset($validated['songs']) && count($validated['songs']) > 60) {
            return response()->json([
                'success' => false,
                'message' => 'Playlist tidak boleh memiliki lebih dari 60 lagu.'
            ], 422);
        }

        if (isset($validated['genre_id'])) {
            $genre = Genre::where('genre_id', $validated['genre_id'])->firstOrFail();
            $playlist->genre_id = $genre->id;
        }

        if (isset($validated['title'])) {
            $playlist->title = $validated['title'];
        }

        $playlist->save();

        if (isset($validated['songs'])) {
            $songIds = Song::whereIn('song_id', $validated['songs'])->pluck('id');
            $playlist->songs()->sync($songIds);
        }

        return response()->json([
            'success' => true,
            'message' => 'Playlist berhasil diperbarui.'
        ]);
    }

    public function destroy($playlist_id)
    {
        $playlist = Playlist::where('playlist_id', $playlist_id)->firstOrFail();
        $playlist->delete();

        return response()->json([
            'success' => true,
            'message' => 'Playlist berhasil dihapus.'
        ]);
    }
}
