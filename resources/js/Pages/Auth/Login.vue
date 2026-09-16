<script setup>
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AuthLayout from '../../Layouts/AuthLayout.vue';
import InputField from '../../Components/InputField.vue';
import Turnstile from '../../Components/Turnstile.vue';
import Icon from '../../Components/Icon.vue';

defineProps({
    canRegister: { type: Boolean, default: true },
});

const turnstile = ref(null);

const form = useForm({
    email: '',
    password: '',
    remember: false,
    'cf-turnstile-response': '',
});

function submit() {
    form.post('/login', {
        onError: () => {
            form.reset('password');
            turnstile.value?.reset();
        },
    });
}
</script>

<template>
    <AuthLayout
        title="Sign in"
        heading="Sign in to your account"
        subheading="Manage courses, enrolments and certificates."
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

            <div>
                <div class="mb-1.5 flex items-baseline justify-between gap-3">
                    <span class="text-sm font-medium text-navy-800">Password</span>
                    <Link href="/forgot-password" class="text-sm font-medium text-navy-600 transition hover:text-navy-900">
                        Forgot password?
                    </Link>
                </div>
                <input
                    v-model="form.password"
                    type="password"
                    autocomplete="current-password"
                    required
                    placeholder="••••••••"
                    class="field-input"
                    :class="{ 'field-input-invalid': form.errors.password }"
                >
                <p v-if="form.errors.password" class="mt-1.5 text-sm font-medium text-rose-600">{{ form.errors.password }}</p>
            </div>

            <label class="flex cursor-pointer items-center gap-2.5 text-sm text-navy-700">
                <input
                    v-model="form.remember"
                    type="checkbox"
                    class="h-4 w-4 rounded border-navy-300 text-navy-600 focus:ring-navy-500"
                >
                Keep me signed in
            </label>

            <Turnstile
                ref="turnstile"
                v-model="form['cf-turnstile-response']"
                :error="form.errors['cf-turnstile-response']"
            />

            <button type="submit" class="btn-primary btn-block" :disabled="form.processing">
                <span v-if="form.processing">Signing in…</span>
                <template v-else>
                    Sign in
                    <Icon name="arrow-right" class="btn-arrow h-4 w-4" />
                </template>
            </button>
        </form>

        <p v-if="canRegister" class="mt-8 text-center text-sm text-navy-600">
            Need a staff account?
            <Link href="/register" class="font-semibold text-navy-900 underline-offset-4 transition hover:underline">Create one</Link>
        </p>
    </AuthLayout>
</template>
