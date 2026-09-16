<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import Icon from './Icon.vue';

const props = defineProps({
    url: { type: String, required: true },
    title: { type: String, default: 'Delete this record?' },
    description: { type: String, default: 'This action cannot be undone.' },
});

const open = ref(false);
const processing = ref(false);

function confirm() {
    processing.value = true;

    router.delete(props.url, {
        preserveScroll: true,
        onFinish: () => {
            processing.value = false;
            open.value = false;
        },
    });
}
</script>

<template>
    <button
        type="button"
        class="inline-flex items-center gap-1.5 rounded-md px-2.5 py-1.5 text-sm font-medium text-rose-600 transition hover:bg-rose-50"
        @click="open = true"
    >
        <Icon name="trash" class="h-4 w-4" />
        <span class="sr-only sm:not-sr-only">Delete</span>
    </button>

    <Teleport to="body">
        <Transition
            enter-active-class="transition duration-150 ease-out"
            enter-from-class="opacity-0"
            leave-active-class="transition duration-150 ease-in"
            leave-to-class="opacity-0"
        >
            <div v-if="open" class="fixed inset-0 z-50 flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-navy-950/60 backdrop-blur-sm" @click="open = false"></div>

                <div
                    role="alertdialog"
                    aria-modal="true"
                    class="relative w-full max-w-md rounded-xl border border-navy-100 bg-white p-6 shadow-[var(--shadow-card-hover)]"
                >
                    <div class="flex items-start gap-4">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-rose-50 text-rose-600">
                            <Icon name="exclamation-triangle" class="h-5 w-5" />
                        </span>
                        <div>
                            <h2 class="text-base font-semibold text-navy-900">{{ title }}</h2>
                            <p class="mt-1 text-sm text-navy-600">{{ description }}</p>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <button
                            type="button"
                            class="rounded-lg border border-navy-200 px-4 py-2 text-sm font-semibold text-navy-700 transition hover:bg-navy-50"
                            @click="open = false"
                        >
                            Cancel
                        </button>
                        <button
                            type="button"
                            :disabled="processing"
                            class="rounded-lg bg-rose-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-rose-500 disabled:opacity-60"
                            @click="confirm"
                        >
                            {{ processing ? 'Deleting…' : 'Delete' }}
                        </button>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>
