<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\Episode;
use Exception;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EpisodesController extends Controller
{
    public function index()
    {
        try {
            $response = Http::get('https://rickandmortyapi.com/api/episode');
            $data = $response->json()['results'];
            foreach ($data as $episode) {
                $episodeDetails = Http::get($episode['url'])->json();
                $this->storeEpisodes($episodeDetails);
            }
            return response()->json([
                'message' => 'Episodios cargados',
            ]);
        } catch (Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return response()->json(['message' => 'Error al cargar los personajes', 'error' => $e->getMessage()], 500);
        }
    }

    public function storeEpisodes($data)
    {
        try {
            Episode::create([
                'id' => $data['id'],
                'name' => $data['name'],
                'air_date' => $data['air_date'],
                'episode' => $data['episode']
            ]);
        } catch (QueryException $e) {
            Log::error('Error al almacenar personajes en la base de datos: ' . $e->getMessage());
        }
    }

    public function list()
    {
        $episodes = [];
        try {
            $episodes = Episode::all();
            return [
                'status' => 'ok',
                'Episodios' => $episodes,
            ];
        } catch (QueryException $e) {
            throw $e;
        }
    }

    public function show($id)
    {
        try {
            $episode = Episode::findOrFail($id);

            // Obtener los personajes que participan en el episodio
            $characters = $episode->characters;

            return response()->json([
                'status' => 'ok',
                'episode' => $episode,
                'characters' => $characters,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Episodio no encontrado'], 404);
        }
    }
}
