<template>

  <Head title="Disk Usage" />
  <BreezeAuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-lg text-gray-800 leading-tight uppercase">
        Disk Status Information
      </h2>
    </template>

    <div class="py-3">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Filters Section -->
        <div class="flex items-center justify-between mb-6">
          <!-- Search Input (Left) -->
          <input v-model="searchQuery" @input="handleSearch" type="text" placeholder="Search..."
            class="w-full sm:w-80 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition" />

          <!-- Date Picker (Right) -->
          <Datepicker v-model="selectedDates" range :enable-time-picker="false" @update:model-value="handleDateChange"
            placeholder="Select date range" class="w-full max-w-xs ml-auto" />
        </div>

        <!-- Record Count -->
        <div class="mb-4 flex justify-left">
          <div
            class="bg-gradient-to-r from-blue-100 via-indigo-100 to-purple-100 text-indigo-800 text-sm px-4 py-2 rounded-lg shadow-sm italic">
            Showing <span class="font-semibold">{{ disks.length }}</span> record<span v-if="disks.length !== 1">s</span>
          </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto shadow-lg rounded-lg">
          <table class="min-w-full bg-white rounded-lg">
            <thead class="bg-gradient-to-r from-blue-700 via-indigo-600 to-purple-700 text-white">

              <tr>
                <th class="px-6 py-3 text-left">Date</th>
                <th class="px-6 py-3 text-left">Server IP</th>
                <th class="px-6 py-3 text-left">Drive</th>
                <th class="px-6 py-3 text-left">Total Size</th>
                <th class="px-6 py-3 text-left">Free Space</th>
                <th class="px-9 py-3 text-left">Status</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="disk in disks" :key="disk.server_ip + '-' + disk.drive"
                class="border-t hover:bg-blue-50 transition">
                <td class="px-6 py-4">{{ disk.date }}</td>
                <td class="px-6 py-4 relative">
                  <span class="cursor-pointer text-black-600 hover:text-blue-700 relative"
                    @mouseenter="showTooltip(disk.server_ip, disk.drive)"
                    @mouseleave="hideTooltip(disk.server_ip, disk.drive)"
                    @click="copyToClipboard(disk.server_ip, disk.drive)">
                    {{ disk.server_ip }}
                  </span>
                  <div v-if="tooltip === `${disk.server_ip}-${disk.drive}`"
                    class="absolute bg-gray-800 text-white text-xs px-2 py-1 rounded shadow-md -top-6 left-1/2 transform -translate-x-1/2 whitespace-nowrap">
                    {{ copiedIp === `${disk.server_ip}-${disk.drive}` ? "Copied!" : "Copy IP Address" }}
                  </div>
                </td>

                <td class="px-6 py-4">{{ disk.drive }}</td>
                <td class="px-6 py-4">{{ disk.total_size }} {{ disk.uom }}</td>
                <td class="px-6 py-4">{{ disk.free_space }} {{ disk.uom }}</td>
                <td class="px-6 py-4">
                  <span :class="statusColor[disk.status]"
                    class="px-3 py-1 rounded text-sm font-medium inline-flex items-center">
                    {{ disk.status }}
                  </span>
                </td>
              </tr>

              <!-- No Records Row -->
              <tr v-if="disks.length === 0">
                <td colspan="6" class="py-10 bg-gradient-to-r from-blue-50 via-indigo-50 to-purple-50 rounded-b-lg">
                  <div class="flex flex-col items-center justify-center text-gray-600 space-y-2">
                    <div class="bg-blue-100 text-blue-600 p-3 rounded-full shadow-sm">
                      <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9.75 9.75h.008v.008H9.75V9.75zm4.5 0h.008v.008H14.25V9.75zM12 15.75a3.75 3.75 0 0 0 3.748-3.584 5.25 5.25 0 1 0-7.496 0A3.75 3.75 0 0 0 12 15.75z" />
                      </svg>
                    </div>
                    <div
                      class="flex flex-col items-center justify-center text-gray-600 space-y-2 transition-all duration-300 ease-in-out transform animate-fade-in">
                      No Disk Records Found</div>
                    <div class="text-sm text-gray-500">Try adjusting your <span class="font-medium">search</span> or
                      date
                      <span class="font-medium">filters</span>.
                    </div>
                  </div>
                </td>
              </tr>

            </tbody>
          </table>
        </div>
      </div>
    </div>
  </BreezeAuthenticatedLayout>
</template>

<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import debounce from 'lodash.debounce';
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';


const props = defineProps({ disks: Array });

// Search Handling
const searchQuery = ref('');
const selectedDates = ref([new Date(), new Date()]);

// Unified debounced handler
const updateResults = debounce(() => {
  const [start, end] = selectedDates.value.map(date => {
    return date.toISOString().slice(0, 10).replace(/-/g, ''); // Convert to YYYYMMDD
  });

  router.get('/disks', {
    search: searchQuery.value,
    start_date: start,
    end_date: end
  }, {
    preserveState: true,
    replace: true
  });
}, 300);

// Event handlers
const handleSearch = () => updateResults();
const handleDateChange = () => updateResults();

// Debugging output
//   if (!props.disks || props.disks.length === 0) {
//     console.warn("No disks received!");
//   }

// Initialization
onMounted(() => {

  const urlParams = new URLSearchParams(window.location.search);
  searchQuery.value = urlParams.get('search') || '';

  const startParam = urlParams.get('start_date');
  const endParam = urlParams.get('end_date');

  if (startParam && endParam) {
    selectedDates.value = [
      new Date(startParam),
      new Date(endParam)
    ];
  }
});

// Status color logic
const statusColor = (status) => {
  if (status.includes('🔴')) return 'bg-red-100 text-red-800';
  if (status.includes('🟡')) return 'bg-yellow-100 text-yellow-800';
  return 'bg-green-100 text-green-800';
};

const tooltip = ref(null);
const copiedIp = ref(null);

const showTooltip = (ip, drive) => {
  tooltip.value = `${ip}-${drive}`;
};

const hideTooltip = (ip, drive) => {
  if (copiedIp.value !== `${ip}-${drive}`) {
    tooltip.value = null;
  }
};

const copyToClipboard = (ip, drive) => {
  navigator.clipboard.writeText(ip).then(() => {
    copiedIp.value = `${ip}-${drive}`;
    tooltip.value = `${ip}-${drive}`; // Keep the tooltip visible
    setTimeout(() => {
      copiedIp.value = null;
      tooltip.value = null;
    }, 1000); // Reset after 1 second
  });
};



</script>