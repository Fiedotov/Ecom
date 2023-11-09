<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head } from '@inertiajs/inertia-vue3'
import Empty from '@/Components/Empty.vue'

defineProps({ contact: { type: Object } })
</script>

<template>
  <Head title="Contact"/>

  <BreezeAuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Contact
      </h2>
    </template>

    <div class="py-12">
      <div v-if="contact" class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <table>
              <tbody>
              <tr>
                <td class="py-1 pr-4">ID</td>
                <td class="px-2 py-1 font-bold">{{ contact.Id }}</td>
              </tr>
              <tr>
                <td class="py-1 pr-4">Name</td>
                <td class="px-2 py-1 font-bold">{{ contact.Name }}</td>
              </tr>
              <tr>
                <td class="py-1 pr-4">Email</td>
                <td class="px-2 py-1 font-bold">{{ contact.Email }}</td>
              </tr>
              <tr>
                <td class="py-1 pr-4">Phone</td>
                <td class="px-2 py-1 font-bold">{{ contact.Phone }}</td>
              </tr>
              <tr>
                <td class="py-1 pr-4">Address</td>
                <td class="px-2 py-1 font-bold">{{ contact.Mailing_Address__c }}</td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <empty v-else message="No Contact Data Found" icon="fa fa-ban fa-2x"></empty>
    </div>
  </BreezeAuthenticatedLayout>
</template>
