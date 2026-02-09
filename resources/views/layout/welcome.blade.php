<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portfolio Demo</title>


    @vite(['resources/sass/app.scss', 'resources/sass/app2.scss', 'resources/js/app.js'])
</head>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;1,600&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    @include('layout.navbar')

    @yield('content')

</body>
</html>






