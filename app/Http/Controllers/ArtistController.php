<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\Playlist;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArtistController extends Controller
{
    // GET /api/artists
    public function index()
    {
        return response()->json([
            'data' => Artist::with(['playlists.genre', 'playlists.songs.artist'])->get()
        ]);
    }

    // GET /api/artists/{artist_id}
    public function show($artist_id)
    {
        $artist = Artist::with(['playlists.genre', 'playlists.songs.artist'])
            ->where('artist_id', $artist_id)
            ->first();

        if (!$artist) {
            return response()->json(['message' => 'Artist not found'], 404);
        }

        return response()->json([
            'data' => [
                'artist_id' => $artist->artist_id,
                'name' => $artist->name,
                'title' => $artist->title,
                'image_url' => $artist->image_url,
                'bio' => $artist->bio,
                'playlists' => $artist->playlists->map(function ($playlist) {
                    return [
                        'playlist_id' => $playlist->playlist_id,
                        'title' => $playlist->title,
                        'genre_id' => $playlist->genre->genre_id ?? null,
                        'genre_name' => $playlist->genre->name ?? null,
                        'songs' => $playlist->songs->map(function ($song) {
                            return [
                                'song_id' => $song->song_id,
                                'title' => $song->title,
                                'album' => $song->album,
                                'duration' => $song->duration,
                                'artist' => [
                                    'artist_id' => $song->artist->artist_id ?? null,
                                    'name' => $song->artist->name ?? null,
                                ]
                            ];
                        })
                    ];
                })
            ]
        ]);
    }

    // POST /api/artists
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'title' => 'nullable|string',
            'image_url' => 'nullable|url',
            'bio' => 'nullable|string',
        ]);

        $artist = Artist::create($validated);

        return response()->json(['data' => $artist], 201);
    }

    // PATCH /api/artists/{artist_id}
    public function update(Request $request, $artist_id)
    {
        $artist = Artist::where('artist_id', $artist_id)->firstOrFail();

        $artist->update($request->only(['name', 'title', 'image_url', 'bio']));

        return response()->json(['data' => $artist]);
    }

    // DELETE /api/artists/{artist_id}
    public function destroy($artist_id)
    {
        $artist = Artist::where('artist_id', $artist_id)->firstOrFail();
        $artist->delete();

        return response()->json(['message' => 'Artist deleted']);
    }

    // ✅ POST /api/artists/{artist_id}/playlists
    public function storePlaylist(Request $request, $artist_id)
    {
        $artist = Artist::where('artist_id', $artist_id)->with('songs')->firstOrFail();

        $validated = $request->validate([
            'title' => 'required|string',
            'genre_id' => 'nullable|exists:genres,genre_id',
        ]);

        // Ambil genre dari lagu pertama kalau tidak dikirim
        $genreId = $validated['genre_id'] ?? $artist->songs->first()?->genre_id;

        if (!$genreId) {
            return response()->json([
                'message' => 'Genre tidak tersedia dari input atau lagu artis.',
                'error' => true,
            ], 422);
        }

        // Buat playlist baru
        $playlist = new Playlist([
            'playlist_id' => (string) Str::uuid(),
            'title' => $validated['title'],
            'genre_id' => $genreId,
        ]);

        $playlist->artist_id = $artist->artist_id; // ✅ assign UUID
        $playlist->save();

        // ✅ Attach semua lagu artis ke playlist (pakai ID integer dari lagu)
        $playlist->songs()->sync($artist->songs->pluck('id'));

        return response()->json([
            'data' => [
                'playlist_id' => $playlist->playlist_id,
                'title' => $playlist->title,
                'genre_id' => $playlist->genre_id,
                'artist_id' => $playlist->artist_id,
                'created_at' => $playlist->created_at,
                'updated_at' => $playlist->updated_at,
                'songs' => $playlist->songs->map(function ($song) {
                    return [
                        'song_id' => $song->song_id,
                        'title' => $song->title,
                        'album' => $song->album,
                        'duration' => $song->duration,
                        'artist' => [
                            'artist_id' => $song->artist->artist_id ?? null,
                            'name' => $song->artist->name ?? null,
                        ]
                    ];
                })
            ]
        ]);

    }



}
