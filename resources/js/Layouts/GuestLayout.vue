<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="flex min-h-screen flex-col items-center bg-gray-100 pt-8 sm:justify-center sm:pt-0">
        <div>
            <Link href="/">
                <img src="/images/GuessLogo.png" alt="Company Logo" class="h-24 w-auto sm:h-28 lg:h-32 rounded-lg shadow-lg">
            </Link>
        </div>

        <div class="mt-4 w-full max-w-sm bg-white px-6 py-6 shadow-lg rounded-2xl">
            <slot />
        </div>
    </div>
</template>
