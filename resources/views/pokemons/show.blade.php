<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('css/pokemon-types.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

</head>
<body>

    <div class="pokemon">
        <div class="poke-img">
            <div class="poke-id">
              <p>#{{$pokemonInfo['id']}}</p>
            </div>
            <img
                class="poke-backdrop-{{$pokemonInfo['types'][0]}}"
              src="{{$pokemonInfo['sprite']}}"
              alt="Pokemon Image"
            />
            <span class="poke-name">{{ucfirst($pokemonInfo['name'])}}</span>
            <span class="poke-genus">{{$pokemonInfo['genus']}}</span>
            <div class="poke-types">
                @foreach ($pokemonInfo['types'] as $type)
                    <button class="button-{{$type}} ">{{ucfirst($type)}}</button>
                @endforeach
            </div>
          </div>

        <div class="stats">
            @foreach($pokemonInfo['stats'] as $stat)
                <div class="stat">
                    <span class="name">{{ str_replace('-', ' ', strtoupper($stat['stat']['name'])) }}:&nbsp; {{ $stat['base_stat'] }}</span>
                    <span class="bar"></span>
                </div>  
            @endforeach
        </div>
        
      </div>

    {{-- <div class="container">
        <header></header>
        <nav></nav>
        <main></main>
        <aside></aside>
        <footer></footer>
    </div> --}}

    
    {{-- <div class="pokemon">
        <div class="poke-identifier">
            <div class="poke-heading border-{{$pokemon['type']}}">
                <div class="poke-nameid">
                    <h1>{{ ucfirst($pokemonInfo['name']) }}</h1>
                    <span class='id'> #{{$pokemon['id']}}</span>
                </div>
                <div class="poke-type">Type: {{$pokemon['type']}}</div>
            </div>
            <div class="poke-search">
                <input type="text" placeholder="Search..." class="search">Search
            </div>
        </div>
        <div class=poke-data>
            <div class="stats">
                
                @foreach($pokemon['stats'] as $stat)
                <div class="stat">
                    <span class="name">{{ str_replace('-', ' ', strtoupper($stat['stat']['name'])) }}:&nbsp; {{ $stat['base_stat'] }}</span>
                    <span class="bar"></span>
                </div>
                
                @endforeach
            </div>
    
            <div class="poke-img">
                <img class="poke-backdrop-{{$pokemon['type']}}" src="{{$pokemon['sprite']}}" alt="">
            </div>
        </div>
        <div class="poke-tabs">
        </div>
    
    </div>
     --}}
  

</body>
</html>