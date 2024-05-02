@extends('layouts.app')

@section('content')

<div class="container">
    <h1 id="Title">Poki List (Tops {{$limit}})</h1>
    
    <!-- Search Form -->
    <form class="search-box" action="{{ route('pokemons.index') }}" method="GET">
        <div class="search-bar">
            <input type="text" name="search" class="input-box" placeholder="Search poki..." value="{{ $search }}">
            <button type="submit" class="fas fa-search"></button>
        </div>
    </form>
    
   
    
    
    

    
    
    
    

    <!-- Pokémon List -->
    @if(count($pokemons) > 0)
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
    @else
        <h2>No Pokémon found.</h2>
    @endif
</div>
@endsection

