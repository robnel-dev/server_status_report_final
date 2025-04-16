<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div class="max-w-xs mx-auto mt-10 bg-white rounded-lg shadow-md p-6 border border-gray-100">

            <div v-if="status" class="mb-3 text-xs font-medium text-green-600 text-center">
                {{ status }}
            </div>

            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <InputLabel for="email" value="Email" class="text-sm" />
                    <TextInput
                        id="email"
                        type="email"
                        class="mt-1 block w-full rounded-md text-sm shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        v-model="form.email"
                        required
                        autofocus
                        autocomplete="username"
                    />
                    <InputError class="mt-1 text-xs" :message="form.errors.email" />
                </div>

                <div>
                    <InputLabel for="password" value="Password" class="text-sm" />
                    <TextInput
                        id="password"
                        type="password"
                        class="mt-1 block w-full rounded-md text-sm shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        v-model="form.password"
                        required
                        autocomplete="current-password"
                    />
                    <InputError class="mt-1 text-xs" :message="form.errors.password" />
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center">
                        <Checkbox name="remember" v-model:checked="form.remember" />
                        <span class="ms-2 text-gray-600">Remember me</span>
                    </label>

                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="text-indigo-600 hover:text-indigo-800 font-medium transition"
                    >
                        Forgot?
                    </Link>
                </div>

                <div class="pt-2">
                    <PrimaryButton
                        class="w-full justify-center rounded-md bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white shadow text-xs px-3 py-2 font-medium tracking-wide transition-all duration-300 ease-in-out"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        Log in
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </GuestLayout>
</template>
