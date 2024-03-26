<div class="d-tab-section" x-data="{ tab: 'description' }">
    <div class="d-tabs">
        <button class="d-tab-button-{{ $pokemonInfo['types'][0] }}" :class="{'active': tab === 'description'}" @click="tab = 'description'">Description</button>
        <button class="d-tab-button-{{ $pokemonInfo['types'][0] }}" :class="{'active': tab === 'evolution'}" @click="tab = 'evolution'">Evolution</button>
        <button class="d-tab-button-{{ $pokemonInfo['types'][0] }}" :class="{'active': tab === 'moveSet'}" @click="tab = 'moveSet'">Skill Set</button>
        {{-- <button class="d-tab-button"></button> --}}
    </div>
    
    


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

    
    <div class="box" x-show="tab === 'description'">
        <div class="d-img-container">
            <img
            class="poke-sprite poke-backdrop-{{ $pokemonInfo['types'][0] }}"
            src="{{ $pokemonInfo['sprite'] }}"
            alt="Pokemon Image"/>
            {{-- <span class="poke-genus">{{ $pokemonInfo['genus'] }}</span> --}}
        
        </div>
        <div class="d-stats-container" >

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
        </div>



        <div class="d-about">
            <h1 syle="text-align:start;">Description</h1>
            <div class=description>{{$pokemonInfo['description']}}</div>
            <span class="line-break-{{$pokemonInfo['types'][0]}}"></span>
          
            <x-strengths-weakness-tag  title="strengths" :data="$pokemonInfo['strengths']"/>
            <x-strengths-weakness-tag  title="weaknesses" :data="$pokemonInfo['weaknesses']"/>
        </div>

    </div>

    
    
    <div class="evolution" x-show="tab === 'evolution'">
        <h2>Evolution Path</h2>
        <div class="d-evolution-container">
            @forelse ($pokemonInfo['evolutions'] as $evolution)
                <div class="evolution">
                    <img src="{{ $evolution['image_url'] }}" alt="{{ $evolution['name'] }}">
                    <p>{{ $evolution['name'] }}</p>
                </div>
                @if (!$loop->last)
                <i class=" d-arrow fa-solid fa-arrow-right"></i>
                @endif
            @empty
                <div class="no-evolution">
                    <p>This Pokémon does not evolve.</p>
                </div>
            @endforelse
        </div>
    </div>
    

    {{-- ######################## Move Set ########################--}}
    <div class="d-moveSet" x-show="tab === 'moveSet'">
        <div class="d-abilities">
            @foreach($pokemonInfo['ability'] as $ability)
                <div class="d-ability"><p>{{ $ability}}</p></div>
            @endforeach
        </div>

        <div class="d-moves">
            @foreach($pokemonInfo['moves'] as $move)
                <div class="d-move">
                    <p>{{ucFirst($move['name'])}}</p>
                </div>
            @endforeach
        </div>
    </div>
</div>




</div>
