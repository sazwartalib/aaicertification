<script setup>
import { useId } from 'vue';

defineProps({
    label: { type: String, required: true },
    modelValue: { type: [String, Number, null], default: null },
    options: { type: Array, default: () => [] },
    error: { type: String, default: null },
    placeholder: { type: String, default: null },
    required: { type: Boolean, default: false },
});

defineEmits(['update:modelValue']);

const id = useId();
</script>

<template>
    <div>
        <label :for="id" class="mb-1.5 block text-sm font-medium text-navy-800">
            {{ label }}
            <span v-if="required" class="text-rose-500" aria-hidden="true">*</span>
        </label>

        <select
            :id="id"
            :value="modelValue"
            :required="required"
            :aria-invalid="error ? 'true' : undefined"
            class="field-input"
            :class="{ 'field-input-invalid': error }"
            @change="$emit('update:modelValue', $event.target.value === '' ? null : $event.target.value)"
        >
            <option v-if="placeholder" value="">{{ placeholder }}</option>
            <option v-for="option in options" :key="option.value" :value="option.value">{{ option.label }}</option>
        </select>

        <p v-if="error" class="mt-1.5 text-sm font-medium text-rose-600">{{ error }}</p>
    </div>
</template>
