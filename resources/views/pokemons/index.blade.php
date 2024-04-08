@extends('layouts.app')

@section('content')

<div class="container">
    <h1 id="Title">Pokemon List (Tops {{$limit}})</h1>
    
    <!-- Search Form -->
    <form action="{{ route('pokemons.index') }}" method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search for Pokémon..." value="{{ $search }}">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

    <!-- Pokémon List -->
    @if(count($pokemons) > 0)
    <div class="card-container">
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

