<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Album;
use App\Models\Song;

/**
 * @OA\Info(
 *      title="Music Catalog API",
 *      version="1.0.0",
 *      description="API для управления исполнителями, альбомами и песнями.",
 *      @OA\Contact(
 *          email="support@example.com"
 *      )
 * )
 *
 * @OA\Server(
 *     url="http://127.0.0.1:8000",
 *     description="Localhost API server"
 * )
 *
 * @OA\Tag(
 *     name="Album Songs",
 *     description="API для работы с песнями в альбомах"
 * )
 */
class AlbumSongController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/albums/{album}/songs",
     *     tags={"Album Songs"},
     *     summary="Получить список песен в альбоме",
     *     @OA\Parameter(
     *         name="album",
     *         in="path",
     *         description="ID альбома",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Список песен в альбоме",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Song Title"),
     *                 @OA\Property(property="track_number", type="integer", example=1)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Альбом не найден"
     *     )
     * )
     */
    public function index(Album $album)
    {
        $songs = $album->songs()->get(['songs.id', 'songs.title', 'album_song.track_number']);
        return response()->json($songs, 200);
    }

    /**
     * @OA\Post(
     *     path="/api/albums/{album}/songs",
     *     tags={"Album Songs"},
     *     summary="Добавить песню в альбом",
     *     @OA\Parameter(
     *         name="album",
     *         in="path",
     *         description="ID альбома",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"song_id", "track_number"},
     *             @OA\Property(property="song_id", type="integer", example=1),
     *             @OA\Property(property="track_number", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Песня добавлена в альбом"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Альбом или песня не найдены"
     *     )
     * )
     */
    public function store(Request $request, Album $album)
    {
        $validated = $request->validate([
            'song_id' => 'required|exists:songs,id',
            'track_number' => 'required|integer|min:1',
        ]);

        $song = Song::findOrFail($validated['song_id']);
        $album->songs()->attach($song->id, ['track_number' => $validated['track_number']]);

        return response()->json(['message' => 'Song added to album successfully'], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/albums/{album}/songs/{song}",
     *     tags={"Album Songs"},
     *     summary="Удалить песню из альбома",
     *     @OA\Parameter(
     *         name="album",
     *         in="path",
     *         description="ID альбома",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="song",
     *         in="path",
     *         description="ID песни",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Песня удалена из альбома"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Альбом или песня не найдены"
     *     )
     * )
     */
    public function destroy(Album $album, Song $song)
    {
        if (!$album->songs()->detach($song->id)) {
            return response()->json(['message' => 'Song not found in album'], 404);
        }

        return response()->json(['message' => 'Song removed from album successfully'], 200);
    }
}
