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
    <div class="min-h-screen bg-gray-100">
        <!-- Navigation -->
        <nav class="border-b border-gray-100 bg-white shadow-md">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 justify-between items-center">
                    <!-- Logo & Title -->
                    <div class="flex items-center space-x-4">
                        <Link :href="route('disks')">
                        <img src="/images/GuessLogo.png" alt="Company Logo" class="h-12 w-auto rounded-lg shadow-lg">
                        </Link>
                        <h1 class="text-sm sm:text-base md:text-lg font-semibold tracking-wide uppercase 
                                    text-white bg-gradient-to-r from-blue-500 via-purple-500 to-indigo-600 
                                    py-1 px-4 rounded-md shadow-md animate-gradient">
                            Server Status Report
                        </h1>
                    </div>

                    <!-- User Dropdown (Desktop) -->
                    <div class="hidden sm:flex sm:items-center">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <button
                                    class="inline-flex items-center rounded-md px-3 py-2 text-sm font-medium text-gray-500 
                                               hover:text-gray-700 focus:outline-none transition duration-150 ease-in-out">
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

                    <!-- Mobile Menu Button -->
                    <button @click="showingNavigationDropdown = !showingNavigationDropdown"
                        class="sm:hidden p-2 rounded-md text-gray-400 hover:bg-gray-100 hover:text-gray-500 focus:outline-none">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path
                                :class="{ hidden: showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                            <path
                                :class="{ hidden: !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div :class="mobileMenuClass" class="sm:hidden">
                <div class="space-y-1 pb-3 pt-2">
                    <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                        Dashboard
                    </ResponsiveNavLink>
                </div>
                <div class="border-t border-gray-200 pb-1 pt-4">
                    <div class="px-4">
                        <div class="text-base font-medium text-gray-800">{{ $page.props.auth.user.name }}</div>
                        <div class="text-sm font-medium text-gray-500">{{ $page.props.auth.user.email }}</div>
                    </div>
                    <div class="mt-3 space-y-1">
                        <ResponsiveNavLink :href="route('profile.edit')">Profile</ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('logout')" method="post" as="button">Log Out</ResponsiveNavLink>
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
        <main class="bg-gradient-to-b from-gray-100 to-blue-200 text-gray-900">
            <slot />
        </main>









        <!-- Footer -->
        <footer class="mt-5 py-4 text-center bg-gray-100 text-gray-700 text-sm">
            &copy; 2025 Server Status Report | Developed by <span class="font-semibold">ROBLEDO, John Ronnel Z. -
                OJT</span>
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
