<script setup>
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AuthLayout from '../../Layouts/AuthLayout.vue';
import InputField from '../../Components/InputField.vue';
import Turnstile from '../../Components/Turnstile.vue';
import Icon from '../../Components/Icon.vue';

const turnstile = ref(null);

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    'cf-turnstile-response': '',
});

function submit() {
    form.post('/register', {
        onError: () => {
            form.reset('password', 'password_confirmation');
            turnstile.value?.reset();
        },
    });
}
</script>

<template>
    <AuthLayout
        title="Create account"
        heading="Create a staff account"
        subheading="Accounts give access to the AAI admin console."
    >
        <form class="space-y-5" @submit.prevent="submit">
            <InputField
                v-model="form.name"
                label="Full name"
                autocomplete="name"
                placeholder="Nur Amira binti Zakaria"
                required
                autofocus
                :error="form.errors.name"
            />

            <InputField
                v-model="form.email"
                label="Email address"
                type="email"
                autocomplete="username"
                placeholder="you@aaicertification.com"
                required
                :error="form.errors.email"
            />

            <InputField
                v-model="form.password"
                label="Password"
                type="password"
                autocomplete="new-password"
                placeholder="••••••••"
                required
                hint="At least 8 characters."
                :error="form.errors.password"
            />

            <InputField
                v-model="form.password_confirmation"
                label="Confirm password"
                type="password"
                autocomplete="new-password"
                placeholder="••••••••"
                required
                :error="form.errors.password_confirmation"
            />

            <Turnstile
                ref="turnstile"
                v-model="form['cf-turnstile-response']"
                :error="form.errors['cf-turnstile-response']"
            />

            <button type="submit" class="btn-primary btn-block" :disabled="form.processing">
                <span v-if="form.processing">Creating account…</span>
                <template v-else>
                    Create account
                    <Icon name="arrow-right" class="btn-arrow h-4 w-4" />
                </template>
            </button>
        </form>

        <p class="mt-8 text-center text-sm text-navy-600">
            Already have an account?
            <Link href="/login" class="font-semibold text-navy-900 underline-offset-4 transition hover:underline">Sign in</Link>
        </p>
    </AuthLayout>
</template>
