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
    certificates: { type: Object, required: true },
    filters: { type: Object, default: () => ({}) },
    statuses: { type: Array, default: () => [] },
});
</script>

<template>
    <AdminLayout title="Certificates" description="Issued certificates and their verification status.">
        <div class="mb-6 flex items-center justify-end">
            <Link href="/admin/certificates/create" class="btn-primary">
                <Icon name="plus" class="h-4 w-4" />
                Issue certificate
            </Link>
        </div>

        <div class="card p-0">
            <div class="p-5 pb-0">
                <SearchToolbar
                    url="/admin/certificates"
                    :filters="filters"
                    :statuses="statuses"
                    placeholder="Search by number, recipient or IC…"
                />
            </div>

            <EmptyState
                v-if="!certificates.data.length"
                icon="shield-check"
                title="No certificates found"
                description="Issue a certificate to make it publicly verifiable."
                action-label="Issue certificate"
                action-href="/admin/certificates/create"
                class="m-5"
            />

            <div v-else class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-y border-navy-100 bg-navy-50/70 text-xs uppercase tracking-wider text-navy-500">
                        <tr>
                            <th scope="col" class="px-5 py-3 font-semibold">Certificate</th>
                            <th scope="col" class="px-5 py-3 font-semibold">Recipient</th>
                            <th scope="col" class="px-5 py-3 font-semibold">Issued</th>
                            <th scope="col" class="px-5 py-3 font-semibold">Expires</th>
                            <th scope="col" class="px-5 py-3 font-semibold">Status</th>
                            <th scope="col" class="px-5 py-3 text-right font-semibold"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-navy-100">
                        <tr v-for="certificate in certificates.data" :key="certificate.id" class="transition hover:bg-navy-50/50">
                            <td class="px-5 py-3.5">
                                <p class="font-mono text-xs font-semibold text-navy-900">{{ certificate.certificateNumber }}</p>
                                <p class="text-xs text-navy-500">{{ certificate.courseTitle }}</p>
                            </td>
                            <td class="px-5 py-3.5">
                                <p class="font-medium text-navy-900">{{ certificate.recipientName }}</p>
                                <p v-if="certificate.maskedIcNumber" class="font-mono text-xs text-navy-500">{{ certificate.maskedIcNumber }}</p>
                            </td>
                            <td class="px-5 py-3.5 text-navy-600">{{ certificate.issuedAt }}</td>
                            <td class="px-5 py-3.5">
                                <span v-if="!certificate.expiresAt" class="text-navy-400">No expiry</span>
                                <span v-else class="inline-flex items-center gap-1.5" :class="certificate.isExpiringSoon ? 'font-medium text-amber-700' : 'text-navy-600'">
                                    <Icon v-if="certificate.isExpiringSoon" name="clock" class="h-3.5 w-3.5" />
                                    {{ certificate.expiresAt }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex flex-wrap gap-1.5">
                                    <StatusBadge :label="certificate.statusLabel" :tone="certificate.statusTone" />
                                    <StatusBadge v-if="certificate.grade" :label="certificate.grade" tone="neutral" />
                                </div>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-end gap-1">
                                    <a
                                        :href="certificate.publicUrl"
                                        target="_blank"
                                        rel="noopener"
                                        class="rounded-md p-1.5 text-navy-500 transition hover:bg-navy-100 hover:text-navy-900"
                                    >
                                        <Icon name="qr-code" class="h-4 w-4" />
                                        <span class="sr-only">Digital certificate</span>
                                    </a>
                                    <a
                                        :href="certificate.printUrl"
                                        target="_blank"
                                        rel="noopener"
                                        class="rounded-md p-1.5 text-navy-500 transition hover:bg-navy-100 hover:text-navy-900"
                                    >
                                        <Icon name="printer" class="h-4 w-4" />
                                        <span class="sr-only">Print</span>
                                    </a>
                                    <Link
                                        :href="certificate.editUrl"
                                        class="inline-flex items-center gap-1.5 rounded-md px-2.5 py-1.5 text-sm font-medium text-navy-700 transition hover:bg-navy-100"
                                    >
                                        <Icon name="pencil" class="h-4 w-4" />
                                        <span class="sr-only sm:not-sr-only">Edit</span>
                                    </Link>
                                    <ConfirmDelete
                                        :url="certificate.deleteUrl"
                                        :title="`Delete ${certificate.certificateNumber}?`"
                                        description="Public verification for this certificate will stop working."
                                    />
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="px-5 pb-5">
                <Pagination :links="certificates.links" :meta="certificates.meta ?? certificates" />
            </div>
        </div>
    </AdminLayout>
</template>
