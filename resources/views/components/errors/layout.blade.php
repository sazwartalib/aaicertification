@props([
    'code',
    'title',
    'message',
    'icon' => 'exclamation-triangle',
])

{{--
    Shared shell for every HTTP error page, using the same navy/gold language as
    the rest of the site so an error never looks like a different application.
--}}
<x-layouts.app :title="$code.' — '.$title" :description="$message" robots="noindex, nofollow">
    <section class="relative flex min-h-[70vh] items-center overflow-hidden bg-navy-950 py-20">
        <div class="absolute inset-0 bg-grid" aria-hidden="true"></div>
        <div class="pointer-events-none absolute -right-24 -top-16 h-72 w-72 rounded-full bg-gold-500/15 blur-3xl" aria-hidden="true"></div>
        <div class="pointer-events-none absolute -bottom-24 -left-16 h-72 w-72 rounded-full bg-navy-700/40 blur-3xl" aria-hidden="true"></div>

        <div class="relative mx-auto max-w-2xl px-4 text-center sm:px-6 lg:px-8">
            <span class="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-navy-900 text-gold-400 ring-1 ring-inset ring-navy-700">
                <x-ui-icon :name="$icon" class="h-8 w-8" />
            </span>

            <p class="mt-8 font-mono text-sm font-semibold uppercase tracking-[0.35em] text-gold-400">Error {{ $code }}</p>
            <h1 class="mt-4 text-4xl font-bold tracking-tight text-white sm:text-5xl">{{ $title }}</h1>
            <p class="mx-auto mt-5 max-w-lg text-navy-200">{{ $message }}</p>

            <div class="mt-10 flex flex-wrap items-center justify-center gap-3">
                <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('home') }}" class="btn-secondary">
                    <x-ui-icon name="arrow-left" class="h-4 w-4" />
                    Go back
                </a>
                <a href="{{ route('home') }}" class="btn-primary">
                    <x-ui-icon name="home" class="h-4 w-4" />
                    Home
                </a>
            </div>

            <div class="mt-12 border-t border-navy-800 pt-8">
                <p class="text-sm text-navy-400">Looking for something specific?</p>
                <div class="mt-4 flex flex-wrap items-center justify-center gap-x-5 gap-y-2 text-sm">
                    <a href="{{ route('courses.index') }}" class="font-medium text-navy-200 transition hover:text-gold-400">Training courses</a>
                    <a href="{{ route('verify.index') }}" class="font-medium text-navy-200 transition hover:text-gold-400">Verify a certificate</a>
                    <a href="{{ route('contact') }}" class="font-medium text-navy-200 transition hover:text-gold-400">Contact us</a>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
