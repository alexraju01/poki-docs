<?php

use App\Http\Controllers\PokemonController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::resource('pokemons', PokemonController::class)->only(['index','show']);

// Route::resource('pokemons', 'PokemonController')->only(['index', 'show']);

// Route::get('/pokemons/{id}', [PokemonController::class, 'show']);



