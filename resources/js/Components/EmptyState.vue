<script setup>
import { Link } from '@inertiajs/vue3';
import Icon from './Icon.vue';

defineProps({
    icon: { type: String, default: 'inbox' },
    title: { type: String, required: true },
    description: { type: String, default: null },
    actionLabel: { type: String, default: null },
    actionHref: { type: String, default: null },
});
</script>

<template>
    <div class="flex flex-col items-center justify-center rounded-xl border border-dashed border-navy-200 bg-navy-50/60 px-6 py-16 text-center">
        <span class="flex h-12 w-12 items-center justify-center rounded-full bg-white text-navy-400 shadow-sm">
            <Icon :name="icon" class="h-6 w-6" />
        </span>
        <h3 class="mt-4 text-base font-semibold text-navy-900">{{ title }}</h3>
        <p v-if="description" class="mt-1 max-w-sm text-sm text-navy-600">{{ description }}</p>
        <Link v-if="actionHref && actionLabel" :href="actionHref" class="btn-primary mt-6">
            <Icon name="plus" class="h-4 w-4" />
            {{ actionLabel }}
        </Link>
    </div>
</template>
