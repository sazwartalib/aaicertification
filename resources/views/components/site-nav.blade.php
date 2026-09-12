@php
    $links = [
        ['label' => 'Home', 'route' => 'home', 'active' => request()->routeIs('home')],
        ['label' => 'Courses', 'route' => 'courses.index', 'active' => request()->routeIs('courses.*')],
        ['label' => 'Verify Certificate', 'route' => 'verify.index', 'active' => request()->routeIs('verify.*')],
        ['label' => 'About', 'route' => 'about', 'active' => request()->routeIs('about')],
        ['label' => 'Contact', 'route' => 'contact', 'active' => request()->routeIs('contact')],
    ];
@endphp

<header
    data-site-nav
    class="group sticky top-0 z-40 border-b border-navy-800/60 bg-navy-950/95 backdrop-blur transition-shadow duration-300 supports-[backdrop-filter]:bg-navy-950/80"
>
    <input type="checkbox" id="nav-toggle" class="peer hidden">

    <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
        <a href="{{ route('home') }}" class="group flex items-center gap-3">
            <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-gold-500 font-bold text-navy-950 shadow-sm transition-transform duration-200 group-hover:-rotate-6 group-hover:scale-105">AAI</span>
            <span class="flex flex-col leading-tight">
                <span class="text-base font-semibold text-white">{{ config('app.name') }}</span>
                <span class="text-xs uppercase tracking-widest text-navy-300">Certification &amp; Training</span>
            </span>
        </a>

        <nav class="hidden items-center gap-1 lg:flex">
            @foreach ($links as $link)
                <a
                    href="{{ route($link['route']) }}"
                    @class([
                        'relative rounded-md px-3 py-2 text-sm font-medium transition-colors',
                        'after:absolute after:inset-x-3 after:-bottom-0.5 after:h-0.5 after:rounded-full after:bg-gold-400 after:transition-transform after:duration-200',
                        'text-white after:scale-x-100' => $link['active'],
                        'text-navy-200 hover:text-white after:scale-x-0 hover:after:scale-x-100' => ! $link['active'],
                    ])
                >{{ $link['label'] }}</a>
            @endforeach
            <a href="{{ route('courses.index') }}" class="ml-2 inline-flex items-center gap-1.5 rounded-md bg-gold-500 px-4 py-2 text-sm font-semibold text-navy-950 transition-colors hover:bg-gold-400">
                Enrol Now
                <x-ui-icon name="arrow-right" class="h-4 w-4" />
            </a>
        </nav>

        <label for="nav-toggle" class="inline-flex cursor-pointer items-center justify-center rounded-md p-2 text-navy-200 transition hover:bg-navy-800 hover:text-white lg:hidden">
            <x-ui-icon name="menu" class="h-6 w-6 group-has-[:checked]:hidden" />
            <x-ui-icon name="close" class="hidden h-6 w-6 group-has-[:checked]:block" />
            <span class="sr-only">Toggle menu</span>
        </label>
    </div>

    {{-- Mobile menu: animated open/close --}}
    <div class="grid grid-rows-[0fr] overflow-hidden border-navy-800 transition-[grid-template-rows] duration-300 ease-out peer-checked:grid-rows-[1fr] peer-checked:border-t lg:!hidden">
        <div class="min-h-0">
            <nav class="space-y-1 px-4 py-4 sm:px-6">
                @foreach ($links as $link)
                    <a
                        href="{{ route($link['route']) }}"
                        data-nav-link
                        @class([
                            'block rounded-md px-3 py-2.5 text-base font-medium transition-colors',
                            'bg-navy-800 text-white' => $link['active'],
                            'text-navy-200 hover:bg-navy-800/60 hover:text-white' => ! $link['active'],
                        ])
                    >{{ $link['label'] }}</a>
                @endforeach
                <a href="{{ route('courses.index') }}" data-nav-link class="mt-2 flex items-center justify-center gap-1.5 rounded-md bg-gold-500 px-3 py-2.5 text-center text-base font-semibold text-navy-950">
                    Enrol Now
                    <x-ui-icon name="arrow-right" class="h-4 w-4" />
                </a>
            </nav>
        </div>
    </div>
</header>
