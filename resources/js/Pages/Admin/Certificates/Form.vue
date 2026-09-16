<script setup>
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '../../../Layouts/AdminLayout.vue';
import InputField from '../../../Components/InputField.vue';
import SelectField from '../../../Components/SelectField.vue';
import Icon from '../../../Components/Icon.vue';

const props = defineProps({
    certificate: { type: Object, default: null },
    courses: { type: Array, default: () => [] },
    statuses: { type: Array, default: () => [] },
});

const isEdit = Boolean(props.certificate);

const form = useForm({
    certificate_number: props.certificate?.certificate_number ?? '',
    recipient_name: props.certificate?.recipient_name ?? '',
    ic_number: props.certificate?.ic_number ?? '',
    course_id: props.certificate?.course_id ?? null,
    course_title: props.certificate?.course_title ?? '',
    issued_at: props.certificate?.issued_at ?? new Date().toISOString().slice(0, 10),
    expires_at: props.certificate?.expires_at ?? '',
    status: props.certificate?.status ?? 'valid',
    grade: props.certificate?.grade ?? '',
});

/** Keep the snapshot title in step with the selected course. */
function syncCourseTitle(courseId) {
    const course = props.courses.find((option) => String(option.value) === String(courseId));

    if (course) {
        form.course_title = course.label;
    }
}

const copied = ref(false);

async function copyPublicUrl() {
    try {
        await navigator.clipboard.writeText(props.certificate.publicUrl);
        copied.value = true;
        setTimeout(() => (copied.value = false), 1600);
    } catch {
        /* Clipboard unavailable — the link is shown in full above. */
    }
}

function submit() {
    if (isEdit) {
        form.put(`/admin/certificates/${props.certificate.certificate_number}`);
        return;
    }

    form.post('/admin/certificates');
}
</script>

<template>
    <AdminLayout
        :title="isEdit ? 'Edit certificate' : 'Issue certificate'"
        :description="isEdit ? certificate.certificate_number : 'Create a publicly verifiable certificate record.'"
    >
        <form class="mx-auto max-w-3xl space-y-6" @submit.prevent="submit">
            <section class="card space-y-5">
                <h2 class="text-base font-semibold text-navy-950">Recipient</h2>

                <div class="grid gap-5 sm:grid-cols-2">
                    <InputField v-model="form.recipient_name" label="Recipient name" required autofocus :error="form.errors.recipient_name" />
                    <InputField v-model="form.ic_number" label="IC / passport number" :error="form.errors.ic_number" hint="Masked on the public verify page." />
                </div>
            </section>

            <section class="card space-y-5">
                <h2 class="text-base font-semibold text-navy-950">Certificate</h2>

                <div class="grid gap-5 sm:grid-cols-2">
                    <InputField v-model="form.certificate_number" label="Certificate number" required :error="form.errors.certificate_number" placeholder="AAI-2026-00123" />
                    <SelectField v-model="form.status" label="Status" :options="statuses" required :error="form.errors.status" />
                </div>

                <SelectField
                    v-model="form.course_id"
                    label="Course"
                    :options="courses"
                    placeholder="Not linked to a course"
                    :error="form.errors.course_id"
                    @update:model-value="syncCourseTitle"
                />

                <InputField
                    v-model="form.course_title"
                    label="Course title on certificate"
                    required
                    :error="form.errors.course_title"
                    hint="Stored as a snapshot so renaming a course does not change issued certificates."
                />

                <div class="grid gap-5 sm:grid-cols-3">
                    <InputField v-model="form.issued_at" label="Issued on" type="date" required :error="form.errors.issued_at" />
                    <InputField v-model="form.expires_at" label="Expires on" type="date" :error="form.errors.expires_at" hint="Leave blank for no expiry." />
                    <InputField v-model="form.grade" label="Grade" :error="form.errors.grade" placeholder="Distinction" />
                </div>
            </section>

            <section v-if="isEdit" class="card">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div class="min-w-0">
                        <h2 class="text-base font-semibold text-navy-950">Digital certificate</h2>
                        <p class="mt-1 text-sm text-navy-600">
                            The printed QR code points here. The link is unguessable and the page is not indexed.
                        </p>
                        <p class="mt-3 truncate font-mono text-xs text-navy-500">{{ certificate.publicUrl }}</p>
                    </div>

                    <div class="flex shrink-0 gap-2">
                        <button
                            type="button"
                            class="inline-flex items-center gap-2 rounded-lg border border-navy-200 px-4 py-2 text-sm font-semibold text-navy-700 transition hover:bg-navy-50"
                            @click="copyPublicUrl"
                        >
                            <Icon name="clipboard" class="h-4 w-4" />
                            {{ copied ? 'Copied!' : 'Copy link' }}
                        </button>
                        <a
                            :href="certificate.publicUrl"
                            target="_blank"
                            rel="noopener"
                            class="inline-flex items-center gap-2 rounded-lg border border-navy-200 px-4 py-2 text-sm font-semibold text-navy-700 transition hover:bg-navy-50"
                        >
                            <Icon name="arrow-up-right" class="h-4 w-4" />
                            Open
                        </a>
                    </div>
                </div>
            </section>

            <div class="flex flex-wrap justify-end gap-3">
                <a
                    v-if="isEdit"
                    :href="certificate.printUrl"
                    target="_blank"
                    rel="noopener"
                    class="mr-auto inline-flex items-center gap-2 rounded-lg border border-navy-200 px-5 py-2.5 text-sm font-semibold text-navy-700 transition hover:bg-navy-50"
                >
                    <Icon name="printer" class="h-4 w-4" />
                    Print
                </a>
                <Link href="/admin/certificates" class="rounded-lg border border-navy-200 px-5 py-2.5 text-sm font-semibold text-navy-700 transition hover:bg-navy-50">
                    Cancel
                </Link>
                <button type="submit" class="btn-primary" :disabled="form.processing">
                    <span v-if="form.processing">Saving…</span>
                    <template v-else>
                        {{ isEdit ? 'Save changes' : 'Issue certificate' }}
                        <Icon name="arrow-right" class="btn-arrow h-4 w-4" />
                    </template>
                </button>
            </div>
        </form>
    </AdminLayout>
</template>
