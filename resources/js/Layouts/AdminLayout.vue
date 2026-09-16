<script setup>
import { computed, ref } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import Icon from '../Components/Icon.vue';
import FlashMessages from '../Components/FlashMessages.vue';

defineProps({
    title: { type: String, required: true },
    heading: { type: String, default: null },
    description: { type: String, default: null },
});

const page = usePage();
const sidebarOpen = ref(false);
const accountOpen = ref(false);

const navigation = [
    { label: 'Dashboard', href: '/admin', icon: 'squares-2x2', match: /^\/admin\/?$/ },
    { label: 'Courses', href: '/admin/courses', icon: 'academic-cap', match: /^\/admin\/courses/ },
    { label: 'Categories', href: '/admin/categories', icon: 'chart-bar', match: /^\/admin\/categories/ },
    { label: 'Enrolments', href: '/admin/enrollments', icon: 'users', match: /^\/admin\/enrollments/ },
    { label: 'Certificates', href: '/admin/certificates', icon: 'shield-check', match: /^\/admin\/certificates/ },
    { label: 'Administrators', href: '/admin/administrators', icon: 'user', match: /^\/admin\/administrators/ },
];

const currentPath = computed(() => new URL(page.url, 'http://localhost').pathname);
const user = computed(() => page.props.auth.user);

function isActive(item) {
    return item.match.test(currentPath.value);
}

function logout() {
    router.post('/logout');
}
</script>

<template>
    <Head :title="title" />

    <div class="min-h-screen bg-navy-50">
        <!-- Mobile backdrop -->
        <Transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="opacity-0"
            leave-active-class="transition duration-200 ease-in"
            leave-to-class="opacity-0"
        >
            <div v-if="sidebarOpen" class="fixed inset-0 z-30 bg-navy-950/60 backdrop-blur-sm lg:hidden" @click="sidebarOpen = false"></div>
        </Transition>

        <!-- Sidebar -->
        <aside
            class="fixed inset-y-0 left-0 z-40 flex w-72 flex-col bg-navy-950 transition-transform duration-300 lg:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        >
            <div class="flex items-center justify-between gap-3 border-b border-navy-800/70 px-6 py-5">
                <Link href="/admin" class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-gold-500 font-bold text-navy-950">AAI</span>
                    <span class="flex flex-col leading-tight">
                        <span class="text-sm font-semibold text-white">{{ page.props.appName }}</span>
                        <span class="text-[11px] uppercase tracking-widest text-navy-400">Admin Console</span>
                    </span>
                </Link>
                <button type="button" class="rounded-md p-1.5 text-navy-300 transition hover:bg-navy-800 hover:text-white lg:hidden" @click="sidebarOpen = false">
                    <Icon name="close" class="h-5 w-5" />
                    <span class="sr-only">Close menu</span>
                </button>
            </div>

            <nav class="flex-1 space-y-1 overflow-y-auto px-4 py-6">
                <Link
                    v-for="item in navigation"
                    :key="item.href"
                    :href="item.href"
                    class="group relative flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition"
                    :class="isActive(item)
                        ? 'bg-navy-900 text-white'
                        : 'text-navy-300 hover:bg-navy-900/60 hover:text-white'"
                    @click="sidebarOpen = false"
                >
                    <span
                        class="absolute inset-y-1.5 left-0 w-1 rounded-r-full bg-gold-500 transition-opacity"
                        :class="isActive(item) ? 'opacity-100' : 'opacity-0'"
                        aria-hidden="true"
                    ></span>
                    <Icon :name="item.icon" class="h-5 w-5 shrink-0" :class="isActive(item) ? 'text-gold-400' : 'text-navy-400 group-hover:text-gold-400'" />
                    {{ item.label }}
                </Link>
            </nav>

            <div class="border-t border-navy-800/70 p-4">
                <a
                    href="/"
                    target="_blank"
                    rel="noopener"
                    class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-navy-300 transition hover:bg-navy-900/60 hover:text-white"
                >
                    <Icon name="arrow-up-right" class="h-5 w-5 text-navy-400" />
                    View public site
                </a>
            </div>
        </aside>

        <!-- Main column -->
        <div class="lg:pl-72">
            <header class="sticky top-0 z-20 border-b border-navy-100 bg-white/90 backdrop-blur supports-[backdrop-filter]:bg-white/75">
                <div class="flex items-center gap-4 px-4 py-3.5 sm:px-6 lg:px-8">
                    <button type="button" class="rounded-md p-2 text-navy-500 transition hover:bg-navy-50 hover:text-navy-900 lg:hidden" @click="sidebarOpen = true">
                        <Icon name="menu" class="h-5 w-5" />
                        <span class="sr-only">Open menu</span>
                    </button>

                    <div class="min-w-0 flex-1">
                        <h1 class="truncate text-base font-semibold text-navy-950">{{ heading ?? title }}</h1>
                        <p v-if="description" class="truncate text-xs text-navy-500">{{ description }}</p>
                    </div>

                    <div class="relative">
                        <button
                            type="button"
                            class="flex items-center gap-2 rounded-lg border border-navy-200 py-1.5 pl-1.5 pr-2.5 text-sm font-medium text-navy-700 transition hover:border-navy-300 hover:bg-navy-50"
                            @click="accountOpen = !accountOpen"
                        >
                            <span class="flex h-7 w-7 items-center justify-center rounded-md bg-navy-900 text-xs font-semibold text-white">
                                {{ (user?.name ?? '?').charAt(0).toUpperCase() }}
                            </span>
                            <span class="hidden sm:inline">{{ user?.name }}</span>
                        </button>

                        <Transition
                            enter-active-class="transition duration-150 ease-out"
                            enter-from-class="-translate-y-1 opacity-0"
                            leave-active-class="transition duration-100 ease-in"
                            leave-to-class="-translate-y-1 opacity-0"
                        >
                            <div
                                v-if="accountOpen"
                                class="absolute right-0 z-30 mt-2 w-56 overflow-hidden rounded-lg border border-navy-100 bg-white shadow-[var(--shadow-card-hover)]"
                            >
                                <div class="border-b border-navy-100 px-4 py-3">
                                    <p class="truncate text-sm font-semibold text-navy-900">{{ user?.name }}</p>
                                    <p class="truncate text-xs text-navy-500">{{ user?.email }}</p>
                                </div>
                                <Link
                                    href="/admin/account"
                                    class="flex w-full items-center gap-2.5 px-4 py-2.5 text-left text-sm font-medium text-navy-700 transition hover:bg-navy-50"
                                    @click="accountOpen = false"
                                >
                                    <Icon name="lock-closed" class="h-4 w-4 text-navy-400" />
                                    Account settings
                                </Link>
                                <button
                                    type="button"
                                    class="flex w-full items-center gap-2.5 border-t border-navy-100 px-4 py-2.5 text-left text-sm font-medium text-navy-700 transition hover:bg-navy-50"
                                    @click="logout"
                                >
                                    <Icon name="logout" class="h-4 w-4 text-navy-400" />
                                    Sign out
                                </button>
                            </div>
                        </Transition>
                    </div>
                </div>
            </header>

            <main class="px-4 py-8 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-7xl">
                    <FlashMessages />
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>
