<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '../../../Layouts/AdminLayout.vue';
import InputField from '../../../Components/InputField.vue';
import Icon from '../../../Components/Icon.vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

function submit() {
    form.post('/admin/administrators', {
        onError: () => form.reset('password', 'password_confirmation'),
    });
}
</script>

<template>
    <AdminLayout title="New administrator" description="Grant a colleague access to this console.">
        <form class="mx-auto max-w-2xl" @submit.prevent="submit">
            <section class="card space-y-5">
                <InputField v-model="form.name" label="Full name" required autofocus :error="form.errors.name" placeholder="Nur Amira binti Zakaria" />
                <InputField v-model="form.email" label="Email address" type="email" required :error="form.errors.email" placeholder="amira@aaicertification.com" autocomplete="off" />

                <div class="rounded-lg border border-navy-100 bg-navy-50/60 p-4">
                    <p class="text-sm font-medium text-navy-800">Initial password</p>
                    <p class="mt-1 text-xs text-navy-600">
                        Share this with them over a secure channel. They can change it from Account settings after signing in.
                    </p>

                    <div class="mt-4 space-y-5">
                        <InputField
                            v-model="form.password"
                            label="Password"
                            type="password"
                            required
                            autocomplete="new-password"
                            hint="At least 8 characters."
                            :error="form.errors.password"
                        />
                        <InputField
                            v-model="form.password_confirmation"
                            label="Confirm password"
                            type="password"
                            required
                            autocomplete="new-password"
                            :error="form.errors.password_confirmation"
                        />
                    </div>
                </div>
            </section>

            <div class="mt-6 flex justify-end gap-3">
                <Link href="/admin/administrators" class="rounded-lg border border-navy-200 px-5 py-2.5 text-sm font-semibold text-navy-700 transition hover:bg-navy-50">
                    Cancel
                </Link>
                <button type="submit" class="btn-primary" :disabled="form.processing">
                    <span v-if="form.processing">Creating…</span>
                    <template v-else>
                        Create administrator
                        <Icon name="arrow-right" class="btn-arrow h-4 w-4" />
                    </template>
                </button>
            </div>
        </form>
    </AdminLayout>
</template>
