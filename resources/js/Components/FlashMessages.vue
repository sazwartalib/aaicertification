<script setup>
import { computed, ref, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import Icon from './Icon.vue';

const page = usePage();
const dismissed = ref(false);

const message = computed(() => {
    const flash = page.props.flash ?? {};

    if (flash.success) {
        return { text: flash.success, tone: 'success', icon: 'check-circle' };
    }

    if (flash.error) {
        return { text: flash.error, tone: 'danger', icon: 'x-circle' };
    }

    if (flash.status) {
        return { text: flash.status, tone: 'info', icon: 'check-circle' };
    }

    return null;
});

const tones = {
    success: 'border-emerald-200 bg-emerald-50 text-emerald-800',
    danger: 'border-rose-200 bg-rose-50 text-rose-800',
    info: 'border-sky-200 bg-sky-50 text-sky-800',
};

watch(message, () => {
    dismissed.value = false;
});
</script>

<template>
    <Transition
        enter-active-class="transition duration-200 ease-out"
        enter-from-class="-translate-y-2 opacity-0"
        leave-active-class="transition duration-200 ease-in"
        leave-to-class="-translate-y-2 opacity-0"
    >
        <div
            v-if="message && !dismissed"
            role="status"
            class="mb-6 flex items-start gap-3 rounded-lg border px-4 py-3 text-sm font-medium"
            :class="tones[message.tone]"
        >
            <Icon :name="message.icon" class="mt-0.5 h-5 w-5 shrink-0" />
            <span class="flex-1">{{ message.text }}</span>
            <button type="button" class="shrink-0 rounded p-0.5 opacity-60 transition hover:opacity-100" @click="dismissed = true">
                <Icon name="close" class="h-4 w-4" />
                <span class="sr-only">Dismiss</span>
            </button>
        </div>
    </Transition>
</template>
