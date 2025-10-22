<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Education Materials') - LearnIT</title>
    <meta name="description" content="@yield('description', 'Образовательная платформа с материалами по программированию и IT')">
    <meta property="og:title" content="@yield('title', 'Education Materials') - LearnIT">
    <meta property="og:description" content="@yield('description', 'Образовательная платформа с материалами по программированию и IT')">
    
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    @include('includes.header')
    
    @yield('content')
</body>
</html>