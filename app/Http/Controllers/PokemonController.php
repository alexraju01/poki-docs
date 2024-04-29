<?php

namespace App\Http\Controllers;
use App\Services\PokemonApiServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
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

    public function index()
{
    $limit = 1000;
    $search = request('search', '');
    $ActiveType = request('filter'); // Capture the type from the request

    // Fetch and cache Pokémon data
    $pokemons = Cache::remember('pokemons', now()->addDays(90), function () use ($limit) {
        return $this->pokemonApiService->fetchPokemons($limit);
    });
 
    $listOfTypePokemon = $this->pokemonApiService->fetchTypesByFilter($ActiveType);
    // dd($listOfTypePokemon);
    $pokemonCollection = collect($pokemons);

    // Apply search filtering
   // Apply search filtering
if (!empty($search)) {
    $pokemonCollection = $pokemonCollection->filter(function ($pokemon) use ($search) {
        return Str::contains(strtolower($pokemon['name']), strtolower($search));
    });
}
if ($listOfTypePokemon && is_array($listOfTypePokemon)) {
    $pokemonCollection = $pokemonCollection->filter(function ($pokemon) use ($listOfTypePokemon) {
        return in_array($pokemon['name'], $listOfTypePokemon); // Check if Pokémon name is in the list
    });
}

// Get the filtered Pokémon data as an array
$filteredPokemons = $pokemonCollection->values()->toArray();


    // Return the view with the filtered Pokémon, the search term, and the available types
    return view('pokemons.index', [
        'pokemons' => $filteredPokemons,
        'limit' => $limit,
        'search' => $search,
        'ActiveType' => $ActiveType, // Pass the current filter type to the view
        'listTypes' => $this->pokemonApiService->fetchTypes(), // Fetch available types for the view
    ]);
}

    public function show($id)
    {
        // Define a unique cache key based on the Pokemon ID
        $cacheKey = $id;

        // Attempt to retrieve the data from cache
        $pokemonInfo = Cache::remember($cacheKey, now()->addDays(90), function () use ($id) {
            // If not found in cache, fetch data from the API
            $basicInfo = $this->pokemonApiService->fetchPokemonData($id);
            $types = collect($basicInfo['types'])->pluck('type.name')->all();
            $speciesInfo = $this->pokemonApiService->fetchPokemonSpecies($id);
            $genusType = collect($speciesInfo['genera'] ?? [])->firstWhere('language.name', 'en')['genus'] ?? null;
            $statsBarWithColor = $this->pokemonApiService->percentageStatsBarWithColor($basicInfo['stats'], $types[0]);
            $strengthAndWeakness = $this->pokemonApiService->pokemonStrengthAndWeakness($id);
            
            $englishDescription = collect($speciesInfo['flavor_text_entries'])
                ->where('language.name', 'en')
                ->take(4)
                ->map(function ($entry) {
                    return Str::of($entry['flavor_text'])->replace(["\n", "\f", "\r"], " ");
                })
                ->unique()
                ->implode(' ');

            return [
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
        });

        return view('pokemons.show', compact('pokemonInfo'));
    }


}
