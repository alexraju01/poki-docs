@extends('layouts.app')

@section('content')
<div class="pokemon">
    <div class="poke-profile">

        <a href="{{route('pokemons.index')}}"><span class="go-back"><</span></a>
    
        <div class="poke-id">
            <p>#{{ $pokemonInfo['id'] }}</p>
        </div>
        <div class="img-container">
            <img
            class="poke-sprite poke-backdrop-{{ $pokemonInfo['types'][0] }}"
            src="{{ $pokemonInfo['sprite'] }}"
            alt="Pokemon Image"
        />
            {{-- <img class="poke-bg-img" src="{{ asset('images/pokedex.svg') }}" alt=""> --}}
            
        </div>
        
       
        <span class="poke-name">{{ ucfirst($pokemonInfo['name']) }}</span>
        <span class="poke-genus">{{ $pokemonInfo['genus'] }}</span>
        <div class="poke-types">
            @foreach ($pokemonInfo['types'] as $type)
                <button class="button-{{ $type }}">{{ ucfirst($type) }}</button>
            @endforeach
        </div>
    </div>

    <!-- Tabs for additional details about the Pokemon -->
    <div class="poke-tabs">
        @include('partials.pokemonTabs', ['pokemon' => $pokemonInfo])
    </div>


</div>




<div class="d-pokemon">
    <div class="d-poke-profile">

        <div class="d-poke-tabs">
            @include('partials.d-pokemonTabs', ['pokemon' => $pokemonInfo])
        </div>
    </div>
</div>


@endsection


{{-- @push('scripts')
<script src="{{ asset('js/pokemonTab.js') }}"></script>

@endpush --}}
