<template>
    <Head title="Log Files" />
    <BreezeAuthenticatedLayout>
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Server Log Files
        </h2>
      </template>
  
      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <!-- <NavigationTabs /> -->
          <table class="min-w-full bg-white shadow-sm rounded-lg">
            <thead>
              <tr class="bg-gray-50">
                <th class="px-6 py-3 text-left">Server IP</th>
                <th class="px-6 py-3 text-left">Filename</th>
                <th class="px-6 py-3 text-left">File Size</th>
                <th class="px-6 py-3 text-left">Date Created</th>
                <th class="px-6 py-3 text-left">Time Created</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="log in logs" :key="log.id">
                <td class="px-6 py-4">{{ log.server_ip }}</td>
                <td class="px-6 py-4">{{ log.filename }}</td>
                <td class="px-6 py-4">{{ log.filesize }} bytes</td>
                <td class="px-6 py-4">{{ log.datecrt }}</td>
                <td class="px-6 py-4">{{ log.timecrt }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </BreezeAuthenticatedLayout>
  </template>
  
  <script setup>
  import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
  import { Head } from '@inertiajs/vue3';
  import NavigationTabs from '@/Shared/NavigationTabs.vue';
  
  defineProps({ logs: Array });
  </script>