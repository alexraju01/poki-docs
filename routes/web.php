<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PokemonController;

Route::get('/', function () {
    return redirect()->route('pokemons.index');
});



Route::resource('pokemons', PokemonController::class)->only(['index', 'show']);
