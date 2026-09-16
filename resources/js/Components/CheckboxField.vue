<script setup>
import { useId } from 'vue';

defineProps({
    label: { type: String, required: true },
    modelValue: { type: Boolean, default: false },
    hint: { type: String, default: null },
});

defineEmits(['update:modelValue']);

const id = useId();
</script>

<template>
    <label :for="id" class="flex cursor-pointer items-start gap-3 rounded-lg border border-navy-200 bg-white p-3.5 transition hover:border-navy-300">
        <input
            :id="id"
            type="checkbox"
            :checked="modelValue"
            class="mt-0.5 h-4 w-4 rounded border-navy-300 text-navy-600 focus:ring-navy-500"
            @change="$emit('update:modelValue', $event.target.checked)"
        >
        <span class="leading-tight">
            <span class="block text-sm font-medium text-navy-800">{{ label }}</span>
            <span v-if="hint" class="mt-0.5 block text-xs text-navy-500">{{ hint }}</span>
        </span>
    </label>
</template>
