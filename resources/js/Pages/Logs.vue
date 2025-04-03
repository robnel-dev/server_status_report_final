<template>

  <Head title="Log Files" />
  <BreezeAuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-lg text-gray-800 leading-tight uppercase">
        Server Log Files
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Date Picker -->
        <div class="flex items-center justify-end mb-6">
          <Datepicker v-model="selectedDates" range :enable-time-picker="false" @update:model-value="handleDateChange"
            placeholder="Select date range" class="w-full max-w-xs" />
        </div>

        <!-- Table -->
        <div class="overflow-x-auto shadow-lg rounded-lg">
          <table class="min-w-full bg-white rounded-lg">
            <thead class="bg-gradient-to-r from-blue-700 via-indigo-600 to-purple-700 text-white">
              <tr>
                <th class="px-4 py-3 text-left whitespace-nowrap">Date</th>
                <th class="px-4 py-3 text-left whitespace-nowrap">Server IP</th>
                <th class="px-4 py-3 text-left w-60">Filename</th>
                <th class="px-4 py-3 text-left whitespace-nowrap">File Size</th>
                <th class="px-4 py-3 text-left whitespace-nowrap">Time Created</th>
                <th class="px-4 py-3 text-left w-40">Remarks</th>
                <th class="px-4 py-3 text-left whitespace-nowrap">Date Modified</th>
                <th class="px-4 py-3 text-left whitespace-nowrap">Backup</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="log in logs" :key="log.id" class="border-t hover:bg-blue-50 transition">
                <td class="px-4 py-4 whitespace-nowrap">{{ log.datecrt }}</td>
                <td class="px-4 py-4 whitespace-nowrap">{{ log.server_ip }}</td>
                <td class="px-4 py-4 w-60 truncate overflow-hidden text-ellipsis">{{ log.filename }}</td>
                <td class="px-4 py-4 whitespace-nowrap">{{ log.filesize }} bytes</td>
                <td class="px-4 py-4 whitespace-nowrap">{{ log.timecrt }}</td>
                <td class="px-4 py-4 w-40 truncate overflow-hidden text-ellipsis">{{ log.remarks }}</td>
                <td class="px-4 py-4 whitespace-nowrap">{{ log.date_modified }}</td>
                <td class="px-4 py-4 whitespace-nowrap">{{ log.backup }}</td>
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

const props = defineProps({ logs: Array });

// Date Handling
const selectedDates = ref([new Date(), new Date()]);

// Function to update the results (Debounced for performance)
const updateResults = debounce(() => {
  const [start, end] = selectedDates.value.map(date => {
    return date.toISOString().slice(0, 10).replace(/-/g, '');
  });

  alert(`Selected Dates: ${start} to ${end}`);
  router.get('/logs', {
    start_date: start,
    end_date: end
  }, {
    preserveState: true,
    replace: true
  });
}, 300);

// Event handler for date selection
const handleDateChange = () => updateResults();

// Initialize from URL parameters
onMounted(() => {
  const urlParams = new URLSearchParams(window.location.search);
  const startParam = urlParams.get('start_date');
  const endParam = urlParams.get('end_date');

  // Debugging output

  console.log("Received Logs:", props.logs); // Debugging output

  if (!props.logs || props.logs.length === 0) {
    console.warn("No logs received!");
  }

  if (startParam && endParam) {
    selectedDates.value = [
      new Date(startParam),
      new Date(endParam)
    ];
  }
});
</script>
