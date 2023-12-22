<?php

namespace Database\Seeders;

use App\Models\Character;
use App\Models\Episode;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CharacterEpisodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Obtener todos los personajes y episodios
        $characters = Character::all();
        $episodes = Episode::all();

        // Iterar sobre cada personaje y asignarle episodios aleatorios
        foreach ($characters as $character) {
            $randomEpisodes = $episodes->random(rand(1, 5)); // Cambia el rango según tus necesidades

            // Adjuntar los episodios al personaje
            $character->episodes()->attach($randomEpisodes->pluck('id')->toArray());
        }
    }
}
