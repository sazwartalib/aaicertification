@props([
    'name',
    'label',
    'type' => 'text',
    'value' => null,
    'required' => false,
    'icon' => null,
    'placeholder' => null,
])

@php
    $hasError = $errors->has($name);
@endphp

<div>
    <label for="{{ $name }}" class="block text-sm font-medium text-navy-800">
        {{ $label }}@if ($required)<span class="text-rose-600"> *</span>@endif
    </label>
    <div class="relative mt-1.5">
        @if ($icon)
            <x-ui-icon :name="$icon" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-navy-400" />
        @endif
        <input
            type="{{ $type }}"
            id="{{ $name }}"
            name="{{ $name }}"
            value="{{ $value }}"
            @if ($placeholder) placeholder="{{ $placeholder }}" @endif
            @if ($required) required @endif
            @if ($hasError) aria-invalid="true" aria-describedby="{{ $name }}-error" @endif
            {{ $attributes->class([
                'field-input',
                'pl-9' => (bool) $icon,
                'field-input-invalid' => $hasError,
            ]) }}
        >
    </div>
    @error($name)
        <p id="{{ $name }}-error" class="mt-1.5 flex items-center gap-1 text-xs text-rose-600">
            <x-ui-icon name="exclamation-triangle" class="h-3.5 w-3.5" />
            {{ $message }}
        </p>
    @enderror
</div>
