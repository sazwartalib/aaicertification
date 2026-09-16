{{--
    Cloudflare Turnstile widget for the public Blade forms.
    Renders nothing when keys are not configured so local development is never blocked.
--}}
@if (filled(config('services.turnstile.site_key')) && filled(config('services.turnstile.secret_key')))
    <div>
        <div
            class="cf-turnstile"
            data-sitekey="{{ config('services.turnstile.site_key') }}"
            data-theme="{{ $attributes->get('theme', 'light') }}"
        ></div>

        @error('cf-turnstile-response')
            <p class="mt-1.5 text-sm font-medium text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    @once
        @push('scripts')
            <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
        @endpush
    @endonce
@endif
