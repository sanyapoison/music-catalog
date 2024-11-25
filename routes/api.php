<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ArtistController;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\SongController;
use App\Http\Controllers\AlbumSongController;

Route::apiResource('artists', ArtistController::class);
Route::apiResource('albums', AlbumController::class);
Route::apiResource('songs', SongController::class);
Route::get('/albums/{album}/songs', [AlbumSongController::class, 'index']);
Route::post('/albums/{album}/songs', [AlbumSongController::class, 'store']);
Route::delete('/albums/{album}/songs/{song}', [AlbumSongController::class, 'destroy']);
