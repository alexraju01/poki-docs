<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokémon Details</title>
    <link rel="stylesheet" href="{{ asset('css/pokemon-types.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    {{-- @stack('styles') --}}
</head>
<body>

<div class="container">
    @yield('content')
</div>

@stack('scripts')
</body>
</html>
