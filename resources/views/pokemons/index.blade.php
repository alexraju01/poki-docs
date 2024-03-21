@extends('layouts.app')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokemon</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    
</head>
<body>
    
    <h1>Pokemon List (Top 1000)</h1>
   <div class="card-container">
       @foreach($pokemons as $pokemon)
       <a href="{{ route('pokemons.show', ['pokemon' => $pokemon['id']]) }}">
        <div class="card"
        onerror="this.style.display='none';">
            <p>{{$pokemon['id']}}</p>
            <img src="{{ $pokemon['sprite'] }}" alt="{{ ucfirst($pokemon['name']) }}"
            onerror="this.closest('a').remove();">
            <p>{{ ucfirst($pokemon['name'])}}</p>
        </div>
    </a>
    @endforeach
</div>
    {{-- <script src="{{ asset('js/app.js') }}"></script> --}}
</body>
</html>
