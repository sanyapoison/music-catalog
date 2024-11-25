<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use Illuminate\Http\Request;

/**
 * @OA\Server(
 * url="http://127.0.0.1:8000",
 * description="Localhost API server"
 * )
 *
 * @OA\Tag(
 *     name="Artists",
 *     description="API для работы с исполнителями"
 * )
 */
class ArtistController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/artists",
     *     tags={"Artists"},
     *     summary="Получить список всех исполнителей",
     *     @OA\Response(
     *         response=200,
     *         description="Успешное выполнение"
     *     )
     * )
     */
    public function index()
    {
        return response()->json(Artist::with('albums.songs')->get(), 200);
    }

    /**
     * @OA\Post(
     *     path="/api/artists",
     *     tags={"Artists"},
     *     summary="Создать нового исполнителя",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Coldplay")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Исполнитель создан"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate(['name' => 'required|string|max:255']);
        $artist = Artist::create($validated);
        return response()->json($artist, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/artists/{id}",
     *     tags={"Artists"},
     *     summary="Получить информацию об исполнителе",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID исполнителя",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Информация об исполнителе"
     *     )
     * )
     */
    public function show(Artist $artist)
    {
        $artist->load('albums.songs');
        return response()->json($artist, 200);
    }

    /**
     * @OA\Put(
     *     path="/api/artists/{id}",
     *     tags={"Artists"},
     *     summary="Обновить информацию об исполнителе",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID исполнителя",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Imagine Dragons")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Исполнитель обновлен"
     *     )
     * )
     */
    public function update(Request $request, Artist $artist)
    {
        $validated = $request->validate(['name' => 'required|string|max:255']);
        $artist->update($validated);
        return response()->json($artist, 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/artists/{id}",
     *     tags={"Artists"},
     *     summary="Удалить исполнителя",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID исполнителя",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Исполнитель удален"
     *     )
     * )
     */
    public function destroy(Artist $artist)
    {
        $artist->delete();
        return response()->noContent();
    }
}
