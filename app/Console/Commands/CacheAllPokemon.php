<?php

namespace App\Console\Commands;

use App\Services\PokemonApiServices;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CacheAllPokemon extends Command
{
    protected $signature = 'app:cache-all-pokemon';

    protected $description = 'Command description';
    protected $pokemonApiService;
     public function __construct(PokemonApiServices $pokemonApiService)
     {
         parent::__construct();
         $this->pokemonApiService = $pokemonApiService;
     }

    public function handle()
    {
        $pokemonIds = $this->pokemonApiService->fetchAllPokemonIds();

    foreach ($pokemonIds as $id) {
        $cacheKey = 'pokemon_' . $id;

        // Check if the Pokémon data is already cached to avoid unnecessary processing
        if (!Cache::has($cacheKey)) {
            // Cache the Pokémon data if it's not already cached
            Cache::remember($cacheKey, now()->addDays(90), function () use ($id) {
                // Fetch detailed data for the Pokémon
                $basicInfo = $this->pokemonApiService->fetchPokemonData($id);
                
                // Extract and process specific parts of the data as needed
                // This example uses placeholders for these processes
                $types = collect($basicInfo['types'])->pluck('type.name')->all();
                $speciesInfo = $this->pokemonApiService->fetchPokemonSpecies($id);
                $genusType = collect($speciesInfo['genera'] ?? [])->firstWhere('language.name', 'en')['genus'] ?? null;
                $statsBarWithColor = $this->pokemonApiService->percentageStatsBarWithColor($basicInfo['stats'], $types[0]);
                $strengthAndWeakness = $this->pokemonApiService->pokemonStrengthAndWeakness($id);
    
                $englishDescription = collect($speciesInfo['flavor_text_entries'])
                    ->where('language.name', 'en')
                    ->take(4)
                    ->map(function ($entry) {
                        return Str::of($entry['flavor_text'])->replace(["\n", "\f", "\r"], " ");
                    })
                    ->unique()
                    ->implode(' ');
                // Other processing steps can go here, similar to the 'show' method example
                // This includes fetching abilities, moves, strengths, weaknesses, etc.

                // Construct and return the structured data for caching
                return [
                    'id' => $basicInfo['id'],
                    'name' => $basicInfo['name'],
                    'sprite' => $basicInfo['sprites']['other']['official-artwork']['front_default'],
                    'types' => $types,
                    'genus' => $genusType,
                    'stats' => $statsBarWithColor,
                    'ability' => $this->pokemonApiService->fetchPokemonAbilities($basicInfo['abilities']),
                    'moves' => $this->pokemonApiService->fetchPokemonMoves($basicInfo['moves']),
                    'strengths' => $strengthAndWeakness['strengths'],
                    'weaknesses' => $strengthAndWeakness['weaknesses'],
                    'description' => $englishDescription,
                    'evolutions' => $this->pokemonApiService->showEvolutions($basicInfo['name'])
                        // Add more processed data here as needed
                ];
            });

            $this->info("Cached data for Pokémon ID: {$id}");
        } else {
            $this->info("Data for Pokémon ID: {$id} is already cached.");
        }
    }
    }
}
