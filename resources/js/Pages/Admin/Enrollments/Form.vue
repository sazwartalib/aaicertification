<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '../../../Layouts/AdminLayout.vue';
import InputField from '../../../Components/InputField.vue';
import TextareaField from '../../../Components/TextareaField.vue';
import SelectField from '../../../Components/SelectField.vue';
import Icon from '../../../Components/Icon.vue';

const props = defineProps({
    enrollment: { type: Object, default: null },
    courses: { type: Array, default: () => [] },
    statuses: { type: Array, default: () => [] },
});

const isEdit = Boolean(props.enrollment);

const form = useForm({
    name: props.enrollment?.name ?? '',
    email: props.enrollment?.email ?? '',
    phone: props.enrollment?.phone ?? '',
    company: props.enrollment?.company ?? '',
    course_id: props.enrollment?.course_id ?? null,
    message: props.enrollment?.message ?? '',
    status: props.enrollment?.status ?? 'pending',
});

function submit() {
    if (isEdit) {
        form.put(`/admin/enrollments/${props.enrollment.id}`);
        return;
    }

    form.post('/admin/enrollments');
}
</script>

<template>
    <AdminLayout
        :title="isEdit ? 'Edit enrolment' : 'New enrolment'"
        :description="isEdit ? enrollment.name : 'Record a request received off-site.'"
    >
        <form class="mx-auto max-w-3xl space-y-6" @submit.prevent="submit">
            <section class="card space-y-5">
                <h2 class="text-base font-semibold text-navy-950">Participant</h2>

                <div class="grid gap-5 sm:grid-cols-2">
                    <InputField v-model="form.name" label="Full name" required autofocus :error="form.errors.name" />
                    <InputField v-model="form.email" label="Email" type="email" required :error="form.errors.email" />
                    <InputField v-model="form.phone" label="Phone" required :error="form.errors.phone" placeholder="+60 12-345 6789" />
                    <InputField v-model="form.company" label="Company" :error="form.errors.company" placeholder="Optional" />
                </div>
            </section>

            <section class="card space-y-5">
                <h2 class="text-base font-semibold text-navy-950">Request</h2>

                <div class="grid gap-5 sm:grid-cols-2">
                    <SelectField v-model="form.course_id" label="Course" :options="courses" placeholder="General enquiry" :error="form.errors.course_id" />
                    <SelectField v-model="form.status" label="Status" :options="statuses" required :error="form.errors.status" />
                </div>

                <TextareaField v-model="form.message" label="Message" :rows="5" :error="form.errors.message" />
            </section>

            <div class="flex justify-end gap-3">
                <Link href="/admin/enrollments" class="rounded-lg border border-navy-200 px-5 py-2.5 text-sm font-semibold text-navy-700 transition hover:bg-navy-50">
                    Cancel
                </Link>
                <button type="submit" class="btn-primary" :disabled="form.processing">
                    <span v-if="form.processing">Saving…</span>
                    <template v-else>
                        {{ isEdit ? 'Save changes' : 'Create enrolment' }}
                        <Icon name="arrow-right" class="btn-arrow h-4 w-4" />
                    </template>
                </button>
            </div>
        </form>
    </AdminLayout>
</template>
