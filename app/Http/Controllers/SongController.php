<?php

namespace App\Http\Controllers;

use App\Models\Song;
use App\Models\Album;
use Illuminate\Http\Request;

/**
 * @OA\Server(
 *     url="http://127.0.0.1:8000",
 *     description="Localhost API server"
 * )
 *
 * @OA\Tag(
 *     name="Songs",
 *     description="API для работы с песнями"
 * )
 */
class SongController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/songs",
     *     tags={"Songs"},
     *     summary="Получить список всех песен",
     *     @OA\Response(
     *         response=200,
     *         description="Успешное выполнение"
     *     )
     * )
     */
    public function index()
    {
        return response()->json(Song::with('albums')->get(), 200);
    }

    /**
     * @OA\Post(
     *     path="/api/songs",
     *     tags={"Songs"},
     *     summary="Создать новую песню",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title"},
     *             @OA\Property(property="title", type="string", example="Yellow")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Песня создана"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate(['title' => 'required|string|max:255']);
        $song = Song::create($validated);
        return response()->json($song, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/songs/{id}",
     *     tags={"Songs"},
     *     summary="Получить информацию о песне",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID песни",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Информация о песне"
     *     )
     * )
     */
    public function show(Song $song)
    {
        $song->load('albums');
        return response()->json($song, 200);
    }

    /**
     * @OA\Put(
     *     path="/api/songs/{id}",
     *     tags={"Songs"},
     *     summary="Обновить информацию о песне",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID песни",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title"},
     *             @OA\Property(property="title", type="string", example="Fix You")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Песня обновлена"
     *     )
     * )
     */
    public function update(Request $request, Song $song)
    {
        $validated = $request->validate(['title' => 'required|string|max:255']);
        $song->update($validated);
        return response()->json($song, 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/songs/{id}",
     *     tags={"Songs"},
     *     summary="Удалить песню",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID песни",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Песня удалена"
     *     )
     * )
     */
    public function destroy(Song $song)
    {
        $song->delete();
        return response()->noContent();
    }

    /**
     * @OA\Post(
     *     path="/api/songs/{id}/add-to-album",
     *     tags={"Songs"},
     *     summary="Добавить песню в альбом",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID песни",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"album_id", "track_number"},
     *             @OA\Property(property="album_id", type="integer", example=1),
     *             @OA\Property(property="track_number", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Песня добавлена в альбом"
     *     )
     * )
     */
    public function addToAlbum(Request $request, Song $song)
    {
        $validated = $request->validate([
            'album_id' => 'required|exists:albums,id',
            'track_number' => 'required|integer|min:1',
        ]);

        $album = Album::findOrFail($validated['album_id']);
        $album->songs()->attach($song->id, ['track_number' => $validated['track_number']]);
        return response()->json(['message' => 'Song added to album'], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/songs/{id}/remove-from-album",
     *     tags={"Songs"},
     *     summary="Удалить песню из альбома",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID песни",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"album_id"},
     *             @OA\Property(property="album_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Песня удалена из альбома"
     *     )
     * )
     */
    public function removeFromAlbum(Request $request, Song $song)
    {
        $validated = $request->validate([
            'album_id' => 'required|exists:albums,id',
        ]);

        $album = Album::findOrFail($validated['album_id']);
        $album->songs()->detach($song->id);
        return response()->json(['message' => 'Song removed from album'], 200);
    }
}
