@extends('layouts.app')

@section('content')
<div class="pokemon">
    <div class="poke-img">
        <div class="poke-id">
            <p>#{{ $pokemonInfo['id'] }}</p>
        </div>
        <img class="poke-bg-img" src="{{ asset('images/pokedex.svg') }}" alt="">
        <img
            class="poke-backdrop-{{ $pokemonInfo['types'][0] }}"
            src="{{ $pokemonInfo['sprite'] }}"
            alt="Pokemon Image"
        />
        <span class="poke-name">{{ ucfirst($pokemonInfo['name']) }}</span>
        <span class="poke-genus">{{ $pokemonInfo['genus'] }}</span>
        <div class="poke-types">
            @foreach ($pokemonInfo['types'] as $type)
                <button class="button-{{ $type }}">{{ ucfirst($type) }}</button>
            @endforeach
        </div>
    </div>

    <!-- Tabs for additional details about the Pokemon -->
    <div class="pokemon-tabs">
        @include('partials.pokemonTabs', ['pokemon' => $pokemonInfo])
    </div>
</div>
@endsection


@push('scripts')
<script src="{{ asset('js/pokemonTab.js') }}"></script>
@endpush
