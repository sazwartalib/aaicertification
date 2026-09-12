@props(['title' => null, 'description' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ? $title.' — '.config('app.name') : config('app.name').' — Certification & Training' }}</title>
    <meta name="description" content="{{ $description ?? 'Accredited certification and professional training programs delivered by '.config('app.name').'.' }}">

    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <noscript>
        <style>[data-reveal]{opacity:1 !important;transform:none !important}</style>
    </noscript>
</head>
<body class="flex min-h-screen flex-col bg-white">
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-50 focus:rounded-lg focus:bg-navy-900 focus:px-4 focus:py-2 focus:text-sm focus:font-semibold focus:text-white">
        Skip to content
    </a>

    <x-site-nav />

    <main id="main-content" class="flex-1">
        {{ $slot }}
    </main>

    <x-site-footer />
</body>
</html>
