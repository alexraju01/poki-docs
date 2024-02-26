
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokemon</title>
    <link rel="stylesheet" href="{{ asset('css/pokemon-types.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
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
