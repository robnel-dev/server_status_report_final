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
          <button @click="showModal = true" class="mb-4 px-4 py-2 bg-green-500 text-white rounded">
            + New Check
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
  
          <!-- Checks Table -->
          <table class="min-w-full bg-white shadow-sm rounded-lg">
            <thead>
              <tr class="bg-gray-50">
                <th class="px-6 py-3 text-left">In Charge</th>
                <th class="px-6 py-3 text-left">Aircon Status</th>
                <th class="px-6 py-3 text-left">Amber Alert</th>
                <th class="px-6 py-3 text-left">Remarks</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="check in checks" :key="check.id">
                <td class="px-6 py-4">{{ check.in_charge }}</td>
                <td class="px-6 py-4">{{ check.aircon_status }}</td>
                <td class="px-6 py-4">{{ check.amber_alert ? 'Yes' : 'No' }}</td>
                <td class="px-6 py-4">{{ check.remarks }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </BreezeAuthenticatedLayout>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
  import { Head, useForm } from '@inertiajs/vue3';
  import NavigationTabs from '@/Shared/NavigationTabs.vue';
  
  const props = defineProps({ checks: Array });
  const showModal = ref(false);
  
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
  </script>