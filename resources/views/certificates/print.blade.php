<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $certificate->certificate_number }} — Certificate</title>
    @vite(['resources/css/app.css'])
    <style>
        @media print {
            @page { size: A4 landscape; margin: 0; }
            .no-print { display: none !important; }
            body { margin: 0; }
        }
    </style>
</head>
<body class="bg-navy-100 py-10 print:bg-white print:py-0">
    <div class="no-print mx-auto mb-6 flex max-w-4xl items-center justify-between px-4">
        <a href="{{ route('certificates.print', $certificate) }}" class="text-sm text-navy-600 hover:text-navy-900">
            &larr; {{ $certificate->certificate_number }}
        </a>
        <button type="button" onclick="window.print()" class="btn-navy">
            Print certificate
        </button>
    </div>

    <div class="relative mx-auto flex aspect-[297/210] w-full max-w-4xl flex-col justify-between overflow-hidden border-8 border-double border-navy-800 bg-white p-12 shadow-[var(--shadow-card)] print:aspect-auto print:h-screen print:max-w-none print:shadow-none">
        <div class="pointer-events-none absolute inset-4 border border-gold-500/60"></div>

        <div class="relative text-center">
            <p class="text-xs font-semibold uppercase tracking-[0.4em] text-navy-500">{{ config('app.name') }}</p>
            <h1 class="mt-4 font-serif text-4xl font-bold text-navy-900">Certificate of Completion</h1>
            <div class="mx-auto mt-3 h-0.5 w-24 bg-gold-500"></div>
        </div>

        <div class="relative text-center">
            <p class="text-sm text-navy-500">This is to certify that</p>
            <p class="mt-3 font-serif text-3xl font-semibold text-navy-900">{{ $certificate->recipient_name }}</p>
            <p class="mt-1 text-sm tracking-widest text-navy-500">IC / Passport No: {{ $certificate->ic_number ?? '—' }}</p>

            <p class="mt-6 text-sm text-navy-500">has successfully completed</p>
            <p class="mt-2 text-xl font-semibold text-navy-800">{{ $certificate->course_title }}</p>

            @if ($certificate->grade)
                <p class="mt-2 text-sm text-navy-600">Result: <span class="font-semibold">{{ $certificate->grade }}</span></p>
            @endif
        </div>

        <div class="relative flex items-end justify-between text-sm text-navy-600">
            <div>
                <p class="text-xs uppercase tracking-wide text-navy-400">Certificate no.</p>
                <p class="font-mono font-semibold text-navy-900">{{ $certificate->certificate_number }}</p>
            </div>
            <div class="text-center">
                <p class="border-t border-navy-300 px-10 pt-1">Programme Director</p>
            </div>
            <div class="text-right">
                <p class="text-xs uppercase tracking-wide text-navy-400">Issued</p>
                <p class="font-semibold text-navy-900">{{ $certificate->issued_at->format('d M Y') }}</p>
                @if ($certificate->expires_at)
                    <p class="mt-1 text-xs uppercase tracking-wide text-navy-400">Valid until</p>
                    <p class="font-semibold text-navy-900">{{ $certificate->expires_at->format('d M Y') }}</p>
                @endif
            </div>
        </div>
    </div>

    <p class="no-print mx-auto mt-4 max-w-4xl px-4 text-center text-xs text-navy-500">
        Shows the full IC number for issuing purposes only — the public verification page at
        {{ route('verify.index') }} masks it.
    </p>
</body>
</html>
