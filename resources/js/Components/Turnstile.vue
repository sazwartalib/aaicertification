<script setup>
import { onBeforeUnmount, onMounted, ref, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';

/**
 * Cloudflare Turnstile widget.
 *
 * Renders nothing when Turnstile keys are not configured, so local development
 * and tests are never blocked by an unsolvable challenge.
 */
const props = defineProps({
    modelValue: { type: String, default: '' },
    error: { type: String, default: null },
    theme: { type: String, default: 'light' },
});

const emit = defineEmits(['update:modelValue']);

const page = usePage();
const container = ref(null);
const widgetId = ref(null);
const scriptId = 'cf-turnstile-script';

function loadScript() {
    return new Promise((resolve, reject) => {
        if (window.turnstile) {
            resolve();
            return;
        }

        const existing = document.getElementById(scriptId);

        if (existing) {
            existing.addEventListener('load', () => resolve());
            existing.addEventListener('error', reject);
            return;
        }

        const script = document.createElement('script');
        script.id = scriptId;
        script.src = 'https://challenges.cloudflare.com/turnstile/v0/api.js?render=explicit';
        script.async = true;
        script.defer = true;
        script.addEventListener('load', () => resolve());
        script.addEventListener('error', reject);
        document.head.appendChild(script);
    });
}

function render() {
    if (!window.turnstile || !container.value || widgetId.value !== null) {
        return;
    }

    widgetId.value = window.turnstile.render(container.value, {
        sitekey: page.props.turnstile.siteKey,
        theme: props.theme,
        callback: (token) => emit('update:modelValue', token),
        'expired-callback': () => emit('update:modelValue', ''),
        'error-callback': () => emit('update:modelValue', ''),
    });
}

/** Reset the challenge so a failed submission can be retried. */
function reset() {
    if (window.turnstile && widgetId.value !== null) {
        window.turnstile.reset(widgetId.value);
        emit('update:modelValue', '');
    }
}

defineExpose({ reset });

watch(() => props.error, (value) => {
    if (value) {
        reset();
    }
});

onMounted(async () => {
    if (!page.props.turnstile.enabled) {
        return;
    }

    try {
        await loadScript();
        render();
    } catch {
        /* Challenge script blocked — server-side validation still guards the request. */
    }
});

onBeforeUnmount(() => {
    if (window.turnstile && widgetId.value !== null) {
        window.turnstile.remove(widgetId.value);
    }
});
</script>

<template>
    <div v-if="page.props.turnstile.enabled">
        <div ref="container" class="min-h-[65px]"></div>
        <p v-if="error" class="mt-1.5 text-sm font-medium text-rose-600">{{ error }}</p>
    </div>
</template>
