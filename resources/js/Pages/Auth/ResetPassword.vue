<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AuthLayout from '../../Layouts/AuthLayout.vue';
import InputField from '../../Components/InputField.vue';
import Turnstile from '../../Components/Turnstile.vue';
import Icon from '../../Components/Icon.vue';

const props = defineProps({
    token: { type: String, required: true },
    email: { type: String, default: '' },
});

const turnstile = ref(null);

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
    'cf-turnstile-response': '',
});

function submit() {
    form.post('/reset-password', {
        onError: () => {
            form.reset('password', 'password_confirmation');
            turnstile.value?.reset();
        },
    });
}
</script>

<template>
    <AuthLayout
        title="Reset password"
        heading="Choose a new password"
        subheading="Pick something you haven't used before."
    >
        <form class="space-y-5" @submit.prevent="submit">
            <InputField
                v-model="form.email"
                label="Email address"
                type="email"
                autocomplete="username"
                required
                :error="form.errors.email"
            />

            <InputField
                v-model="form.password"
                label="New password"
                type="password"
                autocomplete="new-password"
                placeholder="••••••••"
                required
                autofocus
                hint="At least 8 characters."
                :error="form.errors.password"
            />

            <InputField
                v-model="form.password_confirmation"
                label="Confirm new password"
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
                <span v-if="form.processing">Saving…</span>
                <template v-else>
                    Reset password
                    <Icon name="lock-closed" class="btn-arrow h-4 w-4" />
                </template>
            </button>
        </form>
    </AuthLayout>
</template>
