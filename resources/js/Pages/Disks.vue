<template>
    <Head title="Disk Size" />
    <BreezeAuthenticatedLayout>
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Disk Size Information
        </h2>
      </template>
  
      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <!-- <NavigationTabs /> -->
          <table class="min-w-full bg-white shadow-sm rounded-lg">
            <thead>
              <tr class="bg-gray-50">
                <th class="px-6 py-3 text-left">Server IP</th>
                <th class="px-6 py-3 text-left">Drive</th>
                <th class="px-6 py-3 text-left">Total Size (GB)</th>
                <th class="px-6 py-3 text-left">Free Space (GB)</th>
                <th class="px-6 py-3 text-left">Status</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="disk in disks" :key="disk.server_ip">
                <td class="px-6 py-4">{{ disk.server_ip }}</td>
                <td class="px-6 py-4">{{ disk.drive }}</td>
                <td class="px-6 py-4">{{ disk.total_size }}</td>
                <td class="px-6 py-4">{{ disk.free_space }}</td>
                <td class="px-6 py-4">
                  <span :class="statusColor(disk.status)" class="px-2 py-1 rounded">
                    {{ disk.status }}
                  </span>
                </td>
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
  
  defineProps({ disks: Array });
  
  const statusColor = (status) => {
    if (status.includes('🔴')) return 'bg-red-100 text-red-800';
    if (status.includes('🟡')) return 'bg-yellow-100 text-yellow-800';
    return 'bg-green-100 text-green-800';
  };
  </script>