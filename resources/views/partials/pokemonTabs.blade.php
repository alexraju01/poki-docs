<div class="tab-section" x-data="{ tab: 'skills' }">
    <div class="tabs">
        <button class="tab-button" :class="{ 'active': tab === 'stats' }" @click="tab = 'stats'">Stats</button>
        <button class="tab-button" :class="{ 'active': tab === 'about' }" @click="tab = 'about'">About</button>
        <button class="tab-button" :class="{ 'active': tab === 'skills' }" @click="tab = 'skills'">Skills</button>
    </div>

    <div class="stats-container" x-show="tab === 'stats'">
            @foreach($pokemonInfo['stats'] as $stat)
            <div class="stat">
                <span class="stat-label">{{ str_replace(['Special-attack','Special-defense'],[' Sp. Atk',' Sp. Def'], ucFirst($stat['stat']['name'])) }}</span>
                <span class="stat-num">{{ $stat['base_stat'] }}</span>
                <div class="stat-bar">
                    <div class="stat-fill" 
                            style="width: {{ $stat['percentage'] }}%; background-color: {{ $stat['background_color'] }};">
                    </div>
                </div>
            </div>
        @endforeach
        {{-- </div> --}}
    </div>

    <div class="about" x-show="tab === 'about'">
        <!-- About content -->
        <p class="desc">{{ $pokemonInfo['description'] }}</p>
    </div>

    <div class="skills-tab" x-show="tab === 'skills'">
            <h3 class="abilities-heading">Abilties</h3>
        <div class="abilities-container">
            @foreach($pokemonInfo['ability'] as $ability)
                <button class="ability">{{ $ability}}</button>
            @endforeach
        </div>
            
        <h3 class="moves-heading">Moves</h3>

        <div class="moveset-container">
            @foreach($pokemonInfo['moves'] as $move)
                <button class="move move-{{$move['type']}}">
                    <p class="move-lvl">Lv: {{$move['lvl_req']}}</p>
                    <p class="move-name">{{ $move['name']}}</p>
                    <p class="move-power">Power: {{$move['power']}}</p>
                    <p class="move-pp">PP: {{$move['pp']}}</p>
                </button>
            @endforeach
        </div>

    </div>
</div>

