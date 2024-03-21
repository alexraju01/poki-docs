<?php

namespace App\Http\Controllers;
use App\Services\PokemonApiService;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class PokemonController extends Controller

{
    protected $pokemonApiService;

    public function __construct(PokemonApiService $pokemonApiService)
    {
        $this->pokemonApiService = $pokemonApiService;
    }

 
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $pokemons = $this->fetchPokemons(1000);
        $pokemons = $this->pokemonApiService->fetchPokemons(100000);
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
        $pokemonData = $this->pokemonApiService->fetchPokemonData($id); //
        $types = collect($pokemonData['types'])->pluck('type.name')->all();
        $pokemonSpecies = $this->pokemonApiService->fetchPokemonSpecies($id);
        $genus = collect($pokemonSpecies['genera'] ?? [])->firstWhere('language.name', 'en')['genus'] ?? null;

        $description = $this->pokemonApiService->fetchPokemonDescription($id);
        $statsBarWithColor = $this->pokemonApiService->percentageStatsBarWithColor($pokemonData['stats'], $types[0]);
        $strengthAndWeakness = $this->pokemonApiService->pokemonStrengthAndWeakness($id);
        // $pokemonByType = $this->pokemonApiService->fetchPokemonByType($types[0]);

        
        
        // $this->getPokemonByType($pokemonData['id'], $types[0], $this->getEvolutionChainId($id));

        $pokemonInfo = [
            'id' => $pokemonData['id'],                                                             // ID
            'name' => $pokemonData['name'],                                                         // Name
            'sprite' => $pokemonData['sprites']['other']['official-artwork']['front_default'],      // Image
            'types' =>  $types,
            'stats' => $statsBarWithColor,                                                          // Stats array format primary and secondary attrubute type
            'genus' => $genus,                                                                      // Genus Information e.g. seed pokemon
            'description' => $description,
            'moves' => $this->pokemonApiService->fetchPokemonMoves($pokemonData['moves']),
            'ability' => $this->pokemonApiService->fetchPokemonAbilities($pokemonData['abilities']),
            'strengths' => $strengthAndWeakness['strengths'],   
            'weaknesses' => $strengthAndWeakness['weaknesses'],               
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
