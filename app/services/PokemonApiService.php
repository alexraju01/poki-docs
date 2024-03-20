<?php

namespace App\Services;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
class PokemonApiService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.pokemon.api_url');
    }

    // =========================== Extracting ID From URL ===========================
    protected function extractIdFromUrl($url) {
        return str::of($url)->trim('/')->explode('/')->last();
    }
    // =========================== Fetching Pokemons ===========================
    public function fetchPokemons($limit){
        $response = Http::get("{$this->baseUrl}/pokemon?limit=" . "$limit");
        if ($response->successful()) {
            $data = $response->json()['results'];
            return $this->addImgAndIdToData($data);
        }

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

    
    // =========================== Fetching Pokemon Data(show.blade.php) ===========================
    public function fetchPokemonData($id)
    {
        $response = Http::get("{$this->baseUrl}/pokemon/{$id}");
        return $response->json();
    }

    // =========================== Fetching Pokemon Species ===========================
    public function fetchPokemonSpecies($id) {
        $response = Http::get("{$this->baseUrl}/pokemon-species/{$id}");
        return $response->json();
    }
    
    // =========================== Fetching Poke Types EndPoint ===========================
    public function fetchTypeById($id) {
        $response = Http::get("{$this->baseUrl}/type/{$id}");
        return $response->json();
    }

    // =========================== Fetching Pokemon Description ===========================

    public function fetchPokemonDescription($id) {
        $pokemonSpeciesData = $this->fetchPokemonSpecies($id);
        return collect($pokemonSpeciesData['flavor_text_entries'])
            ->where('language.name', 'en')      // English language
            ->take(3)                           // 3 lines worth
            ->map(function ($entry) {
                return Str::of($entry['flavor_text'])->replace(["\n", "\f", "\r"], " ");
            })
            ->unique()
            ->implode(' ');
    }

    // ============================== Logics Of Stat Bars =============================
    protected function calculateStatPercentage($baseStat, $maxStatValue = 255) {
        return ($baseStat / $maxStatValue) * 100;
    }
    
    protected function determineBackgroundColor($statName, $pokemonType) {
        // Define a mapping of Pokémon types to colors
        $typeColors = [
            'bug' => '#92b242',
            'dark' => '#5a526a',
            'dragon' => '#6a68cf',
            'electric' => '#e2e242',
            'fairy' => '#e242bc',
            'fighting' => '#e27242',
            'fire' => '#e24242',
            'flying' => '#9090e2',
            'ghost' => '#6768cf',
            'grass' => '#2e9868',
            'ground' => '#d2a758',
            'ice' => '#68cfcf',
            'normal' => '#a8a68a',
            'poison' => '#9c50b7',
            'psychic' => '#e24292',
            'rock' => '#a8a258',
            'steel' => '#a8aacb',
            'water' => '#4a90e2',
        ];
        
        
        // If the stat is special-attack or special-defense, return a specific color
        if (in_array($statName, ['special-attack', 'special-defense'])) {
            return $typeColors[$pokemonType];
        }
        
        // If the Pokémon type matches one in the mapping, return its color
        if (array_key_exists($pokemonType, $typeColors)) {
            return $typeColors[$pokemonType];
        }
        
        // Default color if no specific conditions are met
        return '#c9c7c8';
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
    
        return [
            'strengths' => $strengths,
            'weaknesses' => $weaknesses,
        ];
    }

    // ============================== Fetching Evolution Chain ID =============================
    public function fetchEvolutionChainId($id) {
        $speciesData = $this->fetchPokemonSpecies($id);
        $evolutionChainUrl = $speciesData['evolution_chain']['url'];
        $urlSegments = explode('/', rtrim($evolutionChainUrl, '/'));
        $evolutionChainId = end($urlSegments);
        return $evolutionChainId;
    }
    
    // ########################### Fetching Abilities #############################
    public function fetchPokemonAbilities($Abilities) {
        return collect($Abilities)->pluck('ability.name')->all();
     }


    // ########################### Fetching Moves #############################

    public function fetchPokemonMoves($moves) {
        return collect($moves)
            ->map(fn($move) => $this->prepareMove($move))
            ->filter()
            ->sortBy(fn($move) => $move['lvl_req'])
            ->values();
    }

// ########################### Preparing Moves Data #############################

    private function prepareMove($move) {
        $levelUpMove = $this->getFirstLevelUpMove($move['version_group_details']);
        if (is_null($levelUpMove)) {
            return null;
        }
        return [
            'name' => $move['move']['name'],
            'url' => $move['move']['url'],
            'lvl_req' => $levelUpMove['level_learned_at'],
        ];
    }

    // ################# Repeated Items In The list, Only Returning One Item Back ##################
    private function getFirstLevelUpMove($versionGroupDetails) {
        return collect($versionGroupDetails)
            ->filter(fn($vgd) => $vgd['move_learn_method']['name'] == 'level-up')
            ->map(fn($vgd) => $this->extractLevelLearnedAt($vgd))
            ->sortBy('level_learned_at')
            ->first();
    }

    private function extractLevelLearnedAt($vgd)
    {
        return ['level_learned_at' => $vgd['level_learned_at']];
    }

}
