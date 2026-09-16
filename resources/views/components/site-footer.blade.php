<footer class="mt-24 bg-navy-950 text-navy-200">
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <div class="grid gap-10 md:grid-cols-2 lg:grid-cols-4">
            <div class="lg:col-span-2">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-gold-500 font-bold text-navy-950">AAI</span>
                    <span class="text-base font-semibold text-white">{{ config('app.name') }}</span>
                </div>
                <p class="mt-4 max-w-md text-sm leading-relaxed text-navy-300">
                    An accredited certification body and professional training provider. We help individuals and
                    organisations build competence, meet compliance requirements, and earn credentials that are
                    recognised and independently verifiable.
                </p>
                <div class="mt-6 flex gap-3">
                    @foreach ([
                        ['label' => 'LinkedIn', 'href' => '#', 'd' => 'M4.98 3.5A2.5 2.5 0 1 1 0 3.5a2.5 2.5 0 0 1 4.98 0ZM.5 8h4V24h-4V8Zm7.5 0h3.8v2.2h.05c.53-1 1.83-2.2 3.77-2.2 4.03 0 4.78 2.65 4.78 6.1V24h-4v-6.9c0-1.65-.03-3.77-2.3-3.77-2.3 0-2.65 1.8-2.65 3.65V24h-4V8Z'],
                        ['label' => 'Facebook', 'href' => '#', 'd' => 'M24 12.07C24 5.4 18.63 0 12 0S0 5.4 0 12.07C0 18.1 4.39 23.1 10.13 24v-8.44H7.08v-3.49h3.05V9.41c0-3.02 1.79-4.69 4.53-4.69 1.31 0 2.68.24 2.68.24v2.97h-1.51c-1.49 0-1.96.93-1.96 1.89v2.26h3.33l-.53 3.49h-2.8V24C19.61 23.1 24 18.1 24 12.07Z'],
                        ['label' => 'YouTube', 'href' => '#', 'd' => 'M23.5 6.2a3 3 0 0 0-2.11-2.13C19.5 3.55 12 3.55 12 3.55s-7.5 0-9.39.52A3 3 0 0 0 .5 6.2 31.3 31.3 0 0 0 0 12a31.3 31.3 0 0 0 .5 5.8 3 3 0 0 0 2.11 2.13c1.89.52 9.39.52 9.39.52s7.5 0 9.39-.52a3 3 0 0 0 2.11-2.13A31.3 31.3 0 0 0 24 12a31.3 31.3 0 0 0-.5-5.8ZM9.6 15.6V8.4l6.2 3.6-6.2 3.6Z'],
                    ] as $social)
                        <a href="{{ $social['href'] }}" aria-label="{{ $social['label'] }}"
                           class="flex h-9 w-9 items-center justify-center rounded-lg border border-navy-800 text-navy-300 transition hover:border-gold-500 hover:text-gold-400">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="{{ $social['d'] }}" /></svg>
                        </a>
                    @endforeach
                </div>
            </div>

            <div>
                <h3 class="text-sm font-semibold uppercase tracking-widest text-white">Explore</h3>
                <ul class="mt-4 space-y-2.5 text-sm">
                    <li><a href="{{ route('courses.index') }}" class="inline-flex items-center gap-1.5 transition hover:text-white">Training Courses</a></li>
                    <li><a href="{{ route('verify.index') }}" class="inline-flex items-center gap-1.5 transition hover:text-white">Verify a Certificate</a></li>
                    <li><a href="{{ route('about') }}" class="inline-flex items-center gap-1.5 transition hover:text-white">About Us</a></li>
                    <li><a href="{{ route('contact') }}" class="inline-flex items-center gap-1.5 transition hover:text-white">Contact</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-sm font-semibold uppercase tracking-widest text-white">Get in touch</h3>
                <ul class="mt-4 space-y-3 text-sm">
                    <li class="flex gap-3">
                        <x-ui-icon name="map-pin" class="mt-0.5 h-4 w-4 shrink-0 text-gold-400" />
                        <span>Level 12, Menara Training<br>Kuala Lumpur, Malaysia</span>
                    </li>
                    <li class="flex gap-3">
                        <x-ui-icon name="envelope" class="mt-0.5 h-4 w-4 shrink-0 text-gold-400" />
                        <a href="mailto:training@aaicertification.test" class="transition hover:text-white">training@aaicertification.test</a>
                    </li>
                    <li class="flex gap-3">
                        <x-ui-icon name="phone" class="mt-0.5 h-4 w-4 shrink-0 text-gold-400" />
                        <a href="tel:+60322000000" class="transition hover:text-white">+60 3-2200 0000</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="mt-12 flex flex-col gap-3 border-t border-navy-800 pt-6 text-xs text-navy-400 sm:flex-row sm:items-center sm:justify-between">
            <p class="flex flex-wrap items-center gap-x-2 gap-y-1">
                <span>&copy; {{ now()->year }} {{ config('app.name') }}. All rights reserved.</span>
                <span aria-hidden="true">·</span>
                <a href="{{ auth()->check() ? route('admin.dashboard') : route('login') }}" class="inline-flex items-center gap-1.5 transition hover:text-white">
                    <x-ui-icon name="lock-closed" class="h-3.5 w-3.5" />
                    {{ auth()->check() ? 'Admin console' : 'Staff login' }}
                </a>
            </p>
            <p class="flex flex-wrap items-center gap-x-2 gap-y-1">
                <span class="inline-flex items-center gap-1.5"><x-ui-icon name="shield-check" class="h-3.5 w-3.5 text-gold-400" /> ISO 9001</span>
                <span aria-hidden="true">·</span>
                <span>ISO/IEC 27001</span>
                <span aria-hidden="true">·</span>
                <span>ISO 45001 accredited training partner</span>
            </p>
        </div>
    </div>

    <button
        data-back-to-top
        type="button"
        aria-label="Back to top"
        class="fixed bottom-6 right-6 z-40 flex h-11 w-11 translate-y-2 items-center justify-center rounded-full bg-gold-500 text-navy-950 opacity-0 shadow-lg transition-all duration-300 hover:bg-gold-400"
    >
        <x-ui-icon name="chevron-up" class="h-5 w-5" />
    </button>
</footer>
