<template>

  <Head title="Physical Checks" />
  <BreezeAuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-lg text-gray-800 leading-tight uppercase">
        Physical Server Room Checks
      </h2>
    </template>

    <div class="py-3">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
          <!-- Add New Check Button -->
          <button @click="showModal = true"
            class="inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-md shadow hover:from-blue-700 hover:to-purple-700 transition duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
              stroke="currentColor" class="size-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg> New Record
          </button>

          <!-- Datepicker -->
          <Datepicker v-model="selectedDates" range :enable-time-picker="false" @update:model-value="handleDateChange"
            placeholder="Select date range" class="w-full max-w-xs" />
        </div>

        <!-- Modal Form -->
        <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
          <div class="bg-white w-full max-w-md p-6 rounded-xl shadow-2xl animate-fadeIn">

            <h2 class="text-xl mb-4">Add Physical Check</h2>
            <form @submit.prevent="submit">
              <div class="space-y-4">
                <div>
                  <label>In Charge</label>
                  <input v-model="form.in_charge" type="text" class="w-full p-2 border rounded" required>
                </div>
                <div>
                  <label>Aircon Status</label>
                  <select v-model="form.aircon_status" class="w-full p-2 border rounded" required>
                    <option value="Normal">Normal</option>
                    <option value="Faulty">Faulty</option>
                  </select>
                </div>
                <div>
                  <label class="flex items-center">
                    <input v-model="form.amber_alert" type="checkbox" class="mr-2">
                    Amber Alert
                  </label>
                </div>
                <div>
                  <label>Remarks</label>
                  <textarea v-model="form.remarks" class="w-full p-2 border rounded" required></textarea>
                </div>
                <div class="flex gap-2">
                  <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Save</button>
                  <button @click="showModal = false" type="button" class="px-4 py-2 bg-gray-500 text-white rounded">
                    Cancel
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>

        <!-- Edit Modal (Add after Add Modal) -->
        <div v-if="showEditModal"
          class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
          <div class="bg-white w-full max-w-md p-6 rounded-xl shadow-2xl animate-fadeIn">
            <h2 class="text-xl mb-4">Edit Physical Check</h2>
            <form @submit.prevent="submitUpdate">
              <div class="space-y-4">
                <!-- Same fields as Add Modal -->
                <div>
                  <label>In Charge</label>
                  <input v-model="editForm.in_charge" type="text" class="w-full p-2 border rounded" required>
                </div>
                <div>
                  <label>Aircon Status</label>
                  <select v-model="editForm.aircon_status" class="w-full p-2 border rounded" required>
                    <option value="Normal">Normal</option>
                    <option value="Faulty">Faulty</option>
                  </select>
                </div>
                <div>
                  <label class="flex items-center">
                    <input v-model="editForm.amber_alert" type="checkbox" class="mr-2">
                    Amber Alert
                  </label>
                </div>
                <div>
                  <label>Remarks</label>
                  <textarea v-model="editForm.remarks" class="w-full p-2 border rounded"></textarea>
                </div>
                <div class="flex gap-2">
                  <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Update</button>
                  <button @click="showEditModal = false" type="button" class="px-4 py-2 bg-gray-500 text-white rounded">
                    Cancel
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div v-if="showDeleteModal"
          class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
          <div
            class="w-full max-w-md bg-gradient-to-br from-white via-gray-50 to-white p-6 rounded-2xl shadow-2xl animate-fadeIn">
            <div class="flex items-center gap-3 mb-4">
              <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 1010 10A10 10 0 0012 2z" />
              </svg>
              <h3 class="text-lg font-bold text-gray-800">Confirm Deletion</h3>
            </div>

            <p class="text-gray-600 mb-6 leading-relaxed">
              Are you sure you want to delete the <span class="font-semibold text-red-600">remarks</span> on this
              record?
              <br>This action <span class="font-medium text-gray-800">cannot be undone.</span>
            </p>

            <div class="flex justify-end gap-3">
              <button @click="showDeleteModal = false"
                class="px-4 py-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-md transition">
                Cancel
              </button>
              <button @click="confirmDelete"
                class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition font-medium">
                Delete
              </button>
            </div>
          </div>
        </div>

        <!-- Checks Table -->
        <div :class="{ 'pointer-events-none opacity-30': isAnyModalOpen }"
          class="overflow-x-auto shadow-lg rounded-lg transition duration-200">
          <table class="min-w-full bg-white rounded-lg">
            <thead class="bg-gradient-to-r from-blue-700 via-indigo-600 to-purple-700 text-white">
              <tr>
                <th class="px-6 py-3 text-left">In Charge</th>
                <th class="px-6 py-3 text-left">Date Recorded</th>
                <th class="px-6 py-3 text-left">Aircon Status</th>
                <th class="px-6 py-3 text-left">Amber Alert</th>
                <th class="px-6 py-3 text-left">Remarks</th>
                <th class="px-6 py-3 text-left">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="check in checks" :key="check.id" class="border-t hover:bg-blue-50 transition">
                <td class="px-6 py-4">{{ check.in_charge }}</td>
                <td class="px-6 py-4">{{ formatDate(check.created_at) }}</td>
                <td class="px-6 py-4">{{ check.aircon_status }}</td>
                <td class="px-6 py-4">{{ check.amber_alert ? 'Yes' : 'No' }}</td>
                <td class="px-6 py-4">{{ check.remarks }}</td>
                <td class="px-6 py-4">
                  <div class="flex gap-2">
                    <!-- Edit Icon with Tooltip -->
                    <div class="relative group">
                      <button @click="openEditModal(check)" class="text-blue-500 hover:text-blue-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                          <path
                            d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                        </svg>
                      </button>
                      <span
                        class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-1 px-2 py-1 text-xs text-white bg-black rounded opacity-0 group-hover:opacity-100 transition">
                        Edit
                      </span>
                    </div>

                    <!-- Delete Icon with Tooltip -->
                    <div class="relative group">
                      <button @click="initiateDelete(check.id)"
                        class="text-red-500 hover:text-red-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                      </button>
                      <span
                        class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-1 px-2 py-1 text-xs text-white bg-black rounded opacity-0 group-hover:opacity-100 transition">
                        Delete Remarks
                      </span>
                    </div>

                  </div>
                </td>
              </tr>

              <!-- No Records Row -->
              <tr v-if="checks.length === 0">
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
                      No Check Records Found</div>
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
import { Head, useForm, router } from '@inertiajs/vue3';
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import NavigationTabs from '@/Shared/NavigationTabs.vue';
import { ref, onMounted, computed } from 'vue';
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';

