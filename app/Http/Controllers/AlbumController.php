<?php

namespace App\Http\Controllers;

use App\Models\Album;
use Illuminate\Http\Request;

/**
 * @OA\Server(
 * url="http://127.0.0.1:8000",
 * description="Localhost API server"
 * )
 *
 * @OA\Tag(
 *     name="Albums",
 *     description="API для работы с альбомами"
 * )
 */
class AlbumController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/albums",
     *     tags={"Albums"},
     *     summary="Получить список всех альбомов",
     *     @OA\Response(
     *         response=200,
     *         description="Успешное выполнение"
     *     )
     * )
     */
    public function index()
    {
        return response()->json(Album::with(['artist', 'songs'])->get(), 200);
    }

    /**
     * @OA\Post(
     *     path="/api/albums",
     *     tags={"Albums"},
     *     summary="Создать новый альбом",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "artist_id", "release_year"},
     *             @OA\Property(property="title", type="string", example="A Head Full of Dreams"),
     *             @OA\Property(property="artist_id", type="integer", example=1),
     *             @OA\Property(property="release_year", type="integer", example=2015)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Альбом создан"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'artist_id' => 'required|exists:artists,id',
            'release_year' => 'required|digits:4|integer|min:1900|max:' . date('Y'),
        ]);

        $album = Album::create($validated);
        return response()->json($album, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/albums/{id}",
     *     tags={"Albums"},
     *     summary="Получить информацию об альбоме",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID альбома",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Информация об альбоме"
     *     )
     * )
     */
    public function show(Album $album)
    {
        $album->load(['artist', 'songs']);
        return response()->json($album, 200);
    }

    /**
     * @OA\Put(
     *     path="/api/albums/{id}",
     *     tags={"Albums"},
     *     summary="Обновить информацию об альбоме",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID альбома",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "artist_id", "release_year"},
     *             @OA\Property(property="title", type="string", example="Mylo Xyloto"),
     *             @OA\Property(property="artist_id", type="integer", example=1),
     *             @OA\Property(property="release_year", type="integer", example=2011)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Альбом обновлен"
     *     )
     * )
     */
    public function update(Request $request, Album $album)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'artist_id' => 'required|exists:artists,id',
            'release_year' => 'required|digits:4|integer|min:1900|max:' . date('Y'),
        ]);

        $album->update($validated);
        return response()->json($album, 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/albums/{id}",
     *     tags={"Albums"},
     *     summary="Удалить альбом",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID альбома",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Альбом удален"
     *     )
     * )
     */
    public function destroy(Album $album)
    {
        $album->delete();
        return response()->noContent();
    }
}
