@extends('layouts.app')

@section('content')
<h1 id="Title">Pokemon List (Topsssssss {{$limit}})</h1>
    <div class="card-container">
        @foreach($pokemons as $pokemon)
        <a href="{{ route('pokemons.show', ['pokemon' => $pokemon['id']]) }}">
         <div class="card"
         onerror="this.style.display='none';">
             <p>{{$pokemon['id']}}</p>
             <img src="{{ $pokemon['sprite'] }}" alt="{{ ucfirst($pokemon['name']) }}"
             onerror="this.closest('a').remove();">
             <p>{{ ucfirst($pokemon['name'])}}</p>
         </div>
     </a>
     @endforeach
 </div>


@endsection

