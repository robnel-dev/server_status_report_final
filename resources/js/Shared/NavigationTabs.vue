<!-- resources/js/Shared/NavigationTabs.vue -->
<template>
    <div class="flex gap-4">
      <Link
        v-for="link in links"
        :key="link.name"
        :href="route(link.route)"
        class="px-4 py-2 text-sm font-medium rounded-md transition-colors"
        :class="route().current(link.route) ? 'bg-blue-500 text-white' : 'text-gray-700 hover:bg-blue-100'"
      >
        {{ link.name }}
      </Link>
    </div>
  </template>
  
  <script setup>
  import { Link } from '@inertiajs/vue3';
  
  const links = [
    { name: 'Disk Usage', route: 'disks' },
    { name: 'Antivirus', route: 'antivirus' },
    { name: 'Logs', route: 'logs' },
    { name: 'Physical Check', route: 'physical-checks' }
  ];
  </script>