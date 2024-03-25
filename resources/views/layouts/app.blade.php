<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
    <script src="//unpkg.com/alpinejs" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/scss/pokeTypes.scss'])
    <!-- Head content like meta tags, title, and links to CSS files -->

    <title>Document</title>
</head>
<body>
    <x-preloader />

    {{-- <div class="flex-center position-ref full-height"> --}}
        @yield('content')
    {{-- </div> --}}

    <script>
        window.addEventListener('load', function() {
            document.getElementById('preloader').classList.add('hidden');
        });
    </script>
</body>
</html>



