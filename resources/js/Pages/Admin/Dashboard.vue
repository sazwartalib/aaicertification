<script setup>
import { Link } from '@inertiajs/vue3';
import AdminLayout from '../../Layouts/AdminLayout.vue';
import Icon from '../../Components/Icon.vue';
import StatusBadge from '../../Components/StatusBadge.vue';
import EmptyState from '../../Components/EmptyState.vue';

defineProps({
    stats: { type: Array, default: () => [] },
    recentEnrollments: { type: Array, default: () => [] },
    expiringCertificates: { type: Array, default: () => [] },
});

const statIcons = {
    courses: 'academic-cap',
    enrollments: 'users',
    certificates: 'shield-check',
    categories: 'chart-bar',
};
</script>

<template>
    <AdminLayout title="Dashboard" description="An overview of the certification programme.">
        <!-- Stat tiles -->
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div v-for="stat in stats" :key="stat.label" class="card">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-medium text-navy-500">{{ stat.label }}</p>
                        <p class="mt-2 text-3xl font-semibold tracking-tight text-navy-950">{{ stat.value }}</p>
                        <p class="mt-1 text-xs text-navy-500">{{ stat.hint }}</p>
                    </div>
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-navy-50 text-navy-600">
                        <Icon :name="statIcons[stat.icon]" class="h-5 w-5" />
                    </span>
                </div>
            </div>
        </div>

        <div class="mt-6 grid gap-6 lg:grid-cols-5">
            <!-- Recent enrolments -->
            <section class="card lg:col-span-3">
                <header class="mb-4 flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-base font-semibold text-navy-950">Recent enrolments</h2>
                        <p class="text-sm text-navy-500">The latest requests from the public site.</p>
                    </div>
                    <Link href="/admin/enrollments" class="shrink-0 text-sm font-semibold text-navy-700 transition hover:text-navy-900">
                        View all
                    </Link>
                </header>

                <EmptyState
                    v-if="!recentEnrollments.length"
                    icon="inbox"
                    title="No enrolments yet"
                    description="Requests submitted from the course pages will appear here."
                />

                <ul v-else class="divide-y divide-navy-100">
                    <li v-for="enrollment in recentEnrollments" :key="enrollment.id" class="flex items-center gap-4 py-3">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-navy-50 text-xs font-semibold text-navy-700">
                            {{ enrollment.name.charAt(0).toUpperCase() }}
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-medium text-navy-900">{{ enrollment.name }}</p>
                            <p class="truncate text-xs text-navy-500">{{ enrollment.course ?? 'General enquiry' }} · {{ enrollment.createdAt }}</p>
                        </div>
                        <StatusBadge :label="enrollment.statusLabel" :tone="enrollment.statusTone" />
                    </li>
                </ul>
            </section>

            <!-- Expiring certificates -->
            <section class="card lg:col-span-2">
                <header class="mb-4 flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-base font-semibold text-navy-950">Expiring soon</h2>
                        <p class="text-sm text-navy-500">Valid certificates lapsing within 60 days.</p>
                    </div>
                    <Link href="/admin/certificates" class="shrink-0 text-sm font-semibold text-navy-700 transition hover:text-navy-900">
                        View all
                    </Link>
                </header>

                <EmptyState
                    v-if="!expiringCertificates.length"
                    icon="shield-check"
                    title="Nothing expiring"
                    description="No valid certificate lapses in the next 60 days."
                />

                <ul v-else class="divide-y divide-navy-100">
                    <li v-for="certificate in expiringCertificates" :key="certificate.id" class="py-3">
                        <p class="truncate text-sm font-medium text-navy-900">{{ certificate.recipient }}</p>
                        <p class="truncate text-xs text-navy-500">{{ certificate.number }} · {{ certificate.courseTitle }}</p>
                        <p class="mt-1 inline-flex items-center gap-1.5 text-xs font-medium text-amber-700">
                            <Icon name="clock" class="h-3.5 w-3.5" />
                            Expires {{ certificate.expiresAt }}
                        </p>
                    </li>
                </ul>
            </section>
        </div>
    </AdminLayout>
</template>
