@if (session('status'))
    <div
        data-dismissible
        class="flex items-start gap-3 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-800 transition duration-300 motion-safe:animate-[fade-up_0.4s_ease-out]"
        role="status"
    >
        <x-ui-icon name="check-circle" class="mt-0.5 h-5 w-5 shrink-0 text-green-600" />
        <p class="flex-1">{{ session('status') }}</p>
        <button type="button" data-dismiss class="shrink-0 text-green-700 transition hover:text-green-900" aria-label="Dismiss">
            <x-ui-icon name="close" class="h-4 w-4" />
        </button>
    </div>
@endif
