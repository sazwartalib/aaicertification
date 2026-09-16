@php
    $states = [
        'valid' => [
            'label' => 'Verified & valid',
            'icon' => 'shield-check',
            'ring' => 'ring-emerald-400/30',
            'badge' => 'bg-emerald-500/15 text-emerald-300 ring-1 ring-inset ring-emerald-400/30',
            'accent' => 'text-emerald-400',
            'note' => 'This certificate was issued by '.config('app.name').' and is currently valid.',
        ],
        'expired' => [
            'label' => 'Expired',
            'icon' => 'clock',
            'ring' => 'ring-amber-400/30',
            'badge' => 'bg-amber-500/15 text-amber-300 ring-1 ring-inset ring-amber-400/30',
            'accent' => 'text-amber-400',
            'note' => 'This certificate was genuinely issued but its validity period has lapsed. Contact us about recertification.',
        ],
        'revoked' => [
            'label' => 'Revoked',
            'icon' => 'x-circle',
            'ring' => 'ring-rose-400/30',
            'badge' => 'bg-rose-500/15 text-rose-300 ring-1 ring-inset ring-rose-400/30',
            'accent' => 'text-rose-400',
            'note' => 'This certificate has been revoked by '.config('app.name').' and should not be accepted as proof of certification.',
        ],
    ];

    $current = $states[$state];
@endphp

<x-layouts.app
    :title="$certificate->recipient_name.' — Digital Certificate'"
    description="Digital certificate issued by {{ config('app.name') }}."
    robots="noindex, nofollow"
>
    {{-- Status hero --}}
    <section class="relative overflow-hidden bg-navy-950 py-14">
        <div class="absolute inset-0 bg-grid" aria-hidden="true"></div>
        <div class="pointer-events-none absolute -right-20 -top-16 h-72 w-72 rounded-full bg-gold-500/15 blur-3xl" aria-hidden="true"></div>

        <div class="relative mx-auto max-w-4xl px-4 text-center sm:px-6 lg:px-8">
            <span class="inline-flex items-center gap-2 rounded-full px-3.5 py-1.5 text-xs font-semibold uppercase tracking-widest {{ $current['badge'] }}">
                <x-ui-icon name="{{ $current['icon'] }}" class="h-3.5 w-3.5" />
                {{ $current['label'] }}
            </span>

            <h1 class="mt-6 font-serif text-4xl font-bold text-white sm:text-5xl">{{ $certificate->recipient_name }}</h1>
            <p class="mt-3 text-lg text-navy-200">{{ $certificate->course_title }}</p>
            <p class="mx-auto mt-4 max-w-xl text-sm text-navy-300">{{ $current['note'] }}</p>
        </div>
    </section>

    <section class="mx-auto max-w-4xl px-4 py-14 sm:px-6 lg:px-8">
        <div class="grid gap-6 lg:grid-cols-5">
            {{-- Credential details --}}
            <div class="card lg:col-span-3" data-reveal>
                <h2 class="text-base font-semibold text-navy-950">Credential details</h2>

                <dl class="mt-5 divide-y divide-navy-100 text-sm">
                    @php
                        $rows = array_filter([
                            ['Certificate number', $certificate->certificate_number, true],
                            ['Recipient', $certificate->recipient_name, false],
                            ['IC / Passport', $certificate->maskedIcNumber(), true],
                            ['Programme', $certificate->course_title, false],
                            ['Result', $certificate->grade, false],
                            ['Issued on', $certificate->issued_at->format('d F Y'), false],
                            ['Valid until', $certificate->expires_at?->format('d F Y') ?? 'No expiry', false],
                            ['Accredited by', $certificate->course?->accreditation_body, false],
                        ], fn (array $row): bool => filled($row[1]));
                    @endphp

                    @foreach ($rows as [$label, $value, $mono])
                        <div class="flex flex-wrap items-baseline justify-between gap-2 py-3">
                            <dt class="text-navy-500">{{ $label }}</dt>
                            <dd @class(['font-semibold text-navy-900', 'font-mono text-xs tracking-wider' => $mono])>{{ $value }}</dd>
                        </div>
                    @endforeach
                </dl>

                @if ($certificate->course && $certificate->course->is_published)
                    <a href="{{ route('courses.show', $certificate->course) }}"
                       class="mt-5 inline-flex items-center gap-1.5 text-sm font-semibold text-navy-700 transition hover:text-navy-900">
                        View this programme
                        <x-ui-icon name="arrow-right" class="h-4 w-4" />
                    </a>
                @endif
            </div>

            {{-- QR + share --}}
            <div class="space-y-6 lg:col-span-2">
                <div class="card text-center" data-reveal>
                    <h2 class="text-base font-semibold text-navy-950">Share this credential</h2>
                    <p class="mt-1 text-xs text-navy-500">Scan or copy the link to share proof of certification.</p>

                    <div class="mt-5 flex justify-center">
                        <div
                            data-qr="{{ $certificate->publicUrl() }}"
                            data-qr-pending="true"
                            class="h-40 w-40 rounded-lg border border-navy-200 bg-white p-3"
                        ></div>
                    </div>

                    <button type="button"
                            data-copy="{{ $certificate->publicUrl() }}"
                            class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-lg border border-navy-200 px-4 py-2.5 text-sm font-semibold text-navy-700 transition hover:bg-navy-50">
                        <x-ui-icon name="clipboard" class="h-4 w-4" />
                        <span data-copy-label>Copy link</span>
                    </button>
                </div>

                <div class="card" data-reveal>
                    <h2 class="text-base font-semibold text-navy-950">Verify independently</h2>
                    <p class="mt-1 text-sm text-navy-600">
                        Prefer not to trust a scanned link? Look the certificate number up yourself in our public register.
                    </p>
                    <a href="{{ route('verify.index', ['number' => $certificate->certificate_number]) }}"
                       class="btn-navy btn-block mt-4">
                        <x-ui-icon name="shield-check" class="h-4 w-4" />
                        Check the register
                    </a>
                </div>
            </div>
        </div>

        <p class="mt-8 text-center text-xs text-navy-400">
            Issued by {{ config('app.name') }}. This page is private and not indexed by search engines.
        </p>
    </section>
</x-layouts.app>
