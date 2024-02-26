<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('css/pokemon-types.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">



    <style>
        


    </style>
</head>
<body>

    
    <div class="pokemon">
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
    
            <div class="content">
                <img class="poke-img poke-backdrop-{{$pokemon['type']}}" src="{{$pokemon['sprite']}}" alt="">
            </div>
        </div>
        
    </div>
    
  

</body>
</html>