<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <meta name="description" content="@yield('description')">
    <link rel="icon" href="/img/favicon.svg">
    
    <meta property="og:title" content="@yield('title')" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://<?= $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] ?>" />
    <meta property="og:image" content=""/>
    <meta property="og:site_name" content="" />
    <meta property="og:description" content="@yield('description')" />
</head>
<body>
    @yield('content') 
</body>
</html>