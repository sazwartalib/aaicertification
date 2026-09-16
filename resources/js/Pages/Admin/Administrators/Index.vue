<script setup>
import { Link } from '@inertiajs/vue3';
import AdminLayout from '../../../Layouts/AdminLayout.vue';
import Icon from '../../../Components/Icon.vue';
import StatusBadge from '../../../Components/StatusBadge.vue';
import SearchToolbar from '../../../Components/SearchToolbar.vue';
import Pagination from '../../../Components/Pagination.vue';
import ConfirmDelete from '../../../Components/ConfirmDelete.vue';

defineProps({
    administrators: { type: Object, required: true },
    filters: { type: Object, default: () => ({}) },
});
</script>

<template>
    <AdminLayout title="Administrators" description="Accounts with access to this console.">
        <div class="mb-6 flex items-center justify-end">
            <Link href="/admin/administrators/create" class="btn-primary">
                <Icon name="plus" class="h-4 w-4" />
                New administrator
            </Link>
        </div>

        <div class="card p-0">
            <div class="p-5 pb-0">
                <SearchToolbar url="/admin/administrators" :filters="filters" placeholder="Search by name or email…" />
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-y border-navy-100 bg-navy-50/70 text-xs uppercase tracking-wider text-navy-500">
                        <tr>
                            <th scope="col" class="px-5 py-3 font-semibold">Administrator</th>
                            <th scope="col" class="px-5 py-3 font-semibold">Added</th>
                            <th scope="col" class="px-5 py-3 text-right font-semibold"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-navy-100">
                        <tr v-for="administrator in administrators.data" :key="administrator.id" class="transition hover:bg-navy-50/50">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-navy-900 text-xs font-semibold text-white">
                                        {{ administrator.name.charAt(0).toUpperCase() }}
                                    </span>
                                    <div class="min-w-0">
                                        <p class="flex items-center gap-2 font-medium text-navy-900">
                                            {{ administrator.name }}
                                            <StatusBadge v-if="administrator.isCurrentUser" label="You" tone="info" />
                                        </p>
                                        <p class="truncate text-xs text-navy-500">{{ administrator.email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-navy-600">{{ administrator.createdAt }}</td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-end">
                                    <ConfirmDelete
                                        v-if="!administrator.isCurrentUser"
                                        :url="administrator.deleteUrl"
                                        :title="`Remove ${administrator.name}?`"
                                        description="They will immediately lose access to the admin console."
                                    />
                                    <span v-else class="px-2.5 py-1.5 text-xs text-navy-400">Manage in Account</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="px-5 pb-5">
                <Pagination :links="administrators.links" :meta="administrators.meta ?? administrators" />
            </div>
        </div>

        <p class="mt-4 text-xs text-navy-500">
            There is no public sign-up. New administrators can only be added from this page.
        </p>
    </AdminLayout>
</template>
