<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GenreController extends Controller
{
    // GET /genres
    public function index()
    {
        $genres = Genre::select('id', 'genre_id', 'name', 'slug')->get()
            ->map(function ($genre) {
                return [
                    'id' => $genre->id,
                    'genre_id' => $genre->genre_id,
                    'name' => $genre->name,
                    'slug' => $genre->slug,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $genres
        ]);
    }

    // POST /genres
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:genres,name',
        ]);

        $genre = Genre::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Genre berhasil ditambahkan.',
            'data' => [
                'id' => $genre->id,
                'genre_id' => $genre->genre_id,
                'name' => $genre->name,
                'slug' => $genre->slug,
            ]
        ], 201);
    }

    // PUT/PATCH /genres/{genre_id}
    public function update(Request $request, $genre_id)
    {
        $genre = Genre::where('genre_id', $genre_id)->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:genres,name,' . $genre->id,
        ]);

        $genre->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Genre berhasil diperbarui.',
            'data' => [
                'id' => $genre->id,
                'genre_id' => $genre->genre_id,
                'name' => $genre->name,
                'slug' => $genre->slug,
            ]
        ]);
    }

    // DELETE /genres/{genre_id}
    public function destroy($genre_id)
    {
        $genre = Genre::where('genre_id', $genre_id)->firstOrFail();

        $genre->delete();

        return response()->json([
            'success' => true,
            'message' => 'Genre berhasil dihapus.'
        ]);
    }
}
