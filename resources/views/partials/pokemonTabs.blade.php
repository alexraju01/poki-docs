<div class="tab-section">

    <div class="tabs">
        <button class="tab-button active" onclick="openTab(event, 'About')">About</button>
        <button class="tab-button" onclick="openTab(event, 'Stats')">Stats</button>
    </div>
    
    <div id="About" class="tab-content show">
        <!-- Content for the About tab -->
        <p>{{ $pokemonInfo['description'] }}</p>
        
    </div>
    
    <div id="Stats" class="tab-content">
        <!-- Content for the Stats tab -->


          

        <div class="stats">
            @foreach($pokemonInfo['stats'] as $stat)
            <div class="stat">
                <span class="stat-label">{{ str_replace(['Special-attack','Special-defense'],[' Sp. Atk',' Sp. Def'], ucFirst($stat['stat']['name'])) }}</span>
                <span class="stat-num">{{ $stat['base_stat'] }}</span>
                <div class="stat-bar"><div class="stat-fill" style="width: {{ $stat['base_stat'] }}%;"></div></div>
              </div>
            {{-- <div class="stat">
                <span class="name">{{ str_replace(['Special', '-'], [' Sp.', ' '], ucFirst($stat['stat']['name'])) }}: </span>
                <span class=stat-num>{{ $stat['base_stat'] }}</span>
                <div class="bar" style="width: {{ $stat['base_stat'] }}%">========</div>
            </div>   --}}
            @endforeach
        </div>
    </div>
    
</div>

