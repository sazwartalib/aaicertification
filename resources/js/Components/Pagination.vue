<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    links: { type: Array, default: () => [] },
    meta: { type: Object, default: null },
});
</script>

<template>
    <nav v-if="links.length > 3" class="mt-6 flex flex-wrap items-center justify-between gap-4" aria-label="Pagination">
        <p v-if="meta" class="text-sm text-navy-500">
            Showing {{ meta.from ?? 0 }}–{{ meta.to ?? 0 }} of {{ meta.total }}
        </p>

        <div class="flex flex-wrap items-center gap-1">
            <component
                :is="link.url ? Link : 'span'"
                v-for="link in links"
                :key="link.label"
                :href="link.url"
                preserve-scroll
                class="inline-flex min-w-9 items-center justify-center rounded-md px-3 py-2 text-sm font-medium transition"
                :class="[
                    link.active
                        ? 'bg-navy-900 text-white'
                        : link.url
                            ? 'text-navy-600 hover:bg-navy-100 hover:text-navy-900'
                            : 'cursor-not-allowed text-navy-300',
                ]"
                v-html="link.label"
            />
        </div>
    </nav>
</template>
