<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
        * { 
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            text-decoration: none;
            outline: none;
            font-family: "Gill Sans", "Gill Sans MT";
        }

        html {
            font-size: 62.5%;
        }

        .container {
            width: 100%;
            height: 100%;
            background-color: white;
            overflow: hidden;
        }

        nav {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 10;
            width: 100%;
            height: 12rem;
            padding: 0 15rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: aquamarine;
        }

        .nav-items {
            width: 50%;
            display: flex;
            justify-content: space-evenly;
            align-items: center;
        }

        .nav-item {
            list-style: none;
            position: relative;
        }

        .nav-link {
            font-size: 2rem;
            text-transform: uppercase;
            color: green;
            letter-spacing: 0.1rem;
            text-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.2);
        }


        header {
            width: 100%;
            height: 100vh;
            position: relative;
        }

        .banner {
            display: flex;
            justify-content: space-between;
            width: 40rem;
            
        }

        .banner h1, .id {
        font-size: 5rem;
        color: black;
        text-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.3);
        }

        .stats{
            font-size: 5rem
        }

        .stat{
            background-color: green;
            width: 40rem;
        }


    </style>
</head>
<body>
    <div class="container">
        {{-- <nav>
        <ul class='nav-items'>
            <li class='nav-item'><a class="nav-link" href="{{ route('pokemons.index') }}"></a>Home</li>
            <li class='nav-item'><a class="nav-link" href="#"></a>Dashboard</li>
            <li class='nav-item'><a class="nav-link" href=""></a></li>
        </ul>
    </nav> --}}

    <div class="pokemon">
        <div class="banner">
            <h1>{{ ucfirst($pokemonInfo['name']) }}</h1>
            <span class='id'> #{{$pokemon['id']}}</span>
        </div>

        <div class="stats">
                @foreach($pokemon['stats'] as $stat)
                <div class="stat">
                    <span>{{ $stat['stat']['name'] }}:</span>
                    <span>{{ $stat['base_stat'] }}</span>
                </div>
                @endforeach
        </div>
    </div>

    </div>




</body>
</html>