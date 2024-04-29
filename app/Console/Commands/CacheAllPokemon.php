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
        // $processedCount = 1; // Initialize counter
    foreach ($pokemonIds as $id) {
        $cacheKey = $id;

        // Check if the Pokémon data is already cached to avoid unnecessary processing
        if (!Cache::has($cacheKey)) {
            // Cache the Pokémon data if it's not already cached
            Cache::remember($cacheKey, now()->addDays(90), function () use ($id) {
                // If not found in cache, fetch data from the API
            $basicInfo = $this->pokemonApiService->fetchPokemonData($id);
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
            ];
        });

            $this->info("Cached data for Pokémon ID:{$id}");
            // $processedCount++;
            // echo ("Cached data for Pokémon ID: {$name}");
        } else {
            $this->info("Data for Pokémon ID: {$id} is already cached.");
            // $processedCount++;
        }
    }
    }
}
