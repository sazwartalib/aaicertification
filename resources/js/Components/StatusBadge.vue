<script setup>
import { computed } from 'vue';

const props = defineProps({
    label: { type: String, required: true },
    tone: { type: String, default: 'neutral' },
});

const tones = {
    success: 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-200',
    warning: 'bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-200',
    danger: 'bg-rose-50 text-rose-700 ring-1 ring-inset ring-rose-200',
    info: 'bg-sky-50 text-sky-700 ring-1 ring-inset ring-sky-200',
    neutral: 'bg-navy-50 text-navy-700 ring-1 ring-inset ring-navy-200',
};

const classes = computed(() => tones[props.tone] ?? tones.neutral);
</script>

<template>
    <span class="badge" :class="classes">{{ label }}</span>
</template>
