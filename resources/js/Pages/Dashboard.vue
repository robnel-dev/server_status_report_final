<script setup>
import AuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head, Link } from '@inertiajs/vue3'
import { Server, HardDrive, ShieldCheck, ClipboardList } from 'lucide-vue-next' // Optional if using lucide icons
</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <!-- <template #header>
      <h2 class="text-2xl font-bold text-gray-800 uppercase">Dashboard</h2>
    </template> -->

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
          
          <!-- Disk Usage -->
          <Link href="/disks" class="block bg-gradient-to-tr from-indigo-500 to-purple-600 text-white p-6 rounded-2xl shadow-md hover:shadow-xl transition duration-300 ease-in-out">
            <div class="flex items-center space-x-4">
              <HardDrive class="w-10 h-10" />
              <div>
                <h3 class="text-lg font-semibold">Disk Usage</h3>
                <p class="text-sm">Monitor storage capacity</p>
              </div>
            </div>
          </Link>

          <!-- Antivirus -->
          <Link href="/antivirus" class="block bg-gradient-to-tr from-green-500 to-emerald-600 text-white p-6 rounded-2xl shadow-md hover:shadow-xl transition duration-300 ease-in-out">
            <div class="flex items-center space-x-4">
              <ShieldCheck class="w-10 h-10" />
              <div>
                <h3 class="text-lg font-semibold">Antivirus</h3>
                <p class="text-sm">Check virus protection</p>
              </div>
            </div>
          </Link>

          <!-- Logs -->
          <Link href="/logs" class="block bg-gradient-to-tr from-yellow-500 to-amber-600 text-white p-6 rounded-2xl shadow-md hover:shadow-xl transition duration-300 ease-in-out">
            <div class="flex items-center space-x-4">
              <ClipboardList class="w-10 h-10" />
              <div>
                <h3 class="text-lg font-semibold">Logs</h3>
                <p class="text-sm">View system activities</p>
              </div>
            </div>
          </Link>

          <!-- Physical Check -->
          <Link href="/physical-checks" class="block bg-gradient-to-tr from-red-500 to-pink-600 text-white p-6 rounded-2xl shadow-md hover:shadow-xl transition duration-300 ease-in-out">
            <div class="flex items-center space-x-4">
              <Server class="w-10 h-10" />
              <div>
                <h3 class="text-lg font-semibold">Physical Check</h3>
                <p class="text-sm">Inspect server room</p>
              </div>
            </div>
          </Link>

        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
