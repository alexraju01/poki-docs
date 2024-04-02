<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="//unpkg.com/alpinejs" defer></script>
    

    
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/scss/pokeTypes.scss'])
    <!-- Head content like meta tags, title, and links to CSS files -->

    <title>Pokidocs</title>
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



