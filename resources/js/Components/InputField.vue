<script setup>
import { computed, useId } from 'vue';

const props = defineProps({
    label: { type: String, required: true },
    modelValue: { type: [String, Number], default: '' },
    type: { type: String, default: 'text' },
    error: { type: String, default: null },
    hint: { type: String, default: null },
    placeholder: { type: String, default: null },
    required: { type: Boolean, default: false },
    autocomplete: { type: String, default: null },
    autofocus: { type: Boolean, default: false },
    step: { type: String, default: null },
});

defineEmits(['update:modelValue']);

const id = useId();
const describedBy = computed(() => (props.error ? `${id}-error` : props.hint ? `${id}-hint` : undefined));
</script>

<template>
    <div>
        <label :for="id" class="mb-1.5 block text-sm font-medium text-navy-800">
            {{ label }}
            <span v-if="required" class="text-rose-500" aria-hidden="true">*</span>
        </label>

        <input
            :id="id"
            :type="type"
            :value="modelValue"
            :placeholder="placeholder"
            :required="required"
            :autocomplete="autocomplete"
            :autofocus="autofocus"
            :step="step"
            :aria-invalid="error ? 'true' : undefined"
            :aria-describedby="describedBy"
            class="field-input"
            :class="{ 'field-input-invalid': error }"
            @input="$emit('update:modelValue', $event.target.value)"
        >

        <p v-if="error" :id="`${id}-error`" class="mt-1.5 text-sm font-medium text-rose-600">{{ error }}</p>
        <p v-else-if="hint" :id="`${id}-hint`" class="mt-1.5 text-xs text-navy-500">{{ hint }}</p>
    </div>
</template>
