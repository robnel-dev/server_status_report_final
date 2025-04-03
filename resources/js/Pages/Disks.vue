<template>

  <Head title="Disk Usage" />
  <BreezeAuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-lg text-gray-800 leading-tight uppercase">
        Disk Status Information
      </h2>
    </template>

    <div class="py-12">
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
              <tr v-for="disk in disks" :key="disk.server_ip + '-' + disk.drive" class="border-t hover:bg-blue-50 transition">
                <td class="px-6 py-4">{{ disk.date }}</td>
                <td class="px-6 py-4 relative">
                  <span class="cursor-pointer text-black-600 hover:text-blue-700 relative"
                    @mouseenter="showTooltip(disk.server_ip)" @mouseleave="hideTooltip(disk.server_ip)"
                    @click="copyToClipboard(disk.server_ip)">
                    {{ disk.server_ip }}
                  </span>
                  <div v-if="tooltip === disk.server_ip"
                    class="absolute  bg-gray-800 text-white text-xs px-2 py-1 rounded shadow-md -top-6 left-1/2 transform -translate-x-1/2 whitespace-nowrap">
                    {{ copiedIp === disk.server_ip ? "Copied!" : "Copy IP Address" }}
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
  const [start, end] = selectedDates.value.map(date =>
    date.toISOString().split('T')[0]
  );

// alert('start: ' + start);
// alert('end: ' + end);
// alert('search: ' + searchQuery);

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

// Initialization
onMounted(() => {

  console.log("Received Disks:", props.disks); // Debugging output
  
  if (!props.disks || props.disks.length === 0) {
    console.warn("No disks received!");
  }


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

//IP Tooltip
const tooltip = ref(null);
const copiedIp = ref(null);

const showTooltip = (ip) => {
  tooltip.value = ip;
};

const hideTooltip = (ip) => {
  if (copiedIp.value !== ip) {
    tooltip.value = null;
  }
};

const copyToClipboard = (ip) => {
  navigator.clipboard.writeText(ip).then(() => {
    copiedIp.value = ip;
    tooltip.value = ip; // Keep the tooltip visible
    setTimeout(() => {
      copiedIp.value = null;
      tooltip.value = null;
    }, 1000); // Reset after 2 seconds
  });
};


</script>