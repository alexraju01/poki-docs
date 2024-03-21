<div class="d-tab-section" x-data="{ tab: 'description' }">

    <div class="d-tabs">
        <button class="d-tab-button-{{ $pokemonInfo['types'][0] }}" :class="{'active': tab === 'description'}" @click="tab = 'description'">Description</button>
        <button class="d-tab-button-{{ $pokemonInfo['types'][0] }}" :class="{'active': tab === 'evolution'}" @click="tab = 'evolution'">Evolution</button>
        <button class="d-tab-button-{{ $pokemonInfo['types'][0] }}" :class="{'active': tab === 'skillset'}" @click="tab = 'skillset'">Skill Set</button>
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
            <h1>Description</h1>
            <div class=description>{{$pokemonInfo['description']}}</div>
            
            <div class=strengths>
                <h2>Strengths</h2>
                @foreach ($pokemonInfo['strengths'] as $type => $strength)
                {{-- <h3>{{ ucfirst($type) }}</h3> --}}
                <div class="d-poke-types">
                    @foreach ($strength['double_damage_to'] as $damageType)
                    {{-- <h1>{{$damageType}}</h1> --}}
                        <button class="d-button-{{ $damageType }}">{{ ucfirst($damageType) }}</button>
                    @endforeach
                </div>
                @endforeach
            </div>
            
           
            <div class=weaknesses>
                <h2>Weakness</h2>
                @foreach ($pokemonInfo['weaknesses'] as $type => $weakness)
                {{-- <h3>{{ ucfirst($type) }}</h3> --}}
                <div class="d-poke-types">
                    @foreach ($weakness['double_damage_from'] as $damageType)
                    {{-- <h1>{{$damageType}}</h1> --}}
                        <button class="d-button-{{ $damageType }}">{{ ucfirst($damageType) }}</button>
                    @endforeach
                </div>
                @endforeach
            </div>

            {{-- <div class="weakness">
                <h2>Weaknesses</h2>
                @foreach ($pokemonInfo['weaknesses'] as $type => $weakness)
                <h3>{{ ucfirst($type) }}</h3>
                <ul>
                    <li>Half Damage To: {{ implode(', ', $weakness['half_damage_to']) }}</li>
                    <li>2x Damage From: {{ implode(', ', $weakness['double_damage_from']) }}</li>
                </ul>
                @endforeach
            </div> --}}
        </div>

    </div>

    
    
    <div class="evolution" x-show="tab === 'evolution'">
        <p>Evolution Path Evolution Path</p>
    </div>

    <div class="skillsets" x-show="tab === 'skillset'">
        <p>Skill Set Skill Set</p>
        <div class="d-img-container">
            <img
            class="poke-sprite poke-backdrop-{{ $pokemonInfo['types'][0] }}"
            src="{{ $pokemonInfo['sprite'] }}"
            alt="Pokemon Image"/>
            {{-- <span class="poke-genus">{{ $pokemonInfo['genus'] }}</span> --}}
        
        </div>
    </div>
</div>




</div>
