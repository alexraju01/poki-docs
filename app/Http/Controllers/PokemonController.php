<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;


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
    
    private function determineBackgroundColor($statName) {
        return in_array($statName, ['special-attack', 'special-defense']) ? '#2fb487' : '#c9c7c8';
    }
    
    private function percentageStatsBarWithColor($stats) {
        return collect($stats)->map(function ($stat) {
            $backgroundColor = $this->determineBackgroundColor($stat['stat']['name']);
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
        $pokemons = $this->fetchPokemons(10000);
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

     private function fetchPokemonAbilities($Abilities) {
        return collect($Abilities)->pluck('ability.name')->all();
     }

     private function fetchPokemonMoves($moves)
{
    return collect($moves)
        ->map(fn($move) => $this->prepareMove($move))
        ->filter()
        ->sortBy(fn($move) => $move['lvl_req']['level_learned_at'])
        ->values();
}

private function prepareMove($move)
{
    $levelUpMove = $this->getFirstLevelUpMove($move['version_group_details']);

    if (is_null($levelUpMove)) {
        return null;
    }

    return [
        'name' => $move['move']['name'],
        'url' => $move['move']['url'],
        'lvl_req' => $levelUpMove,
    ];
}

private function getFirstLevelUpMove($versionGroupDetails)
{
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


//      private function fetchPokemonMoves($moves) {
//     $movesCollection = collect($moves)
//         ->map(function ($move) {
//             $levelUpMoves = collect($move['version_group_details'])
//                 ->filter(function ($vgd) {
//                     return $vgd['move_learn_method']['name'] == 'level-up';
//                 })
//                 ->map(function ($vgd) {
//                     // Extract level learned at and version group name
//                     return [
//                         'level_learned_at' => $vgd['level_learned_at'],
//                         // 'version_group_name' => $vgd['version_group']['name'],
//                     ];
//                 })
//                 ->sortBy('level_learned_at') // Ensure lvl_req is sorted for each move
//                 ->values() // Reset keys after sorting
//                 ->first(); // Only keep the first value (lowest level)

//             // Skip adding lvl_req if no level-up moves exist
//             if (empty($levelUpMoves)) {
//                 return null;
//             }

//             return [
//                 'name' => $move['move']['name'],
//                 'url' => $move['move']['url'],
//                 'lvl_req' => $levelUpMoves,
//             ];
//         })
//         ->filter() // Remove moves that ended up with null (no level-up moves)
//         ->values(); // Re-index the collection after filtering

//     // Uncomment the next line to dump the collection for debugging
//     // dd($movesCollection);
//         dd($movesCollection);
//     return $movesCollection;
// }



    public function show($id)
    {
        $pokemonData = $this->fetchPokemonData($id);

        // Looping through pokemon types
        $types = collect($pokemonData['types'])
            ->pluck('type.name')
            ->all();
        
        $pokemonSpecies = $this->fetchPokemonSpecies($id);
        $genus = collect($pokemonSpecies['genera'])
            ->firstWhere('language.name', 'en')['genus'];
        
        $description = $this->fetchPokemonDescription($id);
        $statsBarWithColor = $this->percentageStatsBarWithColor($pokemonData['stats']);
        

        $pokemonInfo = [
            'id' => $pokemonData['id'],                                                             // ID
            'name' => $pokemonData['name'],                                                         // Name
            'sprite' => $pokemonData['sprites']['other']['official-artwork']['front_default'],      // Image
            'types' =>  $types,
            'stats' => $statsBarWithColor,                                                          // Stats array format primary and secondary attrubute type
            'genus' => $genus,                                                                      // Genus Information e.g. seed pokemon
            'description' => $description,
            'moves' => $this->fetchPokemonMoves($pokemonData['moves']),
            'ability' => $this->fetchPokemonAbilities($pokemonData['abilities'])  
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
