<?php

namespace App\Services;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PokemonApiServices{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.pokemon.api_url');
    }
    // =========================== Extracting ID From URL ===========================
 protected function extractIdFromUrl($url) {
    return Str::of($url)->trim('/')->explode('/')->last();
}
// =========================== Fetching Pokemons ===========================
public function fetchPokemons($limit){
    $response = Http::get("{$this->baseUrl}/pokemon?limit=" . $limit);
    if ($response->successful()) {
        $data = $response->json()['results'];
        return $this->addImgAndIdToData($data);
    }
}

public function fetchPokemonByType($type){
    $response = Http::get("{$this->baseUrl}/type/{$type}");

    $data = json_decode($response->getBody()->getContents(), true);
    // dd(collect($data['pokemon'])->take(3));
    return $data['pokemon'] ?? [];
} 

// =========================== Adding Image And ID To Data ===========================
protected function addImgAndIdToData($data) {
    return collect($data)->map(function ($pokemon) {
        $pokemonId = $this->extractIdFromUrl($pokemon['url']);
        $pokemon['id'] = $pokemonId;
        $pokemon['sprite'] = "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/{$pokemonId}.png";
        return $pokemon;
    })->toArray();
}

    //  ############################ Fetching Pokemon Data #########################
    public function fetchPokemonData($id){
        return Cache::remember("pokemon_data_{$id}", now()->addHours(24), function () use ($id) {
            $response = Http::get("https://pokeapi.co/api/v2/pokemon/{$id}");
            return $response->json();
        });
    }

    // ============================== Logics Of Stat Bars =============================
    protected function calculateStatPercentage($baseStat, $maxStatValue = 255) {
        return ($baseStat / $maxStatValue) * 100;
    }
    
    protected function determineBackgroundColor($statName, $pokemonType) {
        // Retrieve the mapping of Pokémon types to colors from configuration
        $typeColors = config('pokemonTypes.type_colors');
    
        // If the stat is special-attack or special-defense, return the specific color
        if (in_array($statName, ['special-attack', 'special-defense'], true)) {
            return $typeColors[$pokemonType] ?? $typeColors['default'];
        }
        
        // Return the color for the Pokémon type, or the default color if not found
        return $typeColors[$pokemonType] ?? $typeColors['default'];
    }
    
    
    public function percentageStatsBarWithColor($stats, $pokemonType) {
        return collect($stats)->map(function ($stat) use ($pokemonType) {
            // Pass the Pokémon type to determineBackgroundColor
            $backgroundColor = $this->determineBackgroundColor($stat['stat']['name'], $pokemonType);
            $statPercentage = $this->calculateStatPercentage($stat['base_stat']);
            
            return array_merge($stat, [
                'background_color' => $backgroundColor,
                'percentage' => $statPercentage,
            ]);
        })->all(); // Convert back to array
    }

    // ########################### Fetching Abilities #############################
    public function fetchPokemonAbilities($Abilities) {
        return collect($Abilities)->pluck('ability.name')->all();
     }

    // ########################### Fetching Moves #############################

    public function fetchPokemonMoves($moves)
    {
        return collect($moves)
            ->map(fn($move) => $this->prepareMove($move))
            ->filter()
            ->sortBy('lvl_req');
    }

    // ########################### Preparing Moves Data #############################

    private function prepareMove($move)
    {
        $levelUpMove = $this->getFirstLevelUpMove($move['version_group_details']);
        return $levelUpMove ? [
            'name' => $move['move']['name'],
            'url' => $move['move']['url'],
            'lvl_req' => $levelUpMove['level_learned_at'],
        ] : null;
    }

    // ################# Repeated Items In The list, Only Returning One Item Back ##################
    private function getFirstLevelUpMove($versionGroupDetails)
    {
        return collect($versionGroupDetails)
            ->where('move_learn_method.name', 'level-up')
            ->sortBy('level_learned_at')
            ->map(fn($vgd) => ['level_learned_at' => $vgd['level_learned_at']])
            ->first();
    }
    
    // =========================== Fetching Pokemon Types EndPoint ===========================
    public function fetchTypeById($id) {
        return Cache::remember("pokemon_type_{$id}", now()->addHours(24), function () use ($id) {
            $response = Http::get("{$this->baseUrl}/type/{$id}");
            return $response->json();
        });
    }

     // ============================== Strengths And Weakness =============================
    public function pokemonStrengthAndWeakness($id) {
        $pokeData = $this->fetchPokemonData($id);
        $types = collect($pokeData['types'])->pluck('type.name');
        
        $strengths = [];
        $weaknesses = [];
        
        foreach ($types as $type) {
            $typeData = $this->fetchTypeById($type);
            
            $strengths[$type] = [
                'double_damage_to' => collect($typeData['damage_relations']['double_damage_to'])->pluck('name')->all(),
                'half_damage_from' => collect($typeData['damage_relations']['half_damage_from'])->pluck('name')->all(),
                'no_damage_from' => collect($typeData['damage_relations']['no_damage_from'])->pluck('name')->all(),
            ];

            $weaknesses[$type] = [
                'half_damage_to' => collect($typeData['damage_relations']['half_damage_to'])->pluck('name')->all(),
                'double_damage_from' => collect($typeData['damage_relations']['double_damage_from'])->pluck('name')->all(),
                'no_damage_to' => collect($typeData['damage_relations']['no_damage_to'])->pluck('name')->all(),
            ];
        }
        // dd($weaknesses);
        return [
            'strengths' => $strengths,
            'weaknesses' => $weaknesses,
        ];
    }

//  ======================= Evolutions ==================================
public function showEvolutions($name){
    $cacheKey = "pokemon_evolutions_with_levels_{$name}";
    // $evolutions = Cache::remember($cacheKey, now()->addHour(), function () use ($name) {
        // Get species information
        $speciesResponse = Http::get("https://pokeapi.co/api/v2/pokemon-species/{$name}");

        // Check if the response is not null and contains the expected 'evolution_chain' data
        if ($speciesResponse->json() && isset($speciesResponse->json()['evolution_chain']['url'])) {
            $evolutionChainUrl = $speciesResponse->json()['evolution_chain']['url'];
            $evolutionData = Http::get($evolutionChainUrl)->json();

            return $this->fetchEvolutions([$evolutionData['chain']]);
        } 
        return []; // or any other appropriate response
 
}



protected function fetchEvolutions($evolutionNode, $level = 1){
    return collect($evolutionNode)->flatMap(function ($evolution) {
        $speciesName = $evolution['species']['name'] ?? null;
        if ($speciesName) {
            $pokemonData = Http::get("https://pokeapi.co/api/v2/pokemon/{$speciesName}");

            if ($pokemonData->successful()) {
                $evolutionDetails = collect($evolution['evolution_details'])->first();
                $evolvesAtLevel = $evolutionDetails['min_level'] ?? null;

                return [[
                    'name' => $speciesName,
                    'image_url' => $pokemonData['sprites']['front_default'],
                    'evolves_at_level' => $evolvesAtLevel,
                ]];
            }
        }

        // Recurse if there are further evolutions.
        return !empty($evolution['evolves_to']) ? $this->fetchEvolutions($evolution['evolves_to']) : collect();
    });
}
}