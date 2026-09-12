<x-layouts.app title="Contact" description="Contact {{ config('app.name') }} about training, certification, or in-house programmes.">
    <section class="relative overflow-hidden bg-navy-950 py-16">
        <div class="absolute inset-0 bg-grid"></div>
        <div class="relative mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <span class="eyebrow border-navy-700 bg-navy-900/60 text-navy-200">
                <x-ui-icon name="envelope" class="h-3.5 w-3.5 text-gold-400" />
                Get in touch
            </span>
            <h1 class="mt-5 text-4xl font-bold text-white">Contact us</h1>
            <p class="mt-4 max-w-2xl text-navy-200">
                Questions about a course, group bookings, or certificate verification? Send us a message and
                we will respond within one business day.
            </p>
        </div>
    </section>

    <div class="mx-auto grid max-w-5xl gap-12 px-4 py-14 sm:px-6 lg:grid-cols-3 lg:px-8">
        <div class="space-y-4 lg:col-span-1">
            @foreach ([
                ['icon' => 'map-pin', 'title' => 'Office', 'lines' => ['Level 12, Menara Training', 'Jalan Ampang, 50450 Kuala Lumpur', 'Malaysia']],
                ['icon' => 'envelope', 'title' => 'Email', 'link' => ['href' => 'mailto:training@aaicertification.test', 'text' => 'training@aaicertification.test']],
                ['icon' => 'phone', 'title' => 'Phone', 'link' => ['href' => 'tel:+60322000000', 'text' => '+60 3-2200 0000']],
            ] as $item)
                <div class="flex gap-3 rounded-xl border border-navy-100 bg-navy-50/40 p-4">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-navy-900 text-gold-400">
                        <x-ui-icon :name="$item['icon']" class="h-4 w-4" />
                    </span>
                    <div class="text-sm">
                        <h2 class="font-semibold uppercase tracking-widest text-navy-500">{{ $item['title'] }}</h2>
                        @isset($item['lines'])
                            <p class="mt-1 text-navy-800">{!! implode('<br>', $item['lines']) !!}</p>
                        @endisset
                        @isset($item['link'])
                            <p class="mt-1"><a href="{{ $item['link']['href'] }}" class="text-navy-800 underline transition hover:text-navy-950">{{ $item['link']['text'] }}</a></p>
                        @endisset
                    </div>
                </div>
            @endforeach
        </div>

        <div class="lg:col-span-2">
            <div class="card">
                <x-flash />

                @if ($errors->any())
                    <div class="mb-4 flex items-start gap-2 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                        <x-ui-icon name="exclamation-triangle" class="mt-0.5 h-4 w-4 shrink-0" />
                        <span>Please check the highlighted fields below.</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('contact.store') }}" class="space-y-4" data-loading-submit>
                    @csrf
                    <div class="grid gap-4 sm:grid-cols-2">
                        <x-form-field name="name" label="Full name" icon="users" :value="old('name')" required />
                        <x-form-field name="email" label="Email" type="email" icon="envelope" :value="old('email')" required />
                        <x-form-field name="phone" label="Phone" icon="phone" :value="old('phone')" required />
                        <x-form-field name="company" label="Company (optional)" icon="presentation-chart" :value="old('company')" />
                    </div>
                    <div>
                        <label for="message" class="block text-sm font-medium text-navy-800">Message <span class="text-rose-600">*</span></label>
                        <textarea id="message" name="message" rows="5" required
                            class="field-input mt-1.5 @error('message') field-input-invalid @enderror">{{ old('message') }}</textarea>
                        @error('message')
                            <p class="mt-1.5 flex items-center gap-1 text-xs text-rose-600"><x-ui-icon name="exclamation-triangle" class="h-3.5 w-3.5" /> {{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="btn-navy" data-loading-label="Sending…">
                        <x-ui-icon name="envelope" class="h-4 w-4" />
                        Send message
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
