<script setup>
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AuthLayout from '../../Layouts/AuthLayout.vue';
import InputField from '../../Components/InputField.vue';
import Turnstile from '../../Components/Turnstile.vue';
import Icon from '../../Components/Icon.vue';

const turnstile = ref(null);

const form = useForm({
    email: '',
    'cf-turnstile-response': '',
});

function submit() {
    form.post('/forgot-password', {
        onError: () => turnstile.value?.reset(),
        onSuccess: () => {
            form.reset();
            turnstile.value?.reset();
        },
    });
}
</script>

<template>
    <AuthLayout
        title="Forgot password"
        heading="Reset your password"
        subheading="Enter your email and we'll send you a secure reset link."
    >
        <form class="space-y-5" @submit.prevent="submit">
            <InputField
                v-model="form.email"
                label="Email address"
                type="email"
                autocomplete="username"
                placeholder="you@aaicertification.com"
                required
                autofocus
                :error="form.errors.email"
            />

            <Turnstile
                ref="turnstile"
                v-model="form['cf-turnstile-response']"
                :error="form.errors['cf-turnstile-response']"
            />

            <button type="submit" class="btn-primary btn-block" :disabled="form.processing">
                <span v-if="form.processing">Sending link…</span>
                <template v-else>
                    Email password reset link
                    <Icon name="envelope" class="btn-arrow h-4 w-4" />
                </template>
            </button>
        </form>

        <p class="mt-8 text-center text-sm text-navy-600">
            Remembered it?
            <Link href="/login" class="font-semibold text-navy-900 underline-offset-4 transition hover:underline">Back to sign in</Link>
        </p>
    </AuthLayout>
</template>
