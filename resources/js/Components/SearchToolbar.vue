<script setup>
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import Icon from './Icon.vue';

/**
 * Debounced search + optional status filter that pushes query-string state
 * back to the current Inertia route.
 */
const props = defineProps({
    url: { type: String, required: true },
    filters: { type: Object, default: () => ({}) },
    placeholder: { type: String, default: 'Search…' },
    statuses: { type: Array, default: () => [] },
});

const search = ref(props.filters.search ?? '');
const status = ref(props.filters.status ?? '');

let timeout = null;

function push() {
    router.get(
        props.url,
        { search: search.value || undefined, status: status.value || undefined },
        { preserveState: true, preserveScroll: true, replace: true },
    );
}

watch(search, () => {
    clearTimeout(timeout);
    timeout = setTimeout(push, 300);
});

watch(status, push);
</script>

<template>
    <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center">
        <div class="relative flex-1">
            <Icon name="search" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-navy-400" />
            <input
                v-model="search"
                type="search"
                :placeholder="placeholder"
                class="field-input pl-9"
                aria-label="Search"
            >
        </div>

        <select v-if="statuses.length" v-model="status" class="field-input sm:w-52" aria-label="Filter by status">
            <option value="">All statuses</option>
            <option v-for="option in statuses" :key="option.value" :value="option.value">{{ option.label }}</option>
        </select>
    </div>
</template>
