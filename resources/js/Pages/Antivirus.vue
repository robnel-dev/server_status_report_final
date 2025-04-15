<template>

  <Head title="Antivirus Status" />
  <BreezeAuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-lg text-gray-800 leading-tight uppercase">
        Antivirus Update Status
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
                <th class="px-6 py-3 text-left">Date</th>
                <th class="px-6 py-3 text-left">Server IP</th>
                <th class="px-6 py-3 text-left">Last Update</th>
                <th class="px-6 py-3 text-left">Last Scan</th>
                <th class="px-6 py-3 text-left">Time Recorded</th>
                <th class="px-6 py-3 text-left">Remarks</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="antivirus in antiviruses" :key="antivirus.cntr" class="border-t hover:bg-blue-50 transition">
                <td class="px-6 py-4">{{ antivirus.dateCRT }}</td>
                <td class="px-6 py-4">{{ antivirus.svrIP }}</td>
                <td class="px-6 py-4">{{ antivirus.last_update }}</td>
                <td class="px-6 py-4">{{ antivirus.last_scan ?? 'N/A' }}</td>
                <td class="px-6 py-4">{{ antivirus.timeCRT }}</td>
                <td class="px-6 py-4">{{ antivirus.remarks ?? 'N/A' }}</td>
              </tr>

              <!-- No Records Row -->
              <tr v-if="antiviruses.length === 0">
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
                      No Antivirus Records Found</div>
                    <div class="text-sm text-gray-500">Try adjusting your date
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

const props = defineProps({ antiviruses: Array });

// Date Handling
const selectedDates = ref([new Date(), new Date()]);

// Function to update the results (Debounced for performance)
const updateResults = debounce(() => {
  const [start, end] = selectedDates.value.map(date => {
    return date.toISOString().slice(0, 10).replace(/-/g, ''); // Convert to YYYYMMDD
  });

  router.get('/antivirus', {
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

  if (startParam && endParam) {
    selectedDates.value = [
      new Date(startParam),
      new Date(endParam)
    ];
  }
});
</script>
