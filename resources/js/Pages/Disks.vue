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
        <!-- Date Picker -->
        <div class="mb-6">
          <Datepicker
            v-model="selectedDates"
            range
            :enable-time-picker="false"
            @update:model-value="handleDateChange"
            placeholder="Select date range"
            class="w-full max-w-md"
          />
        </div>

        <!-- Table -->
        <table class="min-w-full bg-white shadow-sm rounded-lg">
          <thead>
            <tr class="bg-gray-50">
              <th class="px-6 py-3 text-left">Date</th>
              <th class="px-6 py-3 text-left">Server IP</th>
              <th class="px-6 py-3 text-left">Server Name</th>
              <th class="px-6 py-3 text-left">Drive</th>
              <th class="px-6 py-3 text-left">Total Size</th>
              <th class="px-6 py-3 text-left">Free Space</th>
              <th class="px-6 py-3 text-left">Status</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="disk in disks" :key="disk.server_ip">
              <td class="px-6 py-4">{{ disk.date }}</td>
              <td class="px-6 py-4">{{ disk.server_ip }}</td>
              <td class="px-6 py-4">{{ disk.server_name }}</td>
              <td class="px-6 py-4">{{ disk.drive }}</td>
              <td class="px-6 py-4">{{ disk.total_size }} {{ disk.uom }}</td>
              <td class="px-6 py-4">{{ disk.free_space }} {{ disk.uom }}</td>
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
import { Head, router } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';


const props = defineProps({ disks: Array });

// Date Handling
const selectedDates = ref([new Date(), new Date()]);

const handleDateChange = (dates) => {
  if (!dates || dates.length !== 2) return;
  
  const formatDate = (date) => date.toLocaleDateString('en-CA');
  const [start, end] = dates.map(formatDate);
  
  router.get('/disks', { 
    start_date: start,
    end_date: end 
  }, { 
    preserveState: true, 
    replace: true 
  });
};

// Initialize dates from URL params
onMounted(() => {
  const urlParams = new URLSearchParams(window.location.search);
  const startParam = urlParams.get('start_date');
  const endParam = urlParams.get('end_date');
  
  if (startParam && endParam) {
    selectedDates.value = [new Date(startParam), new Date(endParam)];
  }
});

// Status color logic
const statusColor = (status) => {
  if (status.includes('🔴')) return 'bg-red-100 text-red-800';
  if (status.includes('🟡')) return 'bg-yellow-100 text-yellow-800';
  return 'bg-green-100 text-green-800';
};
</script>