<template>

  <Head title="Physical Checks" />
  <BreezeAuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Physical Server Room Checks
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- <NavigationTabs /> -->

        <!-- Add New Check Button -->
        <button @click="showModal = true" class="mb-4 px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition duration-200">
          + New Record
        </button>


        <!-- Modal Form -->
        <div v-if="showModal" class="fixed inset-0 bg-black/50 flex items-center justify-center">
          <div class="bg-white p-6 rounded-lg w-96">
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
                  <textarea v-model="form.remarks" class="w-full p-2 border rounded"></textarea>
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
        <div v-if="showEditModal" class="fixed inset-0 bg-black/50 flex items-center justify-center">
          <div class="bg-white p-6 rounded-lg w-96">
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
        <div v-if="showDeleteModal" class="fixed inset-0 bg-black/50 flex items-center justify-center">
          <div class="bg-white p-6 rounded-lg w-96 shadow-xl">
            <div class="mb-4">
              <h3 class="text-lg font-semibold">Confirm Delete</h3>
              <p class="text-gray-600 mt-2">Are you sure you want to delete the remarks on this record? This action
                cannot
                be undone.
              </p>
            </div>
            <div class="flex justify-end gap-3">
              <button @click="showDeleteModal = false"
                class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors">
                Cancel
              </button>
              <button @click="confirmDelete"
                class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition-colors">
                Delete
              </button>
            </div>
          </div>
        </div>

        <!-- Checks Table -->
        <table class="min-w-full bg-white shadow-sm rounded-lg">
          <thead>
            <tr class="bg-gray-50">
              <th class="px-6 py-3 text-left">In Charge</th>
              <th class="px-6 py-3 text-left">Date Recorded</th>
              <th class="px-6 py-3 text-left">Aircon Status</th>
              <th class="px-6 py-3 text-left">Amber Alert</th>
              <th class="px-6 py-3 text-left">Remarks</th>
              <th class="px-6 py-3 text-left">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="check in checks" :key="check.id">
              <td class="px-6 py-4">{{ check.in_charge }}</td>
              <td class="px-6 py-4">{{ dayjs(check.created_at).format('DD/MM/YYYY') }}</td>
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
                    <button @click="initiateDelete(check.id)" class="text-red-500 hover:text-red-700 transition-colors">
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
          </tbody>
        </table>
      </div>
    </div>
  </BreezeAuthenticatedLayout>
</template>

<script setup>
import dayjs from "dayjs";
import { ref } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import NavigationTabs from '@/Shared/NavigationTabs.vue';

const props = defineProps({ checks: Array });
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

</script>