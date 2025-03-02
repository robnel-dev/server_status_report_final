<template>

  <Head title="Antivirus Status" />
  <BreezeAuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Antivirus Update Status
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- <NavigationTabs /> -->
        <table class="min-w-full bg-white shadow-sm rounded-lg">
          <thead>
            <tr class="bg-gray-50">
              <th class="px-6 py-3 text-left">Server IP</th>
              <th class="px-6 py-3 text-left">Last Update</th>
              <th class="px-6 py-3 text-left">Date Recorded</th>
              <th class="px-6 py-3 text-left">Time Recorded</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="antivirus in antiviruses" :key="antivirus.id">
              <td class="px-6 py-4">{{ antivirus.svrip }}</td>
              <td class="px-6 py-4">{{ antivirus.last_update }}</td>
              <td class="px-6 py-4">{{ antivirus.datecrt }}</td>
              <td class="px-6 py-4">{{ antivirus.timecrt }}</td>
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

defineProps({ antiviruses: Array });
</script>