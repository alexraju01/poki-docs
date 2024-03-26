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
            $response = Http::get("https://pokeapi.co/api/v2/pokemon/{$id}");
            return $response->json();
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
        // dd($moves);
        return collect($moves)
            ->map(fn($move) => $this->prepareMove($move))
            ->filter()
            ->sortBy('lvl_req');
    }

    // ########################### Preparing Moves Data #############################

    private function prepareMove($move)
{
    $levelUpMove = $this->getFirstLevelUpMove($move['version_group_details']);
    if ($levelUpMove) {
        // Fetch move details from PokéAPI to get the type
        // $moveDetails = Cache::rememberForever("move_details_{$move['move']['name']}", function () use ($move) {
            $moveDetails = Http::get($move['move']['url'])->json();
        // });
        
        $moveType = $moveDetails['type']['name'] ?? 'unknown'; // Extracting the move type

        return [
            'name' => $move['move']['name'],
            'url' => $move['move']['url'],
            'lvl_req' => $levelUpMove['level_learned_at'],
            'type' => $moveType, // Now includes the move type fetched from the PokéAPI
        ];
    }

    return null;
}

    // ################# Repeated Items In The list, Only Returning One Item Back ##################
    private function getFirstLevelUpMove($versionGroupDetails)
    {
        // dd($versionGroupDetails);
        return collect($versionGroupDetails)
            ->where('move_learn_method.name', 'level-up')
            ->sortBy('level_learned_at')
            ->map(fn($vgd) => ['level_learned_at' => $vgd['level_learned_at']])
            ->first();
    }
    
    // =========================== Fetching Pokemon Types EndPoint ===========================
    public function fetchTypeById($id) {
            $response = Http::get("{$this->baseUrl}/type/{$id}");
            return $response->json();
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
public function showEvolutions($name)
{
    $speciesResponse = Http::get("https://pokeapi.co/api/v2/pokemon-species/{$name}");
    if (!$speciesResponse->successful()) {
        return collect(); // Early return on failed API call
    }
    $evolutionChainUrl = $speciesResponse->json()['evolution_chain']['url'];
    $evolutionData = Http::get($evolutionChainUrl)->json();

    // Pass a starting level of 1 for the base Pokémon
    return $this->fetchEvolutions(collect([$evolutionData['chain']]), 1);
}

protected function fetchEvolutions($evolutionNodes, $level = 1)
{
    $evolutions = collect();

    foreach ($evolutionNodes as $evolutionNode) {
        $speciesName = $evolutionNode['species']['name'];
        $pokemonData = $this->fetchPokemonData($speciesName);
        if (empty($pokemonData)) {
            continue; // Skip if no data is returned
        }

        // Extracting the types into an array
        $types = collect($pokemonData['types'])->map(function ($typeEntry) {
            return $typeEntry['type']['name']; // Gets the type name
        })->toArray(); // Converts the collection to an array

        $evolutionDetails = collect($evolutionNode['evolution_details'])->first();
        $evolvesAtLevel = $evolutionDetails ? $evolutionDetails['min_level'] : $level; // Use provided level if min_level is not specified

        $evolutions->push([
            'name' => $speciesName,
            'image_url' => $pokemonData['sprites']['front_default'],
            'evolves_at_level' => $evolvesAtLevel,
            'types' => $types,
        ]);

        if (!empty($evolutionNode['evolves_to'])) {
            // Pass the evolvesAtLevel for the next evolution stage
            $evolutions = $evolutions->merge($this->fetchEvolutions(collect($evolutionNode['evolves_to']), $evolvesAtLevel));
        }
    }

    return $evolutions;
}





}