<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;


class PokemonController extends Controller
{

    private function fetchPokemonData($id)
    {
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
    

    // Fetching specifiic number of pokemons from API
    private function fetchPokemons($limit) {
        $client = new Client();
        $response = $client->get(env('POKEMON_API_URL') . '/pokemon?limit=' . $limit);
        $pokemons = json_decode($response->getBody(), true)['results'];

        foreach ($pokemons as &$pokemon) {
            $pokemonResponse = $client->get($pokemon['url']);
            $pokemonDetails = json_decode($pokemonResponse->getBody(), true);

            $pokemon['sprite'] = $pokemonDetails['sprites']['front_default'];
            $pokemon['type'] = $pokemonDetails['types'][0]['type']['name'];
            $pokemon['id'] = $pokemonDetails['id'];
        }
        return $pokemons;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pokemons = $this->fetchPokemons(0);
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

        $statsWithColor = $this->percentageStatsBarWithColor($pokemonData['stats']);
        // dd($statsWithColor);

        $pokemonInfo = [
            'id' => $pokemonData['id'],                                                             // ID
            'name' => $pokemonData['name'],                                                         // Name
            'sprite' => $pokemonData['sprites']['other']['official-artwork']['front_default'],      // Image
            'types' =>  $types,
            'stats' => $statsWithColor,
            // 'stats' => $pokemonData['stats'],                                                       // Stats
            'genus' => $genus,  
            'description' => $description                                                                   // Genus Information e.g. seed pokemon
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
