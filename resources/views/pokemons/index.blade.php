<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokemon</title>

    <style>
        * { 
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            text-decoration: none;
            outline: none;
            font-family: "Gill Sans", "Gill Sans MT";
        }
        .card {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            margin: 10px;
            width: 200px;
            text-align: center;
            display: inline-block;
        }
        .card img {
            scale: 1.3;
            margin-bottom: 10px;
            image-rendering: pixelated;

        }

        /* poki types for colour */
        .bug { background-color: #7DBD60; }
        .dark { background-color: #736C75 ; }
        .dragon { background-color: #6A7BAF;}
        .electric { background-color: #E5C531;}
        .fairy { background-color: #E397D1;}
        .fighting { background-color: #CB5F48;}
        .fire { background-color: #FFA07A;}
        .flying { background-color: #7DA6DE;}
        .ghost { background-color: #846AB6;}
        .grass { background-color: #A9E5BB;}
        .ground { background-color: #CC9F4F;}
        .ice { background-color: #70CBD4;}
        .normal { background-color: #AAB09F;}
        .poison { background-color: #B468B7;}
        .psychic { background-color: #E5709B;}
        .rock { background-color: #B2A061;}
        .steel { background-color: #89A1B0;}
        .water { background-color: #ADD8E6;}

    </style>
</head>
<body>
    <h1>Pokemon List</h1>
   
    @foreach($pokemons as $pokemon)
        <a href="{{ route('pokemons.show', ['pokemon' => $pokemon['id']]) }}">
            <div class="card {{ $pokemon['type'] }}">
                <p>{{$pokemon['id']}}</p>
                <img src="{{ $pokemon['sprite'] }}" alt="{{ ucfirst($pokemon['name']) }}">
                <p>{{ ucfirst($pokemon['name'])}}</p>
            </div>
        </a>
    @endforeach

   
    {{-- @foreach($pokemons as $pokemon)
        <div class="card {{ $pokemon['type'] }}">

                <img src="{{ $pokemon['sprite'] }}" alt="{{ ucfirst($pokemon['name']) }}">
                <p>{{ ucfirst($pokemon['name']) }}</p>
         
        </div>
    @endforeach --}}
</body>
</html>
