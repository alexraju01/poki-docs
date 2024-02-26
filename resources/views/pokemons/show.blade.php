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
/* 
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

        .pokemon{
            width: 100vh;
        }
        .content{
            display: flex;
            justify-content: space-between;
            width: 100%;
            background-color: brown
        }

        .poke-img{
            width: 15rem;
            background-color: black
        } */
        /* Style for the pokemon card container */
.pokemon {
    width: 100%;
    padding: 10px;
    margin: 10px;
    background-color: #f9f9f9;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Style for the banner */
.banner {
    display: flex;
    flex-direction: column;
    background-color: #ffcb05;
    padding: 10px;
    border-radius: 8px 8px 0 0;
    flex-direction: 
}

.banner h1 {
    font-size: 24px;
    margin: 0;
    color: #333;
    align-self: flex-end;
}

.banner .id {
    font-size: 16px;
    color: #666;
    align-self: flex-end;

}

/* Style for the content */
.content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px;
}

/* Style for the stats */
.stats {
    font-size: 3rem;
    flex-grow: 1;
}

.stat {
    margin-bottom: 5px;
}

.stat span:first-child {
    font-weight: bold;
}

/* Style for the Pokemon image */
.poke-img {
    width: 20rem;
    height: 20rem;
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

        <div class="content">
            <div class="stats">
                @foreach($pokemon['stats'] as $stat)
                <div class="stat">
                    <span>{{ $stat['stat']['name'] }}:</span>
                    <span>{{ $stat['base_stat'] }}</span>
                </div>
                @endforeach
            </div>

            <img class="poke-img" src="{{$pokemon['sprite']}}" alt="">
        </div>
    </div>

    </div>




</body>
</html>