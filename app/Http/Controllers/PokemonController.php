<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class PokemonController extends Controller
{

    private function fetchPokemonData($id) {
        $client = new Client();
        $response = $client->get(env('POKEMON_API_URL') . '/pokemon/' . $id);
        return json_decode($response->getBody(), true);
        
    }

    private function fetchPokemonSpecies($id) {
        $client = new Client();
        $repsonse = $client->get(env('POKEMON_API_URL'). '/pokemon-species/' . $id);
        return json_decode($repsonse->getBody(), true);

    }

    
    // Fetching the specified pokemon's description from API
    private function fetchPokemonDescription($id) {
        $pokemonSpeciesData = $this->fetchPokemonSpecies($id);
        $flavorTexts = array_filter(
            $pokemonSpeciesData['flavor_text_entries'], function ($text) {
                return $text['language']['name'] === 'en';
        });
        $flavorTexts = array_slice($flavorTexts, 0, 3);
    
        $paragraph = '';   // empty string to hold all the uniquetexts
        $uniqueTexts = []; // Array to hold unique flavor texts
    
        foreach ($flavorTexts as $flavorText) {
            // Clean up the text
            $cleanText = str_replace(["\n", "\f", "\r"], " ", $flavorText['flavor_text']);
    
            // Check if the text is already added to prevent duplicates
            if (!isset($uniqueTexts[$cleanText])) {
                $paragraph .= $cleanText . ' ';
                $uniqueTexts[$cleanText] = true; // Mark this text as added
            }
        }
        return trim($paragraph);
    }
    
// ============================== Logics Of Stat Bars =============================
    private function calculateStatPercentage($baseStat, $maxStatValue = 255) {
        return ($baseStat / $maxStatValue) * 100;
    }

//     public function adjustBrightness($hexColor, $factor = 0.2)
// {
//     $factor = max(0, min(1, $factor)); // Ensure factor is between 0 and 1

//     // Convert hex to RGB
//     $rgb = sscanf($hexColor, "#%02x%02x%02x");

//     // Increase the RGB values based on the factor to make the color brighter
//     $brighterRgb = array_map(function ($color) use ($factor) {
//         return 255 - (255 - $color) * (1 - $factor);
//     }, $rgb);

//     // Convert back to hex and return
//     return sprintf("#%02x%02x%02x", ...$brighterRgb);
// }
    
    private function determineBackgroundColor($statName, $pokemonType) {
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
            // Add other types as needed
        ];
        
        
        // If the stat is special-attack or special-defense, return a specific color
        if (in_array($statName, ['special-attack', 'special-defense'])) {
            return $typeColors[$pokemonType];
        }
        
        // If the Pokémon type matches one in the mapping, return its color
        if (array_key_exists($pokemonType, $typeColors)) {
            // $backgroundColor = $this->determineBackgroundColor($statName, $pokemonType);
            // $backgroundColor = $this->adjustBrightness($typeColors[$pokemonType], 0.9);
            return $typeColors[$pokemonType];

        }
        
        // Default color if no specific conditions are met
        return '#c9c7c8';
    }
    
    private function percentageStatsBarWithColor($stats, $pokemonType) {
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
    
    private function fetchPokemons($limit) {
        $client = new Client();
        $response = $client->get('https://pokeapi.co/api/v2/pokemon?limit=' . $limit);
        $data = json_decode($response->getBody(), true)['results'];
        // dd($data);
        foreach ($data as &$pokemon) {
            // dd($pokemon['name']);
            $pokemonUrl = $pokemon['url'];
            $pokemonId = explode('/', $pokemonUrl)[6];

            $pokemon['id'] = $pokemonId;
            // $pokemon['name'] = $pokemon['name'];
            $pokemon['sprite'] = "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/{$pokemonId}.png";
        }
        return $data;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pokemons = $this->fetchPokemons(1000);
        return view('pokemons.index', compact('pokemons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
// ########################### Abilities #############################
     private function fetchPokemonAbilities($Abilities) {
        return collect($Abilities)->pluck('ability.name')->all();
     }

// ########################### Moves #############################

     private function fetchPokemonMoves($moves) {
    return collect($moves)
        ->map(fn($move) => $this->prepareMove($move))
        ->filter()
        ->sortBy(fn($move) => $move['lvl_req'])
        ->values();
}

// ======================== Pokemon types ==============================



private function getEvolutionChainId($id) {
    $speciesData = $this->fetchPokemonSpecies($id);
    $evolutionChainUrl = $speciesData['evolution_chain']['url'];
    $urlSegments = explode('/', rtrim($evolutionChainUrl, '/'));
    $evolutionChainId = end($urlSegments);
    return $evolutionChainId;
}

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




private function pokemonStrengthAndWeakness($id) {
    $client = new Client();
    $pokemonApiUrl = env('POKEMON_API_URL') . '/pokemon/' . $id;
    $response = $client->request('GET', $pokemonApiUrl);
    $pokeData = json_decode($response->getBody(), true);
    $types = collect($pokeData['types'])->pluck('type.name');

    $strengths = [];
    $weaknesses = [];

    foreach ($types as $type) {
        $typeResponse = $client->request('GET', "https://pokeapi.co/api/v2/type/{$type}");
        $typeData = json_decode($typeResponse->getBody(), true);

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


    public function show($id)
    {
        $pokemonData = $this->fetchPokemonData($id);
    
        $strengthWeakness = $this->pokemonStrengthAndWeakness($id);

        // Looping through pokemon types
        $types = collect($pokemonData['types'])
            ->pluck('type.name')
            ->all();
        

        // $this->getEvolutionChainId($id);
        $pokemonSpecies = $this->fetchPokemonSpecies($id);
        $genus = collect($pokemonSpecies['genera'])
            ->firstWhere('language.name', 'en')['genus'];
        
        $description = $this->fetchPokemonDescription($id);
        $statsBarWithColor = $this->percentageStatsBarWithColor($pokemonData['stats'], $types[0]);
        
        // $this->getPokemonByType($pokemonData['id'], $types[0], $this->getEvolutionChainId($id));

        $pokemonInfo = [
            'id' => $pokemonData['id'],                                                             // ID
            'name' => $pokemonData['name'],                                                         // Name
            'sprite' => $pokemonData['sprites']['other']['official-artwork']['front_default'],      // Image
            'types' =>  $types,
            'stats' => $statsBarWithColor,                                                          // Stats array format primary and secondary attrubute type
            'genus' => $genus,                                                                      // Genus Information e.g. seed pokemon
            'description' => $description,
            'moves' => $this->fetchPokemonMoves($pokemonData['moves']),
            'ability' => $this->fetchPokemonAbilities($pokemonData['abilities']),
            'pros' => $strengthWeakness['strengths'],   
            'cons' => $strengthWeakness['weaknesses'],               

            // dd(); 
        ];

        return view('pokemons.show', compact('pokemonInfo'));
    }
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
