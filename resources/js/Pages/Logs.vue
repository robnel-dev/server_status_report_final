<template>

  <Head title="Log Files" />
  <BreezeAuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-lg text-gray-800 leading-tight uppercase">
        Server Log Files
      </h2>
    </template>

    <div class="py-3">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Date Picker -->
        <div class="flex items-center justify-end mb-6">
          <Datepicker v-model="selectedDates" range :enable-time-picker="false" @update:model-value="handleDateChange"
            placeholder="Select date range" class="w-full max-w-xs" />
        </div>

        <!-- Record Count -->
        <div class="mb-4 flex justify-left">
          <div
            class="bg-gradient-to-r from-blue-100 via-indigo-100 to-purple-100 text-indigo-800 text-sm px-4 py-2 rounded-lg shadow-sm italic">
            Showing <span class="font-semibold">{{ logs.length }}</span> record<span v-if="logs.length !== 1">s</span>
          </div>
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

              <!-- No Records Row -->
              <tr v-if="logs.length === 0">
                <td colspan="8" class="py-10 bg-gradient-to-r from-blue-50 via-indigo-50 to-purple-50 rounded-b-lg">
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
                      No Log Records Found</div>
                    <div class="text-sm text-gray-500">Try adjusting your date
                      <span class="font-medium">filters</span>.
                    </div>
                  </div>
                </td>
              </tr>

            </tbody>
          </table>
        </div>

        <!-- For future testing -->
        <!-- <div class="flex justify-end mt-3 mb-12 px-2">
          <button @click="exportToCSV"
            class="bg-gradient-to-r from-blue-100 via-indigo-100 to-purple-100 text-indigo-800 font-medium px-6 py-3 rounded-xl shadow-sm hover:shadow-md hover:brightness-105 transition duration-300 ease-in-out border border-indigo-200">
            📁 Export Logs as CSV
          </button>
        </div> -->



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

//For Testing
// const exportToCSV = () => {
//   if (!props.logs || props.logs.length === 0) {
//     alert("No logs to export!");
//     return;
//   }

//   // Create CSV header
//   const headers = [
//     "Date",
//     "Server IP",
//     "Filename",
//     "File Size",
//     "Time Created",
//     "Remarks",
//     "Date Modified",
//     "Backup"
//   ];

//   // Create rows from data
//   const rows = props.logs.map(log => [
//     log.datecrt,
//     log.server_ip,
//     log.filename,
//     log.filesize,
//     log.timecrt,
//     log.remarks,
//     log.date_modified,
//     log.backup
//   ]);

//   // Combine into CSV format
//   const csvContent =
//     [headers, ...rows]
//       .map(e => e.map(val => `"${String(val).replace(/"/g, '""')}"`).join(","))
//       .join("\n");

//   // Download as file
//   const blob = new Blob([csvContent], { type: "text/csv;charset=utf-8;" });
//   const url = URL.createObjectURL(blob);
//   const link = document.createElement("a");
//   link.setAttribute("href", url);
//   link.setAttribute("download", "server_logs.csv");
//   link.style.visibility = "hidden";
//   document.body.appendChild(link);
//   link.click(); 
//   document.body.removeChild(link);
// };

</script>
