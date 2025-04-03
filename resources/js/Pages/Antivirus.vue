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
          <Datepicker 
            v-model="selectedDates" 
            range 
            :enable-time-picker="false" 
            @update:model-value="handleDateChange"
            placeholder="Select date range" 
            class="w-full max-w-xs" 
          />
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
                <td class="px-6 py-4">{{ antivirus.dateCRT}}</td>
                <td class="px-6 py-4">{{ antivirus.svrIP }}</td>
                <td class="px-6 py-4">{{ antivirus.last_update }}</td>
                <td class="px-6 py-4">{{ antivirus.last_scan ?? 'N/A' }}</td>
                <td class="px-6 py-4">{{ antivirus.timeCRT }}</td>
                <td class="px-6 py-4">{{ antivirus.remarks ?? 'N/A' }}</td>
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
