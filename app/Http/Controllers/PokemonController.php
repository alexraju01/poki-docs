<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PokemonController extends Controller
{

    private function fetchPokemonData($name)
    {
        $client = new Client();
        $response = $client->get(env('POKEMON_API_URL') . '/pokemon/' . $name);
        return json_decode($response->getBody(), true);
    }


    private function fetchPokemons($limit) {
        $client = new Client();
        $response = $client->get(env('POKEMON_API_URL') . '/pokemon?limit=' . $limit);
        $pokemons = json_decode($response->getBody(), true)['results'];

        foreach ($pokemons as &$pokemon) {
            $pokemonResponse = $client->get($pokemon['url']);
            $pokemonDetails = json_decode($pokemonResponse->getBody(), true);
        // dd($pokemonDetails);
            
            $pokemon['sprite'] = $pokemonDetails['sprites']['front_default'];
            $pokemon['type'] = $pokemonDetails['types'][0]['type']['name'];
        }
        return $pokemons;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pokemons = $this->fetchPokemons(21);
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
    public function show($name)
    {
        $pokemonInfo = $this->fetchPokemonData($name);

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
