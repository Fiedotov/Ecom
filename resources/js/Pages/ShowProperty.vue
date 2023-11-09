<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head, Link } from '@inertiajs/inertia-vue3'
import AddAchPaymentModal from '@/Pages/Dashboard/AddAchPaymentModal.vue'
import { computed, ref } from 'vue'
import MakePaymentModal from '@/Pages/Dashboard/MakePaymentModal.vue'
import { Inertia } from '@inertiajs/inertia'
import { notify } from "@kyvg/vue3-notification";

const showAddAchModal = ref(null)
const selectedPayment = ref(null)

const props = defineProps({
  contract: { type: Object },
  user: { type: Object },
})

const makePayment = (payment) => {
  selectedPayment.value = payment
}

const onPayment = () => {
  notify({ title: 'Payment Submitted Successfully', type: 'success' });

  selectedPayment.value = null

  setTimeout(() => {
    Inertia.reload({preserveScroll: true})
  }, 5000)
}

const payments = computed(
  () => props.contract.payments.sort((a, b) => new Date(a.data.Payment_Due_Date__c) - new Date(b.data.Payment_Due_Date__c))
)

const property = computed(() => props.user.sf_properties.find(property => property.APN__c === props.contract.data.APN__c))

const showAlert = (text) => {
  notify({
    title: 'Notification Title',
    type: 'success',
    duration: 10000,
    text
  });
}
</script>

<template>
  <BreezeAuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Property {{ contract.data.APN__c }}
      </h2>
    </template>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-6 text-gray-600">
      <div class="p-6 bg-white rounded">
        <div class="lg:flex justify-between">
          <div v-if="property">
            <div class="font-bold">Property</div>
            <div class="mt-4">
              <div>{{ property.City__c }}, {{ property.State__c }}</div>
              <div>{{ property.County__c }}</div>
              <div class="mt-4">
                <div class="text-xs uppercase">Name</div>
                {{ property.Name }}
              </div>
              <div class="mt-4">
                <div class="text-xs uppercase">APN</div>
                {{ property.APN__c }}
              </div>
              <div class="mt-4">
                <div class="text-xs uppercase">Zoning</div>
                {{ property.Zoning__c }}
              </div>
            </div>
          </div>
          <div>
            <div class="font-bold">Contract</div>
            <div class="mt-4">
              <div class="mt-4">
                <div class="text-xs uppercase">ID</div>
                {{ contract.contract_id }}
              </div>

              <div class="mt-4">
                <div class="text-xs uppercase">Start Date</div>
                {{ contract.data.StartDate }}
              </div>

              <div class="mt-4">
                <div class="text-xs uppercase">End Date</div>
                {{ contract.data.EndDate }}
              </div>

              <div class="mt-4">
                <div class="text-xs uppercase">Billing Address</div>
                <div class="mt-1">
                  <div>{{ contract.data.BillingAddress.street }}</div>
                  <div>{{ contract.data.BillingAddress.city }}, {{ contract.data.BillingAddress.state }}</div>
                  <div>{{ contract.data.BillingAddress.postalCode }}</div>
                  <div>{{ contract.data.BillingAddress.country }}</div>
                </div>
              </div>

            </div>
          </div>
          <div>
            <Link
                :href="route('dashboard.properties')"
                class="text-discount-blue font-bold"
                :title="`View Payments for Contract ${contract.contract_id}`"
            >
              <i class="fa fa-arrow-left mr-2"></i> Back to Contracts
            </Link>
          </div>
        </div>
        <div class="font-bold uppercase mt-10">Payment Schedule</div>
        <table class="w-full mt-4">
          <thead>
          <tr class="border-b border-gray-300">
            <th class="text-left px-2 py-3">Id</th>
            <th class="text-left px-2 py-3">Status</th>
            <th class="text-left px-2 py-3">Payment Date</th>
            <th class="text-left px-2 py-3">Due Date</th>
            <th class="text-left px-2 py-3">Amount Owing</th>
            <th class="text-left px-2 py-3">Amount Paid</th>
            <th class="text-left px-2 py-3"></th>
          </tr>
          </thead>
          <tbody>
          <tr
              v-for="(payment, index) in payments"
              :key="`payment-${index}`"
              :class="{'border-b border-gray-200': index < contract.payments.length - 1, 'bg-dark-blue text-white': selectedPayment === payment}"
          >
            <td class="px-2 py-3">{{ payment.payment_id }}</td>
            <td class="px-2 py-3">
              <div
                  class="flex items-center text-sm uppercase"
                  :class="{
                  'text-green-600': payment.data.Payment_Status__c,
                  'text-gray-500': ! payment.data.Payment_Status__c
              }">
                <i class="fa mr-2"
                   :class="{'fa-clock': ! payment.data.Payment_Status__c, 'fa-check': payment.data.Payment_Status__c}"></i>
                {{ payment.data.Payment_Status__c || 'Pending' }}
              </div>
            </td>
            <td class="px-2 py-3">
              {{ payment.data.Payment_Date__c }}
            </td>
            <td class="px-2 py-3">
              <div v-if="payment.data.Payment_Status__c !== 'Paid'">{{ payment.data.Payment_Due_Date__c }}</div>
              <div v-else>--</div>
            </td>
            <td class="px-2 py-3">{{ Number(payment.data.Total_Outstanding_Amount_with_Fees__c).toFixed(2) }}</td>
            <td class="px-2 py-3">{{ Number(payment.data.Total_Paid_Amount__c).toFixed(2) }}</td>
            <td>
              <button
                  v-if="payment.data.Payment_Status__c !== 'Paid'"
                  class="bg-discount-blue text-white uppercase rounded px-8 py-2 leading-none font-bold"
                  @click="makePayment(payment)"
              >
                Pay Now
              </button>
            </td>
          </tr>
          </tbody>
        </table>
      </div>
    </div>

    <add-ach-payment-modal
        :show="Boolean(showAddAchModal)"
        :payment="showAddAchModal"
        @close="showAddAchModal = null"
        @added="() => {}"
    />

    <make-payment-modal
        :show="Boolean(selectedPayment)"
        :payment="selectedPayment"
        :user="user"
        @close="selectedPayment = null"
        @payment="onPayment"
    />

  </BreezeAuthenticatedLayout>

  <Head :title="`Property ${contract.data.APN__c}`"/>
</template>
