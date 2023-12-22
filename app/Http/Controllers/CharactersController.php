<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\Episode;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CharactersController extends Controller
{
    public function index()
    {
        try {
            $response = Http::get('https://rickandmortyapi.com/api/character');
            $data = $response->json()['results'];
            foreach ($data as $character) {
                $characterDetails = Http::get($character['url'])->json();
                $this->storeCharacters($characterDetails);
            }
            return response()->json([
                'message' => 'Personajes cargados',
            ]);
        } catch (Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return response()->json(['message' => 'Error al cargar los personajes', 'error' => $e->getMessage()], 500);
        }
    }

    public function storeCharacters($data)
    {
        try {
            Character::create([
                'id' => $data['id'],
                'name' => $data['name'],
                'status' => $data['status'],
                'species' => $data['species'],
                'gender' => $data['gender'],
            ]);
        } catch (QueryException $e) {
            Log::error('Error al almacenar personajes en la base de datos: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'status' => 'required|string',
            'species' => 'required|string',
            'gender' => 'required|string',
        ]);

        $character = Character::create($request->all());

        return response()->json([
            'status' => 'ok',
            'character' => $character,
            'message' => 'Personaje creado exitosamente.',
        ]);
    }

    public function list()
    {
        $characters = [];
        try {
            $characters = Character::all();
            return [
                'status' => 'ok',
                'Personajes' => $characters,
            ];
        } catch (QueryException $e) {
            throw $e;
        }
    }

    public function show($id_character)
    {
        try {
            $character = Character::findOrFail($id_character);

            // Obtener los episodios asociados al personaje
            $episodes = $character->episodes;

            return response()->json([
                'status' => 'ok',
                'character' => $character,
                'episodes' => $episodes,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Personaje no encontrado'], 404);
        }
    }

    public function search($id_character)
    {
        try {
            $character = Character::with('episodes')->findOrFail($id_character);

            return response()->json([
                'status' => 'ok',
                'personaje' => $character,
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Personaje no encontrado'], 404);
        }
    }

    public function attachEpisode($characterId, $episodeId)
    {
        try {
            // Obtener el personaje y el episodio
            $character = Character::findOrFail($characterId);
            $episode = Episode::findOrFail($episodeId);

            // Vincular el personaje al episodio
            $character->episodes()->attach($episode->id);

            return response()->json([
                'status' => 'ok',
                'message' => 'Personaje vinculado al episodio exitosamente.',
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Personaje o episodio no encontrado'], 404);
        }
    }
}
