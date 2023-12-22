<?php

use App\Http\Controllers\CharactersController;
use App\Http\Controllers\EpisodesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('characters')->group(function () {
    Route::post('save', [CharactersController::class, 'index']);
    Route::post('list', [CharactersController::class, 'list']);
    Route::post('show/{id_personaje}', [CharactersController::class, 'show']);
    Route::post('search/{id_personaje}', [CharactersController::class, 'search']);
    Route::post('store', [CharactersController::class, 'store']);
    Route::post('/character/{characterId}/episode/{episodeId}', [CharactersController::class, 'attachEpisode']);
});

Route::prefix('episode')->group(function () {
    Route::post('save', [EpisodesController::class, 'index']);
    Route::post('list', [EpisodesController::class, 'list']);
    Route::post('show/{id_episode}', [EpisodesController::class, 'show']);
});