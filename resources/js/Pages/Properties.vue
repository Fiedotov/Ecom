<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head, Link } from '@inertiajs/inertia-vue3'
import Empty from '@/Components/Empty.vue'

defineProps({ contracts: { type: Array } })
</script>

<template>
  <Head title="Properties"/>

  <BreezeAuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Properties
      </h2>
    </template>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-6 text-gray-600" v-if="contracts.length > 0">
      <div>Displaying <span class="font-bold">{{ contracts.length }}</span> properties</div>
    </div>

    <div class="my-6" v-if="contracts.length > 0">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
              <table class="w-full">
                  <thead>
                  <tr class="border-b border-gray-300">
                      <th class="px-2 py-3 text-left">APN</th>
                      <th class="px-2 py-3 text-left">Email</th>
                      <th class="px-2 py-3 text-left">Start Date</th>
                      <th class="px-2 py-3 text-left">City</th>
                      <th class="px-2 py-3 text-left">Terms</th>
                      <th class="px-2 py-3 text-left"></th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr
                    v-for="(contract, index) in contracts"
                    :key="`contract-${contract.contract_id}`"
                    :class="{'border-b border-gray-200': index < contracts.length - 1}"
                  >
                    <td class="px-2 py-3">{{ contract.data.APN__c }}</td>
                    <td class="px-2 py-3">{{ contract.data.Email__c }}</td>
                    <td class="px-2 py-3">{{ contract.data.StartDate }}</td>
                    <td class="px-2 py-3">
                      <div>{{ contract.data.City_of_property__c }}</div>
                      <div>{{ contract.data.County_State__c }}</div>
                    </td>
                    <td class="px-2 py-3">
                      <div v-if="contract.data.ContractTerm === 1">Pay In Full</div>
                      <div v-else>$ {{ Number(contract.data.Monthly_Payment_with_Escrow__c).toFixed(2) }}/mo for {{ contract.data.ContractTerm }} months</div>
                    </td>
                    <td class="px-2 py-3 text-center">
                        <Link
                            :href="route('dashboard.properties.show', contract.contract_id)"
                            class="text-discount-blue font-bold text-sm"
                            :title="`View Payments for Contract ${contract.contract_id}`"
                        >
                            View
                        </Link>
                    </td>
                  </tr>
                  </tbody>
              </table>
          </div>
        </div>
      </div>
    </div>

    <empty icon="fa fa-ban fa-2x" message="No Contracts Found" v-if="contracts.length === 0" class="mt-12" />
  </BreezeAuthenticatedLayout>
</template>
