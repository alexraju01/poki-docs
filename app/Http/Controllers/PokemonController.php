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


    private function fetchPokemons($limit) {
        $client = new Client();
        $response = $client->get(env('POKEMON_API_URL') . '/pokemon?limit=' . $limit);
        $pokemons = json_decode($response->getBody(), true)['results'];

        foreach ($pokemons as &$pokemon) {
            $pokemonResponse = $client->get($pokemon['url']);
            $pokemonDetails = json_decode($pokemonResponse->getBody(), true);
          dd($pokemonDetails);
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
        $pokemons = $this->fetchPokemons(9);
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

        $pokemonSpecies = $this->fetchPokemonSpecies($id);
        // dd($pokemonSpecies);

        $genus = collect($pokemonSpecies['genera'])->where('language.name', 'en')->first()['genus'];
        
        // Looping through pokemon types
        $types = collect($pokemonData['types'])->pluck('type.name')->all();

        $pokemonInfo = [
            'id' => $pokemonData['id'],
            'name' => $pokemonData['name'],
            'sprite' => $pokemonData['sprites']['other']['official-artwork']['front_default'],
            'types' =>  $types,
            'stats' => $pokemonData['stats'],
            'genus' => $genus, // Adding genus information
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
