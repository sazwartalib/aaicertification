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
    courses: { type: Object, required: true },
    filters: { type: Object, default: () => ({}) },
});

const statuses = [
    { value: 'published', label: 'Published' },
    { value: 'draft', label: 'Draft' },
];
</script>

<template>
    <AdminLayout title="Courses" description="Every programme offered on the public site.">
        <div class="mb-6 flex items-center justify-end">
            <Link href="/admin/courses/create" class="btn-primary">
                <Icon name="plus" class="h-4 w-4" />
                New course
            </Link>
        </div>

        <div class="card p-0">
            <div class="p-5 pb-0">
                <SearchToolbar url="/admin/courses" :filters="filters" :statuses="statuses" placeholder="Search by title…" />
            </div>

            <EmptyState
                v-if="!courses.data.length"
                icon="academic-cap"
                title="No courses found"
                description="Adjust your filters, or add the first programme."
                action-label="New course"
                action-href="/admin/courses/create"
                class="m-5"
            />

            <div v-else class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-y border-navy-100 bg-navy-50/70 text-xs uppercase tracking-wider text-navy-500">
                        <tr>
                            <th scope="col" class="px-5 py-3 font-semibold">Course</th>
                            <th scope="col" class="px-5 py-3 font-semibold">Level</th>
                            <th scope="col" class="px-5 py-3 font-semibold">Delivery</th>
                            <th scope="col" class="px-5 py-3 text-right font-semibold">Price</th>
                            <th scope="col" class="px-5 py-3 text-right font-semibold">Enrolments</th>
                            <th scope="col" class="px-5 py-3 font-semibold">Status</th>
                            <th scope="col" class="px-5 py-3 text-right font-semibold"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-navy-100">
                        <tr v-for="course in courses.data" :key="course.id" class="transition hover:bg-navy-50/50">
                            <td class="px-5 py-3.5">
                                <p class="font-medium text-navy-900">{{ course.title }}</p>
                                <p class="text-xs text-navy-500">{{ course.category ?? 'Uncategorised' }}</p>
                            </td>
                            <td class="px-5 py-3.5">
                                <StatusBadge :label="course.level" :tone="course.levelTone" />
                            </td>
                            <td class="px-5 py-3.5 text-navy-600">{{ course.deliveryMode }}</td>
                            <td class="px-5 py-3.5 text-right tabular-nums text-navy-700">
                                {{ course.price ? `RM ${course.price}` : '—' }}
                            </td>
                            <td class="px-5 py-3.5 text-right tabular-nums text-navy-700">{{ course.enrollmentsCount }}</td>
                            <td class="px-5 py-3.5">
                                <div class="flex flex-wrap gap-1.5">
                                    <StatusBadge
                                        :label="course.isPublished ? 'Published' : 'Draft'"
                                        :tone="course.isPublished ? 'success' : 'neutral'"
                                    />
                                    <StatusBadge v-if="course.isFeatured" label="Featured" tone="warning" />
                                </div>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-end gap-1">
                                    <a
                                        v-if="course.isPublished"
                                        :href="course.publicUrl"
                                        target="_blank"
                                        rel="noopener"
                                        class="rounded-md p-1.5 text-navy-500 transition hover:bg-navy-100 hover:text-navy-900"
                                    >
                                        <Icon name="eye" class="h-4 w-4" />
                                        <span class="sr-only">View on site</span>
                                    </a>
                                    <Link
                                        :href="course.editUrl"
                                        class="inline-flex items-center gap-1.5 rounded-md px-2.5 py-1.5 text-sm font-medium text-navy-700 transition hover:bg-navy-100"
                                    >
                                        <Icon name="pencil" class="h-4 w-4" />
                                        <span class="sr-only sm:not-sr-only">Edit</span>
                                    </Link>
                                    <ConfirmDelete
                                        :url="course.deleteUrl"
                                        :title="`Delete “${course.title}”?`"
                                        description="The course and its public page will be removed. Enrolments stay but lose their course link."
                                    />
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="px-5 pb-5">
                <Pagination :links="courses.links" :meta="courses.meta ?? courses" />
            </div>
        </div>
    </AdminLayout>
</template>
