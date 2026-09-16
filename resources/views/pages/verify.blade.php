@php
    use App\Enums\CertificateStatus;
@endphp

<x-layouts.app title="Verify a Certificate" description="Confirm the authenticity of a certificate issued by {{ config('app.name') }}.">
    <section class="relative overflow-hidden bg-navy-950 py-16">
        <div class="absolute inset-0 bg-grid"></div>
        <div class="pointer-events-none absolute -right-16 -top-10 h-64 w-64 rounded-full bg-gold-500/15 blur-3xl"></div>
        <div class="relative mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <span class="eyebrow border-navy-700 bg-navy-900/60 text-navy-200">
                <x-ui-icon name="shield-check" class="h-3.5 w-3.5 text-gold-400" />
                Certificate register
            </span>
            <h1 class="mt-5 text-4xl font-bold text-white">Verify a certificate</h1>
            <p class="mt-4 text-navy-200">
                Enter the certificate number exactly as printed on the document to confirm it was issued by
                {{ config('app.name') }} and is currently valid.
            </p>

            <form method="GET" action="{{ route('verify.index') }}" class="mt-8 flex flex-col gap-3 sm:flex-row">
                <div class="relative flex-1">
                    <x-ui-icon name="badge-check" class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-navy-400" />
                    <input
                        id="certificate-number"
                        type="text"
                        name="number"
                        value="{{ $number }}"
                        placeholder="AAI-YYYY-NNNNN"
                        autofocus
                        autocomplete="off"
                        class="w-full rounded-lg border border-navy-700 bg-navy-900 py-3 pl-11 pr-4 font-mono text-white placeholder-navy-500 focus:border-gold-500 focus:ring-2 focus:ring-gold-500/40"
                    >
                </div>
                <button type="submit" class="btn-primary shrink-0">
                    <x-ui-icon name="shield-check" class="h-4 w-4" />
                    Verify
                </button>
            </form>

            <div class="mt-4 flex flex-wrap items-center gap-2 text-xs text-navy-400">
                <span>Try an example:</span>
                @foreach (['AAI-2025-00001', 'AAI-2024-00742', 'AAI-2023-00318'] as $example)
                    <button type="button"
                        data-fill-target="#certificate-number" data-fill-value="{{ $example }}"
                        class="rounded-full border border-navy-700 px-2.5 py-1 font-mono text-navy-200 transition hover:border-gold-500 hover:text-gold-400">
                        {{ $example }}
                    </button>
                @endforeach
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-3xl px-4 py-14 sm:px-6 lg:px-8">
        @if ($searched && ! $certificate)
            <div data-reveal class="flex flex-col items-center rounded-xl border border-rose-200 bg-rose-50 p-8 text-center">
                <span class="flex h-14 w-14 items-center justify-center rounded-full bg-rose-100 text-rose-600">
                    <x-ui-icon name="x-circle" class="h-8 w-8" />
                </span>
                <h2 class="mt-4 text-lg font-semibold text-rose-800">No certificate found</h2>
                <p class="mt-2 max-w-md text-sm text-rose-700">
                    We could not find a certificate with the number
                    <span class="font-mono font-semibold">{{ $number }}</span>.
                    Check for typos, or <a href="{{ route('contact') }}" class="font-semibold underline">contact us</a>
                    if you believe this is an error.
                </p>
            </div>
        @elseif ($certificate)
            @php
                $revoked = $certificate->status === CertificateStatus::Revoked;
                $expired = $certificate->isExpired();
                $valid = $certificate->isValid();
            @endphp

            <div data-reveal @class([
                'overflow-hidden rounded-xl border',
                'border-green-200 bg-green-50' => $valid,
                'border-amber-200 bg-amber-50' => $expired && ! $revoked,
                'border-rose-200 bg-rose-50' => $revoked,
            ])>
                <div @class([
                    'flex items-center gap-4 px-6 py-5',
                    'bg-green-100/70' => $valid,
                    'bg-amber-100/70' => $expired && ! $revoked,
                    'bg-rose-100/70' => $revoked,
                ])>
                    @if ($valid)
                        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-green-600 text-white motion-safe:animate-[fade-in_0.4s_ease-out]">
                            <x-ui-icon name="check-circle" class="h-7 w-7" />
                        </span>
                        <div>
                            <h2 class="text-lg font-semibold text-green-800">Valid certificate</h2>
                            <p class="text-sm text-green-700">This credential is genuine and currently active.</p>
                        </div>
                    @elseif ($revoked)
                        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-rose-600 text-white">
                            <x-ui-icon name="x-circle" class="h-7 w-7" />
                        </span>
                        <div>
                            <h2 class="text-lg font-semibold text-rose-800">This certificate has been revoked</h2>
                            <p class="text-sm text-rose-700">It is no longer recognised by {{ config('app.name') }}.</p>
                        </div>
                    @else
                        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-amber-500 text-white">
                            <x-ui-icon name="exclamation-triangle" class="h-7 w-7" />
                        </span>
                        <div>
                            <h2 class="text-lg font-semibold text-amber-800">Certificate expired</h2>
                            <p class="text-sm text-amber-700">This credential was genuine but has passed its validity date.</p>
                        </div>
                    @endif
                </div>

                <dl class="grid gap-x-6 gap-y-5 px-6 py-6 text-sm sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <dt class="text-xs uppercase tracking-wide text-navy-400">Certificate number</dt>
                        <dd class="mt-0.5 flex items-center gap-2">
                            <span class="font-mono font-semibold text-navy-900">{{ $certificate->certificate_number }}</span>
                            <button type="button" data-copy="{{ $certificate->certificate_number }}"
                                class="inline-flex items-center gap-1 rounded-md border border-navy-200 bg-white px-2 py-0.5 text-xs font-medium text-navy-600 transition hover:border-navy-400 hover:text-navy-900">
                                <x-ui-icon name="clipboard" class="h-3.5 w-3.5" />
                                <span data-copy-label>Copy</span>
                            </button>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-navy-400">Recipient</dt>
                        <dd class="mt-0.5 font-semibold text-navy-900">{{ $certificate->recipient_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-navy-400">IC / passport</dt>
                        <dd class="mt-0.5 font-mono font-semibold text-navy-900">{{ $certificate->maskedIcNumber() ?? '—' }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-xs uppercase tracking-wide text-navy-400">Programme</dt>
                        <dd class="mt-0.5 font-semibold text-navy-900">{{ $certificate->course_title }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-navy-400">Result</dt>
                        <dd class="mt-0.5 font-semibold text-navy-900">{{ $certificate->grade ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-navy-400">Issued</dt>
                        <dd class="mt-0.5 font-semibold text-navy-900">{{ $certificate->issued_at->format('d M Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-navy-400">Valid until</dt>
                        <dd class="mt-0.5 font-semibold text-navy-900">{{ $certificate->expires_at?->format('d M Y') ?? 'No expiry' }}</dd>
                    </div>
                </dl>

                <div class="border-t border-navy-100/70 bg-white/60 px-6 py-4">
                    <a href="{{ $certificate->publicUrl() }}"
                       class="inline-flex items-center gap-1.5 text-sm font-semibold text-navy-700 transition hover:text-navy-900">
                        <x-ui-icon name="qr-code" class="h-4 w-4" />
                        Open the shareable digital certificate
                        <x-ui-icon name="arrow-right" class="h-4 w-4" />
                    </a>
                </div>
            </div>

            <a href="{{ route('verify.index') }}" class="mt-6 inline-flex items-center gap-1.5 text-sm font-semibold text-navy-700 transition hover:text-navy-900">
                <x-ui-icon name="arrow-right" class="h-4 w-4" />
                Verify another certificate
            </a>
        @else
            <div class="flex items-start gap-3 rounded-xl border border-navy-100 bg-navy-50/50 p-6 text-sm text-navy-600">
                <x-ui-icon name="shield-check" class="mt-0.5 h-5 w-5 shrink-0 text-navy-400" />
                <p>Verification results are drawn directly from our certification register and updated in real time.</p>
            </div>
        @endif
    </section>
</x-layouts.app>
