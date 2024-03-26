<?php

namespace App\Http\Controllers;
use App\Services\PokemonApiServices;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PokemonController extends Controller
{
  
    protected $pokemonApiService;
    protected $baseUrl;

    public function __construct(PokemonApiServices $pokemonApiService)
    {
        $this->pokemonApiService = $pokemonApiService;
        $this->baseUrl = config('services.pokemon.api_url');
    }

    public function index(){
    $limit=1000;
    $pokemons = $this->pokemonApiService->fetchPokemons($limit);
    return view('pokemons.index', ['pokemons' => $pokemons,'limit' => $limit]);
}


    public function show($id)
{
    $basicInfo = $this->pokemonApiService->fetchPokemonData($id);
    // dd($basicInfo);
    $types = collect($basicInfo['types'])->pluck('type.name')->all();
    $speciesInfo = Http::get($basicInfo['species']['url'])->json();
    $genusType = collect($speciesInfo['genera'] ?? [])->firstWhere('language.name', 'en')['genus'] ?? null;
   
    $statsBarWithColor = $this->pokemonApiService->percentageStatsBarWithColor($basicInfo['stats'], $types[0]);
    $strengthAndWeakness = $this->pokemonApiService->pokemonStrengthAndWeakness($id);

    // Select an English description (flavor text) from a specific version for consistency
    $englishDescription = collect($speciesInfo['flavor_text_entries'])
    ->where('language.name', 'en')
    ->take(4)
    ->map(function ($entry) {
    return Str::of($entry['flavor_text'])->replace(["\n", "\f", "\r"], " ");})
    ->unique()
    ->implode(' ');

    $pokemonInfo = [
        'id' => $basicInfo['id'],
        'name' => $basicInfo['name'],
        'sprite' => $basicInfo['sprites']['other']['official-artwork']['front_default'],
        'types' => $types,
        'genus' => $genusType,
        'stats' => $statsBarWithColor,    
        'ability' => $this->pokemonApiService->fetchPokemonAbilities($basicInfo['abilities']),  
        'moves' => $this->pokemonApiService->fetchPokemonMoves($basicInfo['moves']), 
        'strengths' => $strengthAndWeakness['strengths'],   
        'weaknesses' => $strengthAndWeakness['weaknesses'],
        'description' => $englishDescription,
        'evolutions' => $this->pokemonApiService->showEvolutions($basicInfo['name'])               

    ];
    
    return view('pokemons.show',compact('pokemonInfo'));
}


}
