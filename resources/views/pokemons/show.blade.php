@extends('layouts.app')

@section('content')
<div class="pokemon">
    <div class="poke-profile">
        
        {{-- Desktop View --}}
        <div class="name-section">
            <span class="type-indicator type-indicator-{{$pokemonInfo['types'][0]}}"></span>
            <p class=d-poke-name>{{$pokemonInfo['name']}}</p>
            <p class="d-poke-id">#{{ $pokemonInfo['id'] }}</p>
            <div class="poke-DTypes">Type: 
                @foreach ($pokemonInfo['types'] as $type)
                <p> {{ ucfirst($type) }} </p>
                @endforeach
            </div>
            <span class="next-evolution next-evolution-{{$pokemonInfo['types'][0]}}">
                See Next Evolution
            </span>
        </div>


        {{-- Desktop Stats--}}
        <div class="d-stats-container" class="tab-content">
            <!-- Stats content -->
            {{-- <div class="stats"> --}}
                @foreach($pokemonInfo['stats'] as $stat)
                <div class="d-stat">
                    <span class="d-stat-label">{{ str_replace(['Special-attack','Special-defense'],[' Sp. Atk',' Sp. Def'], ucFirst($stat['stat']['name'])) }}</span>
                    <span class="d-stat-num">{{ $stat['base_stat'] }}</span>
                    <div class="d-stat-bar">
                        <div class="d-stat-fill" 
                             style="width: {{ $stat['percentage'] }}%; background-color: {{ $stat['background_color'] }};">
                        </div>
                    </div>
                </div>
            @endforeach
            {{-- </div> --}}
        </div>

        <div class="d-poke-tab">
            <div class="d-tabs">
                <button class="d-tab-button">Description</button>
                <button class="d-tab-button">Evolution</button>
                <button class="d-tab-button">Skill Set</button>
                {{-- <button class="d-tab-button"></button> --}}
            </div>
        </div>

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
@endsection


@push('scripts')
<script src="{{ asset('js/pokemonTab.js') }}"></script>
@endpush
