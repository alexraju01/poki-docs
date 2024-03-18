<div class="tab-section" x-data="{ tab: 'stats' }">
    <div class="tabs">
        <button class="tab-button" :class="{ 'active': tab === 'stats' }" @click="tab = 'stats'">Stats</button>
        <button class="tab-button" :class="{ 'active': tab === 'about' }" @click="tab = 'about'">About</button>
        <button class="tab-button" :class="{ 'active': tab === 'skills' }" @click="tab = 'skills'">Skills</button>
    </div>

    <div class="stats-container" class="tab-content" x-show="tab === 'stats'">
        <!-- Stats content -->
        {{-- <div class="stats"> --}}
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

    <div class="about" class="tab-content" x-show="tab === 'about'">
        <!-- About content -->
        <p class="desc">{{ $pokemonInfo['description'] }}</p>
    </div>

    <div class="skils" class="tab-content" x-show="tab === 'skills'">
        <!-- Skills content -->
        @foreach($pokemonInfo['ability'] as $ability)
            <div class="ability">{{ $ability}}</div>
        @endforeach
        @foreach($pokemonInfo['moves'] as $move)
            <div class="ability">{{ $move['lvl_req']}}: {{$move['name']}}</div>
        @endforeach

    </div>
</div>

