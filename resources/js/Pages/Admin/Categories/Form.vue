<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '../../../Layouts/AdminLayout.vue';
import InputField from '../../../Components/InputField.vue';
import TextareaField from '../../../Components/TextareaField.vue';
import Icon from '../../../Components/Icon.vue';

const props = defineProps({
    category: { type: Object, default: null },
});

const isEdit = Boolean(props.category);

const form = useForm({
    name: props.category?.name ?? '',
    slug: props.category?.slug ?? '',
    description: props.category?.description ?? '',
});

function submit() {
    if (isEdit) {
        form.put(`/admin/categories/${props.category.id}`);
        return;
    }

    form.post('/admin/categories');
}
</script>

<template>
    <AdminLayout
        :title="isEdit ? 'Edit category' : 'New category'"
        :description="isEdit ? category.name : 'Group related programmes together.'"
    >
        <form class="mx-auto max-w-2xl" @submit.prevent="submit">
            <section class="card space-y-5">
                <InputField v-model="form.name" label="Name" required autofocus :error="form.errors.name" placeholder="Quality Management" />
                <InputField v-model="form.slug" label="Slug" :error="form.errors.slug" hint="Leave blank to generate from the name." placeholder="quality-management" />
                <TextareaField v-model="form.description" label="Description" :rows="4" :error="form.errors.description" hint="Shown alongside the category on the home page." />
            </section>

            <div class="mt-6 flex justify-end gap-3">
                <Link href="/admin/categories" class="rounded-lg border border-navy-200 px-5 py-2.5 text-sm font-semibold text-navy-700 transition hover:bg-navy-50">
                    Cancel
                </Link>
                <button type="submit" class="btn-primary" :disabled="form.processing">
                    <span v-if="form.processing">Saving…</span>
                    <template v-else>
                        {{ isEdit ? 'Save changes' : 'Create category' }}
                        <Icon name="arrow-right" class="btn-arrow h-4 w-4" />
                    </template>
                </button>
            </div>
        </form>
    </AdminLayout>
</template>
