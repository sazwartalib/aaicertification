<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">

    <title>{{ $certificate->certificate_number }} — Certificate</title>

    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @media print {
            @page { size: A4 landscape; margin: 0; }
            .no-print { display: none !important; }
            body { margin: 0; background: #fff; }
        }
    </style>
</head>
<body class="bg-navy-100 py-10 print:bg-white print:py-0">
    {{-- Toolbar (screen only) --}}
    <div class="no-print mx-auto mb-6 flex max-w-5xl flex-wrap items-center justify-between gap-3 px-4">
        <a href="{{ route('admin.certificates.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-navy-600 transition hover:text-navy-900">
            <x-ui-icon name="arrow-left" class="h-4 w-4" />
            Back to certificates
        </a>

        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ $certificate->publicUrl() }}" target="_blank" rel="noopener"
               class="inline-flex items-center gap-2 rounded-lg border border-navy-300 bg-white px-4 py-2 text-sm font-semibold text-navy-700 transition hover:bg-navy-50">
                <x-ui-icon name="arrow-up-right" class="h-4 w-4" />
                Open digital certificate
            </a>
            <button type="button" data-print-certificate class="btn-navy">
                <x-ui-icon name="printer" class="h-4 w-4" />
                Print certificate
            </button>
        </div>
    </div>

    {{-- The certificate itself --}}
    <article class="relative mx-auto aspect-[297/210] w-full max-w-5xl overflow-hidden bg-white shadow-[var(--shadow-card-hover)] print:aspect-auto print:h-screen print:max-w-none print:shadow-none">
        {{-- Guilloche background + ornamental frame --}}
        <svg class="pointer-events-none absolute inset-0 h-full w-full" viewBox="0 0 1188 840" preserveAspectRatio="none" aria-hidden="true">
            <defs>
                <pattern id="guilloche" width="34" height="34" patternUnits="userSpaceOnUse" patternTransform="rotate(45)">
                    <circle cx="17" cy="17" r="15" fill="none" stroke="#142244" stroke-width="0.4" opacity="0.13" />
                    <circle cx="17" cy="17" r="8" fill="none" stroke="#c8891a" stroke-width="0.4" opacity="0.13" />
                </pattern>
                <linearGradient id="foil" x1="0" y1="0" x2="1" y2="1">
                    <stop offset="0%" stop-color="#f2c14e" />
                    <stop offset="45%" stop-color="#e6a92b" />
                    <stop offset="100%" stop-color="#c8891a" />
                </linearGradient>
            </defs>

            <rect width="1188" height="840" fill="url(#guilloche)" />

            {{-- Outer navy band, inner gold hairline --}}
            <rect x="18" y="18" width="1152" height="804" fill="none" stroke="#1f2f57" stroke-width="10" />
            <rect x="40" y="40" width="1108" height="760" fill="none" stroke="url(#foil)" stroke-width="1.6" />
            <rect x="48" y="48" width="1092" height="744" fill="none" stroke="#1f2f57" stroke-width="0.8" opacity="0.5" />

            {{-- Corner flourishes --}}
            @foreach ([[48, 48, 1, 1], [1140, 48, -1, 1], [48, 792, 1, -1], [1140, 792, -1, -1]] as [$x, $y, $sx, $sy])
                <g transform="translate({{ $x }} {{ $y }}) scale({{ $sx }} {{ $sy }})">
                    <path d="M0 58 L0 0 L58 0" fill="none" stroke="url(#foil)" stroke-width="3" />
                    <path d="M12 42 L12 12 L42 12" fill="none" stroke="#1f2f57" stroke-width="1.2" opacity="0.65" />
                    <circle cx="12" cy="12" r="3.2" fill="url(#foil)" />
                </g>
            @endforeach
        </svg>

        {{-- Watermark monogram --}}
        <div class="pointer-events-none absolute inset-0 flex items-center justify-center" aria-hidden="true">
            <span class="font-serif text-[20rem] font-bold leading-none text-navy-900/[0.035]">AAI</span>
        </div>

        {{-- Revoked / expired overlay --}}
        @if ($certificate->verificationState() !== 'valid')
            <div class="pointer-events-none absolute inset-0 flex items-center justify-center" aria-hidden="true">
                <span class="rotate-[-24deg] rounded-xl border-[6px] border-rose-600/30 px-10 py-4 text-6xl font-black uppercase tracking-[0.2em] text-rose-600/25">
                    {{ $certificate->verificationState() }}
                </span>
            </div>
        @endif

        <div class="relative flex h-full flex-col justify-between px-20 py-16">
            {{-- Header --}}
            <header class="text-center">
                <div class="flex items-center justify-center gap-3">
                    <span class="flex h-11 w-11 items-center justify-center rounded-lg bg-navy-900 font-serif text-lg font-bold text-gold-400">AAI</span>
                    <span class="text-left leading-tight">
                        <span class="block text-sm font-bold uppercase tracking-[0.3em] text-navy-900">{{ config('app.name') }}</span>
                        <span class="block text-[10px] uppercase tracking-[0.35em] text-navy-500">Certification &amp; Training</span>
                    </span>
                </div>

                <h1 class="mt-7 font-serif text-5xl font-bold tracking-tight text-navy-900">Certificate of Completion</h1>

                <div class="mx-auto mt-4 flex items-center justify-center gap-3">
                    <span class="h-px w-20 bg-gradient-to-r from-transparent to-gold-500"></span>
                    <x-ui-icon name="shield-check" class="h-4 w-4 text-gold-600" />
                    <span class="h-px w-20 bg-gradient-to-l from-transparent to-gold-500"></span>
                </div>
            </header>

            {{-- Body --}}
            <div class="text-center">
                <p class="text-xs uppercase tracking-[0.3em] text-navy-500">This is to certify that</p>

                <p class="mt-4 font-serif text-5xl font-semibold text-navy-950">{{ $certificate->recipient_name }}</p>
                <div class="mx-auto mt-3 h-px w-96 bg-navy-200"></div>

                @if ($certificate->ic_number)
                    <p class="mt-2 font-mono text-[11px] tracking-[0.2em] text-navy-500">
                        IC / PASSPORT: {{ $certificate->ic_number }}
                    </p>
                @endif

                <p class="mt-8 text-xs uppercase tracking-[0.3em] text-navy-500">has successfully completed</p>
                <p class="mt-3 text-2xl font-semibold text-navy-800">{{ $certificate->course_title }}</p>

                <div class="mt-4 flex items-center justify-center gap-6 text-xs text-navy-600">
                    @if ($certificate->grade)
                        <span class="inline-flex items-center gap-1.5">
                            <x-ui-icon name="badge-check" class="h-3.5 w-3.5 text-gold-600" />
                            Result: <span class="font-semibold text-navy-900">{{ $certificate->grade }}</span>
                        </span>
                    @endif
                    @if ($certificate->course?->accreditation_body)
                        <span class="inline-flex items-center gap-1.5">
                            <x-ui-icon name="academic-cap" class="h-3.5 w-3.5 text-gold-600" />
                            Accredited by <span class="font-semibold text-navy-900">{{ $certificate->course->accreditation_body }}</span>
                        </span>
                    @endif
                </div>
            </div>

            {{-- Footer: number · seal & signature · dates · QR --}}
            <footer class="flex items-end justify-between gap-8">
                <div class="min-w-0">
                    <p class="text-[10px] uppercase tracking-[0.25em] text-navy-400">Certificate No.</p>
                    <p class="mt-1 font-mono text-sm font-bold tracking-wide text-navy-900">{{ $certificate->certificate_number }}</p>

                    <p class="mt-4 text-[10px] uppercase tracking-[0.25em] text-navy-400">Issued</p>
                    <p class="mt-1 text-sm font-semibold text-navy-900">{{ $certificate->issued_at->format('d F Y') }}</p>

                    @if ($certificate->expires_at)
                        <p class="mt-4 text-[10px] uppercase tracking-[0.25em] text-navy-400">Valid Until</p>
                        <p class="mt-1 text-sm font-semibold text-navy-900">{{ $certificate->expires_at->format('d F Y') }}</p>
                    @endif
                </div>

                {{-- Embossed seal + signature --}}
                <div class="flex flex-col items-center">
                    <svg class="h-24 w-24" viewBox="0 0 100 100" aria-hidden="true">
                        <defs>
                            <radialGradient id="seal-face" cx="35%" cy="30%">
                                <stop offset="0%" stop-color="#f7d98a" />
                                <stop offset="55%" stop-color="#e6a92b" />
                                <stop offset="100%" stop-color="#b97a14" />
                            </radialGradient>
                        </defs>
                        <circle cx="50" cy="50" r="46" fill="url(#seal-face)" />
                        <circle cx="50" cy="50" r="46" fill="none" stroke="#8a5a0d" stroke-width="1" opacity="0.5" />
                        <circle cx="50" cy="50" r="38" fill="none" stroke="#7a4f0b" stroke-width="0.8" opacity="0.55" />
                        @for ($i = 0; $i < 36; $i++)
                            <rect x="49.3" y="2" width="1.4" height="6" fill="#c8891a" opacity="0.7"
                                  transform="rotate({{ $i * 10 }} 50 50)" />
                        @endfor
                        <text x="50" y="45" text-anchor="middle" font-family="Georgia, serif" font-size="20" font-weight="bold" fill="#3d2704">AAI</text>
                        <text x="50" y="60" text-anchor="middle" font-family="Georgia, serif" font-size="7" letter-spacing="1.6" fill="#3d2704">CERTIFIED</text>
                        <text x="50" y="72" text-anchor="middle" font-family="Georgia, serif" font-size="6" letter-spacing="1" fill="#5a3a06">{{ $certificate->issued_at->format('Y') }}</text>
                    </svg>

                    <div class="mt-3 w-56 border-t border-navy-400 pt-1.5 text-center">
                        <p class="text-xs font-semibold text-navy-900">Programme Director</p>
                        <p class="text-[10px] uppercase tracking-[0.2em] text-navy-500">{{ config('app.name') }}</p>
                    </div>
                </div>

                {{-- QR to the digital certificate --}}
                <div class="flex flex-col items-center">
                    <div
                        data-qr="{{ $certificate->publicUrl() }}"
                        data-qr-pending="true"
                        data-qr-color="#142244"
                        class="h-24 w-24 rounded-sm border border-navy-200 p-1"
                    ></div>
                    <p class="mt-2 text-center text-[9px] font-semibold uppercase leading-tight tracking-[0.15em] text-navy-500">
                        Scan to verify<br>this certificate
                    </p>
                </div>
            </footer>
        </div>
    </article>

    <p class="no-print mx-auto mt-4 max-w-5xl px-4 text-center text-xs text-navy-500">
        This issuing copy shows the full IC number. The digital certificate at
        <span class="font-mono">{{ $certificate->publicUrl() }}</span> masks it.
    </p>

    <script>
        /* Wait for the QR to be drawn before opening the print dialog. */
        document.querySelector('[data-print-certificate]')?.addEventListener('click', async () => {
            try {
                await window.qrCodesReady?.();
            } catch (error) {
                /* Print anyway — the certificate number remains verifiable by hand. */
            }

            window.print();
        });
    </script>
</body>
</html>
