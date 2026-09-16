<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '../../../Layouts/AdminLayout.vue';
import InputField from '../../../Components/InputField.vue';
import TextareaField from '../../../Components/TextareaField.vue';
import SelectField from '../../../Components/SelectField.vue';
import CheckboxField from '../../../Components/CheckboxField.vue';
import Icon from '../../../Components/Icon.vue';

const props = defineProps({
    course: { type: Object, default: null },
    categories: { type: Array, default: () => [] },
    levels: { type: Array, default: () => [] },
    deliveryModes: { type: Array, default: () => [] },
});

const isEdit = Boolean(props.course);

const form = useForm({
    title: props.course?.title ?? '',
    slug: props.course?.slug ?? '',
    category_id: props.course?.category_id ?? null,
    summary: props.course?.summary ?? '',
    description: props.course?.description ?? '',
    level: props.course?.level ?? 'beginner',
    delivery_mode: props.course?.delivery_mode ?? 'online',
    duration: props.course?.duration ?? '',
    price: props.course?.price ?? '',
    accreditation_body: props.course?.accreditation_body ?? '',
    image_path: props.course?.image_path ?? '',
    sort_order: props.course?.sort_order ?? 0,
    is_featured: props.course?.is_featured ?? false,
    is_published: props.course?.is_published ?? true,
});

function submit() {
    if (isEdit) {
        form.put(`/admin/courses/${props.course.slug}`);
        return;
    }

    form.post('/admin/courses');
}
</script>

<template>
    <AdminLayout
        :title="isEdit ? 'Edit course' : 'New course'"
        :description="isEdit ? course.title : 'Add a programme to the catalogue.'"
    >
        <form class="grid gap-6 lg:grid-cols-3" @submit.prevent="submit">
            <!-- Main details -->
            <div class="space-y-6 lg:col-span-2">
                <section class="card space-y-5">
                    <h2 class="text-base font-semibold text-navy-950">Course details</h2>

                    <InputField v-model="form.title" label="Title" required :error="form.errors.title" placeholder="ISO 9001:2015 Lead Auditor" />
                    <InputField v-model="form.slug" label="Slug" :error="form.errors.slug" hint="Leave blank to generate from the title." placeholder="iso-9001-lead-auditor" />
                    <TextareaField v-model="form.summary" label="Summary" required :rows="2" :error="form.errors.summary" hint="One or two lines shown on course cards." />
                    <TextareaField v-model="form.description" label="Description" required :rows="10" :error="form.errors.description" hint="Full programme description. HTML is allowed." />
                </section>

                <section class="card space-y-5">
                    <h2 class="text-base font-semibold text-navy-950">Delivery</h2>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <SelectField v-model="form.level" label="Level" :options="levels" required :error="form.errors.level" />
                        <SelectField v-model="form.delivery_mode" label="Delivery mode" :options="deliveryModes" required :error="form.errors.delivery_mode" />
                        <InputField v-model="form.duration" label="Duration" required :error="form.errors.duration" placeholder="5 days" />
                        <InputField v-model="form.price" label="Price (RM)" type="number" step="0.01" :error="form.errors.price" placeholder="2500.00" />
                    </div>

                    <InputField v-model="form.accreditation_body" label="Accreditation body" :error="form.errors.accreditation_body" placeholder="Exemplar Global" />
                </section>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <section class="card space-y-4">
                    <h2 class="text-base font-semibold text-navy-950">Publishing</h2>

                    <CheckboxField v-model="form.is_published" label="Published" hint="Visible on the public catalogue." />
                    <CheckboxField v-model="form.is_featured" label="Featured" hint="Highlighted on the home page." />

                    <SelectField v-model="form.category_id" label="Category" :options="categories" placeholder="Uncategorised" :error="form.errors.category_id" />
                    <InputField v-model="form.sort_order" label="Sort order" type="number" :error="form.errors.sort_order" hint="Lower numbers appear first." />
                    <InputField v-model="form.image_path" label="Image path" :error="form.errors.image_path" placeholder="images/courses/iso-9001.jpg" />
                </section>

                <section class="card">
                    <div class="flex flex-col gap-3">
                        <button type="submit" class="btn-primary btn-block" :disabled="form.processing">
                            <span v-if="form.processing">Saving…</span>
                            <template v-else>
                                {{ isEdit ? 'Save changes' : 'Create course' }}
                                <Icon name="arrow-right" class="btn-arrow h-4 w-4" />
                            </template>
                        </button>
                        <Link href="/admin/courses" class="rounded-lg border border-navy-200 px-4 py-2.5 text-center text-sm font-semibold text-navy-700 transition hover:bg-navy-50">
                            Cancel
                        </Link>
                    </div>
                </section>
            </div>
        </form>
    </AdminLayout>
</template>
