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
              <th class="px-6 py-3 text-left">Filename</th>
              <th class="px-6 py-3 text-left">File Size</th>
              <!-- <th class="px-6 py-3 text-left">Date Created</th>
              <th class="px-6 py-3 text-left">Time Created</th> -->
            </tr>
          </thead>
          <tbody>
            <tr v-for="log in logs" :key="log.id">
              <td class="px-6 py-4">{{ formatDate(log.datecrt) }}</td>
              <td class="px-6 py-4">{{ log.server_ip }}</td>
              <td class="px-6 py-4">{{ log.filename }}</td>
              <td class="px-6 py-4">{{ log.filesize }} bytes</td>
              <!-- <td class="px-6 py-4">{{ log.timecrt }}</td> -->
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

const props = defineProps({ logs: Array });

// Date Handling
const selectedDates = ref([new Date(), new Date()]);

const handleDateChange = (dates) => {
  if (!dates || dates.length !== 2) return;
  
  const formatDate = (date) => date.toLocaleDateString('en-CA');
  const [start, end] = dates.map(formatDate);
  
  router.get('/logs', { 
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

// ✅ Function to format the date as "YY-MM-DD"
const formatDate = (dateString) => {
  if (!dateString) return '';

  const date = new Date(dateString);
  if (isNaN(date)) return dateString; // If invalid, return original

  const year = String(date.getFullYear()); // Get last 2 digits of the year
  const month = String(date.getMonth() + 1).padStart(2, '0'); // Ensure 2 digits
  const day = String(date.getDate()).padStart(2, '0'); // Ensure 2 digits

  return `${year}-${month}-${day}`;
};
</script>