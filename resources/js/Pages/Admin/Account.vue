<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import AdminLayout from '../../Layouts/AdminLayout.vue';
import InputField from '../../Components/InputField.vue';
import Icon from '../../Components/Icon.vue';

const page = usePage();

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

function submit() {
    form.put('/admin/account/password', {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: (errors) => {
            if (errors.current_password) {
                form.reset('current_password');
            }

            if (errors.password) {
                form.reset('password', 'password_confirmation');
            }
        },
    });
}
</script>

<template>
    <AdminLayout title="Account settings" description="Your sign-in details.">
        <div class="mx-auto max-w-2xl space-y-6">
            <section class="card">
                <h2 class="text-base font-semibold text-navy-950">Signed in as</h2>

                <div class="mt-4 flex items-center gap-4">
                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-navy-900 text-sm font-semibold text-white">
                        {{ (page.props.auth.user?.name ?? '?').charAt(0).toUpperCase() }}
                    </span>
                    <div class="min-w-0">
                        <p class="truncate font-medium text-navy-900">{{ page.props.auth.user?.name }}</p>
                        <p class="truncate text-sm text-navy-500">{{ page.props.auth.user?.email }}</p>
                    </div>
                </div>
            </section>

            <section class="card">
                <h2 class="text-base font-semibold text-navy-950">Change password</h2>
                <p class="mt-1 text-sm text-navy-600">
                    Use a long, unique password. Changing it signs out your other devices.
                </p>

                <form class="mt-5 space-y-5" @submit.prevent="submit">
                    <InputField
                        v-model="form.current_password"
                        label="Current password"
                        type="password"
                        required
                        autocomplete="current-password"
                        :error="form.errors.current_password"
                    />

                    <InputField
                        v-model="form.password"
                        label="New password"
                        type="password"
                        required
                        autocomplete="new-password"
                        hint="At least 8 characters, and different from your current one."
                        :error="form.errors.password"
                    />

                    <InputField
                        v-model="form.password_confirmation"
                        label="Confirm new password"
                        type="password"
                        required
                        autocomplete="new-password"
                        :error="form.errors.password_confirmation"
                    />

                    <div class="flex justify-end">
                        <button type="submit" class="btn-primary" :disabled="form.processing">
                            <span v-if="form.processing">Saving…</span>
                            <template v-else>
                                Change password
                                <Icon name="lock-closed" class="btn-arrow h-4 w-4" />
                            </template>
                        </button>
                    </div>
                </form>
            </section>
        </div>
    </AdminLayout>
</template>
