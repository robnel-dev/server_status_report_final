<script setup>
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import NavigationTabs from '@/Shared/NavigationTabs.vue';

const showingNavigationDropdown = ref(false);

// Computed class for mobile menu visibility
const mobileMenuClass = computed(() => ({
    block: showingNavigationDropdown.value,
    hidden: !showingNavigationDropdown.value
}));
</script>

<template>
    <div class="min-h-screen flex flex-col bg-gradient-to-br from-gray-200 via-blue-300 to-indigo-500 text-gray-900">
        <!-- Navigation -->
        <nav class="border-b border-gray-100 bg-white shadow-md">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 justify-between items-center">
                    <!-- Logo & Title -->
                    <div class="flex items-center space-x-4">
                        <Link :href="route('disks')">
                        <img src="/images/GuessLogo.png" alt="Company Logo" class="h-12 w-auto rounded-lg shadow-lg">
                        </Link>
                        <h1
                            class="flex items-center gap-2 text-sm sm:text-base md:text-lg font-semibold tracking-wide uppercase text-white bg-gradient-to-r from-blue-500 via-purple-500 to-indigo-600 py-1 px-4 rounded-md shadow-md animate-gradient">
                            Server Status Report
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M5.25 14.25h13.5m-13.5 0a3 3 0 0 1-3-3m3 3a3 3 0 1 0 0 6h13.5a3 3 0 1 0 0-6m-16.5-3a3 3 0 0 1 3-3h13.5a3 3 0 0 1 3 3m-19.5 0a4.5 4.5 0 0 1 .9-2.7L5.737 5.1a3.375 3.375 0 0 1 2.7-1.35h7.126c1.062 0 2.062.5 2.7 1.35l2.587 3.45a4.5 4.5 0 0 1 .9 2.7m0 0a3 3 0 0 1-3 3m0 3h.008v.008h-.008v-.008Zm0-6h.008v.008h-.008v-.008Zm-3 6h.008v.008h-.008v-.008Zm0-6h.008v.008h-.008v-.008Z" />
                            </svg>
                        </h1>

                    </div>

                    <!-- User Dropdown (Desktop) -->
                    <div class="hidden sm:flex sm:items-center">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <button
                                    class="inline-flex items-center rounded-md px-3 py-2 text-sm font-medium text-gray-500 hover:text-gray-700 focus:outline-none transition duration-150 ease-in-out">
                                    {{ $page.props.auth.user.name }}
                                    <svg class="ml-2 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </template>
                            <template #content>
                                <DropdownLink :href="route('profile.edit')">Profile</DropdownLink>
                                <DropdownLink :href="route('logout')" method="post" as="button">Log Out</DropdownLink>
                            </template>
                        </Dropdown>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Header & Navigation Tabs -->
        <header class="bg-white shadow">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <slot name="header" />
                <NavigationTabs class="mt-4" />
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-grow bg-gradient-to-b from-gray-100 to-blue-200 text-gray-900 p-6">
            <slot />
        </main>

        <!-- Footer -->
        <footer
            class="mt-auto bg-gradient-to-r from-blue-600 to-indigo-700 text-gray-200 py-6 text-center text-sm shadow-md">
            <div class="container mx-auto px-4">
                <p>&copy; 2025 Server Status Report | Developed by
                    <span class="font-semibold text-white">ROBLEDO, John Ronnel Z. - OJT</span>
                </p>
            </div>
        </footer>

    </div>
</template>

<style scoped>
@keyframes gradientMove {
    0% {
        background-position: 0% 50%;
    }

    50% {
        background-position: 100% 50%;
    }

    100% {
        background-position: 0% 50%;
    }
}

.animate-gradient {
    background-size: 200% 200%;
    animation: gradientMove 6s infinite linear;
}
</style>