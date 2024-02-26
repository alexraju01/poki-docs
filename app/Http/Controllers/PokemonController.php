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
        $pokemonInfo = $this->fetchPokemonData($id);
        $pokemon = [
            'id' => $pokemonInfo['id'],
            'sprite' => $pokemonInfo['sprites']['other']['official-artwork']['front_default'],
            'type' =>  $pokemonInfo['types'][0]['type']['name'],
            'stats' => $pokemonInfo['stats'],
            
        ];

        return view('pokemons.show', compact('pokemonInfo', 'pokemon'));
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
