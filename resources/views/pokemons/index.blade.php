@extends('layouts.app')

@section('content')

<div class="container">
    <h1 id="Title">Poki List (Tops {{$limit}})</h1>
    
    <!-- Search Form -->
    <form class="searchBar" action="{{ route('pokemons.index') }}" method="GET">
        <div class="search-box">
            <input type="text" name="search" class="input-box" placeholder="Search poki..."  value="{{ $search }}">
            <i class="fas fa-search"></i>
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
        <p>No Pokémon found.</p>
    @endif
</div>


    
        {{-- <div class="card-container">
            @foreach($pokemons as $pokemon)
            <a href="{{ route('pokemons.show', ['pokemon' => $pokemon['name']]) }}">
            <div class="card"
            onerror="this.style.display='none';">
                <p>{{$pokemon['id']}}</p>
                <img src="{{ $pokemon['sprite'] }}" alt="{{ ucfirst($pokemon['name']) }}"
                onerror="this.closest('a').remove();">
                <p>{{ ucfirst($pokemon['name'])}}</p>
            </div>
            </a>
            @endforeach
        </div> --}}


@endsection

