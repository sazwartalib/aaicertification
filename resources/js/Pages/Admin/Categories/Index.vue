<script setup>
import { Link } from '@inertiajs/vue3';
import AdminLayout from '../../../Layouts/AdminLayout.vue';
import Icon from '../../../Components/Icon.vue';
import SearchToolbar from '../../../Components/SearchToolbar.vue';
import Pagination from '../../../Components/Pagination.vue';
import ConfirmDelete from '../../../Components/ConfirmDelete.vue';
import EmptyState from '../../../Components/EmptyState.vue';

defineProps({
    categories: { type: Object, required: true },
    filters: { type: Object, default: () => ({}) },
});
</script>

<template>
    <AdminLayout title="Categories" description="Programme groupings used across the catalogue.">
        <div class="mb-6 flex items-center justify-end">
            <Link href="/admin/categories/create" class="btn-primary">
                <Icon name="plus" class="h-4 w-4" />
                New category
            </Link>
        </div>

        <SearchToolbar url="/admin/categories" :filters="filters" placeholder="Search categories…" />

        <EmptyState
            v-if="!categories.data.length"
            icon="chart-bar"
            title="No categories found"
            description="Categories group courses on the home page and catalogue."
            action-label="New category"
            action-href="/admin/categories/create"
        />

        <div v-else class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            <article v-for="category in categories.data" :key="category.id" class="card-interactive flex flex-col">
                <div class="flex items-start justify-between gap-3">
                    <h2 class="text-base font-semibold text-navy-950">{{ category.name }}</h2>
                    <span class="chip shrink-0 bg-navy-50 text-navy-600">{{ category.coursesCount }} courses</span>
                </div>

                <p class="mt-1 text-xs text-navy-400">/{{ category.slug }}</p>
                <p class="mt-3 flex-1 text-sm leading-relaxed text-navy-600">
                    {{ category.description ?? 'No description yet.' }}
                </p>

                <div class="mt-5 flex items-center justify-end gap-1 border-t border-navy-100 pt-4">
                    <Link
                        :href="category.editUrl"
                        class="inline-flex items-center gap-1.5 rounded-md px-2.5 py-1.5 text-sm font-medium text-navy-700 transition hover:bg-navy-100"
                    >
                        <Icon name="pencil" class="h-4 w-4" />
                        Edit
                    </Link>
                    <ConfirmDelete
                        :url="category.deleteUrl"
                        :title="`Delete “${category.name}”?`"
                        description="Courses in this category become uncategorised."
                    />
                </div>
            </article>
        </div>

        <Pagination :links="categories.links" :meta="categories.meta ?? categories" />
    </AdminLayout>
</template>
