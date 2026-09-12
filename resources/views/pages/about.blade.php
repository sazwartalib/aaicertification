<x-layouts.app title="About Us" description="About {{ config('app.name') }} — an accredited certification body and professional training provider.">
    <section class="relative overflow-hidden bg-navy-950 py-16">
        <div class="absolute inset-0 bg-grid"></div>
        <div class="relative mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <span class="eyebrow border-navy-700 bg-navy-900/60 text-navy-200">
                <x-ui-icon name="sparkles" class="h-3.5 w-3.5 text-gold-400" />
                About us
            </span>
            <h1 class="mt-5 text-4xl font-bold text-white">About {{ config('app.name') }}</h1>
            <p class="mt-4 max-w-2xl text-lg text-navy-200">
                We are an independent certification body and training provider. Our purpose is simple: raise
                the standard of professional competence, and make that competence verifiable.
            </p>
        </div>
    </section>

    <section class="mx-auto max-w-4xl px-4 py-14 sm:px-6 lg:px-8">
        <div class="prose-navy max-w-none">
            <h3>Who we are</h3>
            <p>
                Founded to serve organisations that operate to international standards, {{ config('app.name') }}
                combines hands-on instructor-led training with formal, impartial certification. Our trainers are
                practising lead auditors, security practitioners, and project professionals — not career
                lecturers.
            </p>

            <h3>How certification works</h3>
        </div>

        <ol class="mt-6 space-y-4">
            @foreach ([
                'Enrol in a course and complete the instructor-led training.',
                'Pass the continuous assessment and final examination.',
                'Receive a certificate carrying a unique, permanent certificate number.',
                'Anyone can confirm that certificate through our public verification portal.',
            ] as $i => $step)
                <li data-reveal style="--reveal-delay: {{ $i * 70 }}ms" class="flex gap-4 rounded-xl border border-navy-100 bg-navy-50/40 p-4">
                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-navy-900 text-sm font-bold text-gold-400">{{ $i + 1 }}</span>
                    <p class="text-sm leading-relaxed text-navy-700">{{ $step }}</p>
                </li>
            @endforeach
        </ol>

        <div class="prose-navy mt-10 max-w-none">
            <h3>Accreditation</h3>
            <p>
                Our programmes are aligned with ISO 9001, ISO/IEC 27001, ISO 45001, and PMI frameworks, and
                selected courses are delivered under CQI&nbsp;|&nbsp;IRCA and PECB partnerships. HRD Corp
                claimable options are available for Malaysian employers.
            </p>
        </div>

        <div class="mt-12 grid gap-6 sm:grid-cols-3">
            @foreach ([
                ['icon' => 'shield-check', 'k' => 'Impartial', 'v' => 'Assessment is separated from training delivery.'],
                ['icon' => 'clipboard-check', 'k' => 'Practical', 'v' => 'Every course is built around real workplace scenarios.'],
                ['icon' => 'badge-check', 'k' => 'Verifiable', 'v' => 'Each certificate is independently checkable online.'],
            ] as $i => $value)
                <div data-reveal style="--reveal-delay: {{ $i * 80 }}ms" class="card-interactive">
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-navy-900 text-gold-400">
                        <x-ui-icon :name="$value['icon']" class="h-5 w-5" />
                    </span>
                    <h4 class="mt-4 font-semibold text-navy-900">{{ $value['k'] }}</h4>
                    <p class="mt-2 text-sm text-navy-600">{{ $value['v'] }}</p>
                </div>
            @endforeach
        </div>

        <div data-reveal class="relative mt-12 overflow-hidden rounded-2xl bg-navy-950 px-8 py-12 text-center">
            <div class="absolute inset-0 bg-grid opacity-70"></div>
            <div class="relative">
                <h2 class="text-2xl font-bold text-white">Ready to get certified?</h2>
                <a href="{{ route('courses.index') }}" class="btn-primary mt-6">
                    Browse courses
                    <x-ui-icon name="arrow-right" class="btn-arrow h-4 w-4" />
                </a>
            </div>
        </div>
    </section>
</x-layouts.app>