const props = defineProps({ checks: Array });
const isAnyModalOpen = computed(() => showModal.value || showEditModal.value || showDeleteModal.value);

// Date Handling
const handleDateChange = (dates) => {
  if (!dates || dates.length !== 2) return;

  const formatDate = (date) => date.toLocaleDateString('en-CA');
  const [start, end] = dates.map(formatDate);

  router.get('/physical-checks', {
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

// Modal Form for Add checks
const showModal = ref(false);

//for delete button
const showDeleteModal = ref(false);
const deleteCheckId = ref(null);
// Remove the duplicate defineProps call
// defineProps(["physicalChecks"]);

const form = useForm({
  in_charge: '',
  aircon_status: 'Normal',
  amber_alert: false,
  remarks: ''
});

const submit = () => {
  // Fallbacks if any of the form values are unexpectedly null
  form.in_charge = form.in_charge || '';
  form.aircon_status = form.aircon_status || 'Normal';
  form.amber_alert = form.amber_alert === null ? false : form.amber_alert;
  form.remarks = form.remarks || '';

  form.post(route('physical-checks'), {
    onSuccess: () => {
      showModal.value = false;
      form.reset();
    }
  });
};


// Edit functionality
const showEditModal = ref(false);
const editForm = useForm({
  id: null,
  in_charge: '',
  aircon_status: 'Normal',
  amber_alert: false,
  remarks: ''
});

const openEditModal = (check) => {
  editForm.id = check.id;
  editForm.in_charge = check.in_charge;
  editForm.aircon_status = check.aircon_status;
  editForm.amber_alert = check.amber_alert;
  editForm.remarks = check.remarks;
  showEditModal.value = true;
};

const submitUpdate = () => {
  editForm.in_charge = editForm.in_charge || '';
  editForm.aircon_status = editForm.aircon_status || 'Normal';
  editForm.amber_alert = editForm.amber_alert === null ? false : editForm.amber_alert;
  editForm.remarks = editForm.remarks || '';

  editForm.put(route('physical-checks.update', editForm.id), {
    onSuccess: () => {
      showEditModal.value = false;
      editForm.reset();
    }
  });
};


// Delete functionality
const initiateDelete = (id) => {
  deleteCheckId.value = id;
  showDeleteModal.value = true;
};

const confirmDelete = () => {
  if (deleteCheckId.value) {
    router.delete(route('physical-checks.destroy', deleteCheckId.value), {
      onSuccess: () => {
        showDeleteModal.value = false;
        deleteCheckId.value = null;
      }
    });
  }
};


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