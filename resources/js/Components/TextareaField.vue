<script setup>
import { computed, useId } from 'vue';

const props = defineProps({
    label: { type: String, required: true },
    modelValue: { type: String, default: '' },
    error: { type: String, default: null },
    hint: { type: String, default: null },
    rows: { type: Number, default: 4 },
    required: { type: Boolean, default: false },
    placeholder: { type: String, default: null },
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

        <textarea
            :id="id"
            :rows="rows"
            :value="modelValue"
            :required="required"
            :placeholder="placeholder"
            :aria-invalid="error ? 'true' : undefined"
            :aria-describedby="describedBy"
            class="field-input"
            :class="{ 'field-input-invalid': error }"
            @input="$emit('update:modelValue', $event.target.value)"
        ></textarea>

        <p v-if="error" :id="`${id}-error`" class="mt-1.5 text-sm font-medium text-rose-600">{{ error }}</p>
        <p v-else-if="hint" :id="`${id}-hint`" class="mt-1.5 text-xs text-navy-500">{{ hint }}</p>
    </div>
</template>
