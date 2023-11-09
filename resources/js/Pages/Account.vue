<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head, Link } from '@inertiajs/inertia-vue3'
import Empty from '@/Components/Empty.vue'
import UpdatePasswordForm from '@/Components/UpdatePasswordForm.vue'
import CtaButton from '@/Components/CtaButton.vue'

defineProps({ account: { type: Object }, contact: { type: Object } })
</script>

<template>
  <Head title="Account"/>

  <BreezeAuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Account
      </h2>
    </template>

    <div class="py-12 text-gray-700">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" v-if="account">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <table>
              <tbody>
              <tr>
                <td class="py-1 pr-4">Name</td>
                <td class="px-2 py-1 font-bold">{{ account.Name }}</td>
              </tr>
              <tr>
                <td class="py-1 pr-4">Email</td>
                <td class="px-2 py-1 font-bold">{{ account.EMAIL__c }}</td>
              </tr>
              <tr>
                <td class="py-1 pr-4">Phone</td>
                <td class="px-2 py-1 font-bold">{{ account.Phone }}</td>
              </tr>
              <tr v-if="account.MAILING_ADRESS__c">
                <td class="py-1 pr-4">Mailing Address</td>
                <td class="px-2 py-1 font-bold">{{ account.MAILING_ADRESS__c }}</td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <empty v-else icon="fa fa-user-times fa-2x" message="No Account Data Found" />
    </div>


    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white xl:flex xl:items-start gap-8">
            <update-password-form class="xl:w-1/2 border-b border-gray-200 py-4 xl:py-0 xl:border-none" />

            <div class="xl:w-1/2 py-4 xl:py-0">
              <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100">Payment</h2>
              <div class="px-32 mt-8">
                <cta-button type="Link" :href="route('dashboard.payments')">Manage Payment Methods</cta-button>
              </div>
            </div>
        </div>
      </div>
    </div>

  </BreezeAuthenticatedLayout>
</template>
