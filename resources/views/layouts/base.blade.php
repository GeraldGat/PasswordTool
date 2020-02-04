<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('img/Fav.png') }}" />
    
    <title>@yield('title', config('app.name', 'Laravel'))</title>

    @section('topAssets')
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://kit.fontawesome.com/8eee44d981.js" crossorigin="anonymous"></script>
    @show
</head>
<body class="@yield('bodyClasses')">
    <div class="container-fluid">
        @yield('pageBody')
    </div>

    @section('endAssets')
    <script src="{{ asset('js/app.js') }}"></script>
    @show
</body>
</html>