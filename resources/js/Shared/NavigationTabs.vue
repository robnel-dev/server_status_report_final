<template>
  <div class="flex gap-4 border-b border-gray-300 pb-2">
    <Link v-for="link in links" :key="link.name" :href="route(link.route)"
      class="px-6 py-2 text-sm font-semibold rounded-lg transition-all duration-300 ease-in-out" :class="route().current(link.route)
        ? 'bg-gradient-to-r from-blue-500 to-purple-500 text-white shadow-md'
        : 'text-gray-700 hover:bg-blue-100 hover:text-blue-600'">
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
