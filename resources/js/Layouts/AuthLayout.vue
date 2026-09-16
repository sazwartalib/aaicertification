<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import Icon from '../Components/Icon.vue';
import FlashMessages from '../Components/FlashMessages.vue';

defineProps({
    title: { type: String, required: true },
    heading: { type: String, required: true },
    subheading: { type: String, default: null },
});

const page = usePage();

const highlights = [
    { icon: 'shield-check', title: 'Accredited programmes', body: 'ISO-aligned certification managed end to end.' },
    { icon: 'academic-cap', title: 'Trusted trainers', body: 'Industry practitioners delivering every cohort.' },
    { icon: 'badge-check', title: 'Verifiable certificates', body: 'Every certificate is checkable in seconds.' },
];
</script>

<template>
    <Head :title="title" />

    <div class="grid min-h-screen lg:grid-cols-2">
        <!-- Brand panel: same navy/gold language as the marketing site. -->
        <aside class="relative hidden overflow-hidden bg-navy-950 lg:flex lg:flex-col lg:justify-between lg:p-12">
            <div class="bg-grid absolute inset-0" aria-hidden="true"></div>
            <div class="absolute -right-24 top-10 h-72 w-72 rounded-full bg-navy-700/40 blur-3xl" aria-hidden="true"></div>
            <div class="absolute -bottom-20 -left-10 h-64 w-64 rounded-full bg-gold-500/20 blur-3xl" aria-hidden="true"></div>

            <Link href="/" class="relative flex items-center gap-3">
                <span class="flex h-11 w-11 items-center justify-center rounded-lg bg-gold-500 font-bold text-navy-950 shadow-sm">AAI</span>
                <span class="flex flex-col leading-tight">
                    <span class="text-base font-semibold text-white">{{ page.props.appName }}</span>
                    <span class="text-xs uppercase tracking-widest text-navy-300">Certification &amp; Training</span>
                </span>
            </Link>

            <div class="relative">
                <span class="inline-flex items-center gap-2 rounded-full border border-navy-700 bg-navy-900/60 px-3 py-1 text-xs font-semibold uppercase tracking-widest text-gold-400">
                    Staff portal
                </span>
                <h2 class="mt-6 max-w-md text-3xl font-semibold leading-tight text-white">
                    Manage courses, enrolments and certificates in one place.
                </h2>

                <ul class="mt-10 space-y-5">
                    <li v-for="item in highlights" :key="item.title" class="flex gap-4">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-navy-900 text-gold-400 ring-1 ring-inset ring-navy-700">
                            <Icon :name="item.icon" class="h-5 w-5" />
                        </span>
                        <span>
                            <span class="block text-sm font-semibold text-white">{{ item.title }}</span>
                            <span class="block text-sm text-navy-300">{{ item.body }}</span>
                        </span>
                    </li>
                </ul>
            </div>

            <p class="relative text-xs text-navy-400">
                &copy; {{ new Date().getFullYear() }} {{ page.props.appName }}. All rights reserved.
            </p>
        </aside>

        <!-- Form panel -->
        <main class="flex flex-col justify-center bg-white px-6 py-12 sm:px-12">
            <div class="mx-auto w-full max-w-md">
                <Link href="/" class="mb-10 flex items-center gap-3 lg:hidden">
                    <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-gold-500 font-bold text-navy-950">AAI</span>
                    <span class="text-base font-semibold text-navy-900">{{ page.props.appName }}</span>
                </Link>

                <h1 class="text-2xl font-semibold tracking-tight text-navy-950 sm:text-3xl">{{ heading }}</h1>
                <p v-if="subheading" class="mt-2 text-sm text-navy-600">{{ subheading }}</p>

                <div class="mt-8">
                    <FlashMessages />
                    <slot />
                </div>

                <p class="mt-10 text-center text-sm text-navy-500">
                    <Link href="/" class="inline-flex items-center gap-1.5 font-medium text-navy-700 transition hover:text-navy-900">
                        <Icon name="arrow-left" class="h-4 w-4" />
                        Back to {{ page.props.appName }}
                    </Link>
                </p>
            </div>
        </main>
    </div>
</template>
