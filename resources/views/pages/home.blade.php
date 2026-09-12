<x-layouts.app :description="'Accredited certification and instructor-led training in quality, information security, safety, and project management from '.config('app.name').'.'">
    {{-- Hero --}}
    <section class="relative overflow-hidden bg-navy-950">
        <div class="absolute inset-0 bg-grid"></div>
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,rgba(230,169,43,0.20),transparent_55%)]"></div>
        <div class="pointer-events-none absolute -left-24 top-1/3 h-72 w-72 rounded-full bg-navy-500/20 blur-3xl motion-safe:animate-float"></div>
        <div class="pointer-events-none absolute -right-16 -top-10 h-64 w-64 rounded-full bg-gold-500/15 blur-3xl motion-safe:animate-float [animation-delay:-4s]"></div>

        <div class="relative mx-auto grid max-w-7xl gap-12 px-4 py-20 sm:px-6 lg:grid-cols-2 lg:items-center lg:py-28 lg:px-8">
            <div data-reveal>
                <span class="inline-flex items-center gap-2 rounded-full border border-navy-700 bg-navy-900/60 px-3 py-1 text-xs font-medium uppercase tracking-widest text-navy-200">
                    <x-ui-icon name="sparkles" class="h-3.5 w-3.5 text-gold-400" />
                    Accredited Certification Body
                </span>
                <h1 class="mt-6 text-4xl font-bold leading-tight text-white sm:text-5xl">
                    Build competence. Earn credentials that <span class="text-gold-400">stand up to scrutiny.</span>
                </h1>
                <p class="mt-6 max-w-xl text-lg leading-relaxed text-navy-200">
                    {{ config('app.name') }} delivers instructor-led training and independently verifiable
                    certification across quality, information security, occupational safety, and project
                    management — for professionals and for teams.
                </p>
                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="{{ route('courses.index') }}" class="btn-primary">
                        Browse training courses
                        <x-ui-icon name="arrow-right" class="btn-arrow h-4 w-4" />
                    </a>
                    <a href="{{ route('verify.index') }}" class="btn-secondary">
                        <x-ui-icon name="shield-check" class="h-4 w-4" />
                        Verify a certificate
                    </a>
                </div>
            </div>

            <dl data-reveal style="--reveal-delay: 120ms" class="grid grid-cols-3 gap-4 rounded-2xl border border-navy-800 bg-navy-900/50 p-6 backdrop-blur">
                <div class="text-center">
                    <dt class="text-3xl font-bold text-gold-400" data-count-to="{{ $stats['courses'] }}">0</dt>
                    <dd class="mt-1 text-xs uppercase tracking-wide text-navy-300">Courses</dd>
                </div>
                <div class="text-center">
                    <dt class="text-3xl font-bold text-gold-400" data-count-to="{{ $stats['certificates'] }}" data-count-suffix="+">0</dt>
                    <dd class="mt-1 text-xs uppercase tracking-wide text-navy-300">Certificates issued</dd>
                </div>
                <div class="text-center">
                    <dt class="text-3xl font-bold text-gold-400" data-count-to="{{ $stats['categories'] }}">0</dt>
                    <dd class="mt-1 text-xs uppercase tracking-wide text-navy-300">Disciplines</dd>
                </div>
            </dl>
        </div>

        {{-- Trust bar --}}
        <div class="relative border-t border-navy-800/70 bg-navy-950/60">
            <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-center gap-x-8 gap-y-3 px-4 py-5 text-xs font-medium uppercase tracking-widest text-navy-400 sm:px-6 lg:px-8">
                <span class="text-navy-500">Aligned with</span>
                @foreach (['ISO 9001', 'ISO/IEC 27001', 'ISO 45001', 'PMI', 'CQI | IRCA', 'PECB'] as $standard)
                    <span class="flex items-center gap-1.5 text-navy-300">
                        <x-ui-icon name="badge-check" class="h-4 w-4 text-gold-500" />
                        {{ $standard }}
                    </span>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Services --}}
    <section class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
        <div class="max-w-2xl" data-reveal>
            <span class="eyebrow">What we do</span>
            <h2 class="mt-4 text-3xl font-bold text-navy-900">Train, certify, verify</h2>
            <p class="mt-4 text-navy-600">
                Three connected services — train your people, certify their competence, and let anyone
                confirm that certificate is genuine.
            </p>
        </div>

        <div class="mt-12 grid gap-6 md:grid-cols-3">
            @foreach ([
                ['icon' => 'academic-cap', 'title' => 'Instructor-led training', 'body' => 'Live virtual, in-person, and hybrid courses delivered by practising auditors and subject-matter experts.'],
                ['icon' => 'badge-check', 'title' => 'Professional certification', 'body' => 'Assessment and certification against international standards, with a unique certificate number for every candidate.'],
                ['icon' => 'shield-check', 'title' => 'Independent verification', 'body' => 'A public verification portal so employers and regulators can confirm any certificate in seconds.'],
            ] as $i => $service)
                <div data-reveal style="--reveal-delay: {{ $i * 90 }}ms" class="card-interactive">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-navy-900 text-gold-400">
                        <x-ui-icon :name="$service['icon']" class="h-6 w-6" />
                    </div>
                    <h3 class="mt-4 text-lg font-semibold text-navy-900">{{ $service['title'] }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-navy-600">{{ $service['body'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Featured courses --}}
    @if ($featuredCourses->isNotEmpty())
        <section class="bg-navy-50/60 py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex flex-wrap items-end justify-between gap-4" data-reveal>
                    <div class="max-w-2xl">
                        <span class="eyebrow">Popular this intake</span>
                        <h2 class="mt-4 text-3xl font-bold text-navy-900">Featured programs</h2>
                        <p class="mt-4 text-navy-600">Our most requested certification courses this intake.</p>
                    </div>
                    <a href="{{ route('courses.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-navy-700 transition hover:text-navy-900">
                        View all courses
                        <x-ui-icon name="arrow-right" class="h-4 w-4" />
                    </a>
                </div>

                <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($featuredCourses as $i => $course)
                        <div data-reveal style="--reveal-delay: {{ ($i % 3) * 80 }}ms">
                            <x-course-card :course="$course" />
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Disciplines --}}
    <section class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
        <div data-reveal>
            <span class="eyebrow">Browse by field</span>
            <h2 class="mt-4 text-3xl font-bold text-navy-900">Training disciplines</h2>
        </div>
        <div class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($categories as $i => $category)
                <a href="{{ route('courses.index', ['category' => $category->slug]) }}"
                   data-reveal style="--reveal-delay: {{ ($i % 3) * 70 }}ms"
                   class="group flex items-center justify-between gap-3 rounded-xl border border-navy-100 px-5 py-4 transition hover:-translate-y-0.5 hover:border-navy-300 hover:bg-navy-50 hover:shadow-sm">
                    <span class="flex items-center gap-3">
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-navy-50 text-navy-600 transition group-hover:bg-navy-900 group-hover:text-gold-400">
                            <x-ui-icon name="clipboard-check" class="h-4 w-4" />
                        </span>
                        <span class="font-medium text-navy-900">{{ $category->name }}</span>
                    </span>
                    <span class="flex items-center gap-2 text-sm text-navy-500">
                        {{ $category->published_courses_count }}
                        <x-ui-icon name="arrow-up-right" class="h-4 w-4 opacity-0 transition group-hover:translate-x-0.5 group-hover:opacity-100" />
                    </span>
                </a>
            @endforeach
        </div>
    </section>

    {{-- CTA --}}
    <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div data-reveal class="relative overflow-hidden rounded-2xl bg-navy-950 px-8 py-14 text-center">
            <div class="absolute inset-0 bg-grid opacity-70"></div>
            <div class="pointer-events-none absolute -right-10 top-0 h-48 w-48 rounded-full bg-gold-500/15 blur-3xl"></div>
            <div class="relative">
                <h2 class="text-3xl font-bold text-white">Training a whole team?</h2>
                <p class="mx-auto mt-4 max-w-2xl text-navy-200">
                    We run closed in-house cohorts tailored to your standards, systems, and schedule.
                    Tell us what you need and we will build the programme.
                </p>
                <a href="{{ route('contact') }}" class="btn-primary mt-8">
                    Talk to our training team
                    <x-ui-icon name="arrow-right" class="btn-arrow h-4 w-4" />
                </a>
            </div>
        </div>
    </section>
</x-layouts.app>
