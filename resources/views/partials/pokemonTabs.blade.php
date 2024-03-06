<div class="tab-section" x-data="{ tab: 'stats' }">

    {{-- <div class="tabs">  
        <button :class="tab-button active" data-color="#2e9868" onclick="openTab(event, 'Stats')">Stats</button>
        <button :class="tab-button" data-color="{{$pokemonInfo['types'][0]}}" onclick="openTab(event, 'About')">About</button>
    </div>
    

    <div id="Stats" class="tab-content show">
        <!-- Content for the Stats tab -->
        <div class="stats">
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
        </div>
    </div>

    <div id="About" class="tab-content">
        <!-- Content for the About tab -->
        <p class="desc">{{ $pokemonInfo['description'] }}</p>
        
    </div> --}}
    

    <div class="tab-section" x-data="{ tab: 'stats' }">
        <div class="tabs">
            <button class="tab-button" :class="{ 'active': tab === 'stats' }" @click="tab = 'stats'">Stats</button>
            <button class="tab-button" :class="{ 'active': tab === 'about' }" @click="tab = 'about'">About</button>
        </div>
    
        <div id="Stats" class="tab-content show" x-show="tab === 'stats'">
            <!-- Stats content -->
            <div class="stats">
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
            </div>
        </div>
    
        <div id="About" class="tab-content" x-show="tab === 'about'">
            <!-- About content -->
            <p class="desc">{{ $pokemonInfo['description'] }}</p>
        </div>
    </div>
    

    
    
</div>

