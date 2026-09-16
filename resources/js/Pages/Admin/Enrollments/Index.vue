<script setup>
import { Link } from '@inertiajs/vue3';
import AdminLayout from '../../../Layouts/AdminLayout.vue';
import Icon from '../../../Components/Icon.vue';
import StatusBadge from '../../../Components/StatusBadge.vue';
import SearchToolbar from '../../../Components/SearchToolbar.vue';
import Pagination from '../../../Components/Pagination.vue';
import ConfirmDelete from '../../../Components/ConfirmDelete.vue';
import EmptyState from '../../../Components/EmptyState.vue';

defineProps({
    enrollments: { type: Object, required: true },
    filters: { type: Object, default: () => ({}) },
    statuses: { type: Array, default: () => [] },
});
</script>

<template>
    <AdminLayout title="Enrolments" description="Requests submitted from the public site.">
        <div class="mb-6 flex items-center justify-end">
            <Link href="/admin/enrollments/create" class="btn-primary">
                <Icon name="plus" class="h-4 w-4" />
                New enrolment
            </Link>
        </div>

        <div class="card p-0">
            <div class="p-5 pb-0">
                <SearchToolbar
                    url="/admin/enrollments"
                    :filters="filters"
                    :statuses="statuses"
                    placeholder="Search by name or email…"
                />
            </div>

            <EmptyState
                v-if="!enrollments.data.length"
                icon="inbox"
                title="No enrolments found"
                description="Requests submitted from course pages land here."
                class="m-5"
            />

            <div v-else class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-y border-navy-100 bg-navy-50/70 text-xs uppercase tracking-wider text-navy-500">
                        <tr>
                            <th scope="col" class="px-5 py-3 font-semibold">Participant</th>
                            <th scope="col" class="px-5 py-3 font-semibold">Course</th>
                            <th scope="col" class="px-5 py-3 font-semibold">Contact</th>
                            <th scope="col" class="px-5 py-3 font-semibold">Received</th>
                            <th scope="col" class="px-5 py-3 font-semibold">Status</th>
                            <th scope="col" class="px-5 py-3 text-right font-semibold"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-navy-100">
                        <tr v-for="enrollment in enrollments.data" :key="enrollment.id" class="transition hover:bg-navy-50/50">
                            <td class="px-5 py-3.5">
                                <p class="font-medium text-navy-900">{{ enrollment.name }}</p>
                                <p class="text-xs text-navy-500">{{ enrollment.company ?? 'Individual' }}</p>
                            </td>
                            <td class="px-5 py-3.5 text-navy-600">{{ enrollment.course ?? 'General enquiry' }}</td>
                            <td class="px-5 py-3.5">
                                <a :href="`mailto:${enrollment.email}`" class="block text-navy-700 transition hover:text-navy-900">{{ enrollment.email }}</a>
                                <a :href="`tel:${enrollment.phone}`" class="block text-xs text-navy-500">{{ enrollment.phone }}</a>
                            </td>
                            <td class="px-5 py-3.5 text-xs text-navy-500">{{ enrollment.createdAt }}</td>
                            <td class="px-5 py-3.5">
                                <StatusBadge :label="enrollment.statusLabel" :tone="enrollment.statusTone" />
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-end gap-1">
                                    <Link
                                        :href="enrollment.editUrl"
                                        class="inline-flex items-center gap-1.5 rounded-md px-2.5 py-1.5 text-sm font-medium text-navy-700 transition hover:bg-navy-100"
                                    >
                                        <Icon name="pencil" class="h-4 w-4" />
                                        <span class="sr-only sm:not-sr-only">Edit</span>
                                    </Link>
                                    <ConfirmDelete
                                        :url="enrollment.deleteUrl"
                                        :title="`Delete enrolment from ${enrollment.name}?`"
                                        description="The request will be permanently removed."
                                    />
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="px-5 pb-5">
                <Pagination :links="enrollments.links" :meta="enrollments.meta ?? enrollments" />
            </div>
        </div>
    </AdminLayout>
</template>
