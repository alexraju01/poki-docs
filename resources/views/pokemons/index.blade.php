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
    <body>
        <div id="preloader">
          <div class="spinner"></div>   
    </div>

    <h1>Pokemon List</h1>
   <div class="card-container">
       @foreach($pokemons as $pokemon)
       <a href="{{ route('pokemons.show', ['pokemon' => $pokemon['id']]) }}">
        <div class="card ">
            <p>{{$pokemon['id']}}</p>
            <img src="{{ $pokemon['sprite'] }}" alt="{{ ucfirst($pokemon['name']) }}">
            <p>{{ ucfirst($pokemon['name'])}}</p>
        </div>
    </a>
    @endforeach
</div>

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
