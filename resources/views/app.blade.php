<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">

    <title inertia>{{ config('app.name') }}</title>

    @fonts
    @vite(['resources/css/app.css', 'resources/js/inertia.js'])
    @inertiaHead
</head>
<body class="min-h-screen bg-navy-50 font-sans text-navy-900 antialiased">
    @inertia
</body>
</html>
