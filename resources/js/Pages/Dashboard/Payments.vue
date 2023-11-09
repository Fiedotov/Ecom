<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head } from '@inertiajs/inertia-vue3'
import { reactive, ref } from 'vue'

const props = defineProps({ user: { type: Object }, showAch: { type: Boolean, default: false }})
const profiles = reactive(props.user.customer_profile?.data.paymentProfiles)
const showAddPaymentModal = ref(false)
const showAddAchModal = ref(false)
const deleting = ref(null)
import AddPaymentModal from '@/Pages/Dashboard/AddPaymentModal.vue'
import Button from '@/Components/Button.vue'
import DeletePaymentMethodModal from '@/Pages/Dashboard/DeletePaymentMethodModal.vue'
import AddAchPaymentModal from '@/Pages/Dashboard/AddAchPaymentModal.vue'

const onPaymentAdded = () => {
  showAddPaymentModal.value = false
  window.alert('Payment method added successfully!')
  window.location.reload()
}

const onDeleted = () => {
  deleting.value = null
  window.alert('Payment method deleted')
  window.location.reload()
}
</script>

<template>
  <Head title="Payment"/>

  <BreezeAuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Payment
      </h2>
    </template>

    <div class="py-6 text-gray-700">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex items-center pb-4">
          <div class="ml-auto flex gap-2">
            <Button @click="showAddPaymentModal = true">
              <i class="fa fa-plus mr-2"></i> Add Payment Method
            </Button>
            <Button
                @click="showAddAchModal = true"
                v-show="showAch && false"
            >
              <i class="fa fa-plus mr-2"></i> Make ACH Payment
            </Button>
          </div>
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <div v-if="! profiles" class="w-full py-10 flex items-center justify-center">
              <i class="fa fa-ban mr-3 leading-none fa-2x"></i> No Payment Methods Found
            </div>

            <div
                v-for="(profile, index) in profiles"
                :key="`profile-${index}`"
                class="flex flex-col lg:flex-row py-4"
                :class="{'border-b border-gray-200': index < profiles.length - 1}"
            >
              <div class="w-1/3 p-1">
                <div class="flex items-center">
                  <i class="fa fa-user-circle fa-2x text-gray-500 mr-2"></i>
                </div>
                <div class="mt-2 font-bold">{{ profile.billTo.firstName }} {{ profile.billTo.lastName }}</div>
                <div>{{ profile.billTo.city }}, {{ profile.billTo.state }}</div>
                <div>{{ profile.billTo.country }}</div>
                <div class="mt-1">{{ profile.billTo.phoneNumber }}</div>
              </div>
              <div class="w-64 p-1">
                <div class="font-bold">{{ profile.payment.creditCard.cardType }}</div>
                <div class="mb-1">{{ profile.payment.creditCard.cardNumber }}</div>
                <div class="flex items-center justify-between">
                  <div class="text-gray-400">Issuer Number</div>
                  <div>{{ profile.payment.creditCard.issuerNumber }}</div>
                </div>
                <div class="flex items-center justify-between">
                  <div class="text-gray-400">Expiration Date</div>
                  <div>{{ profile.payment.creditCard.expirationDate }}</div>
                </div>
              </div>
              <div class="ml-auto">
                <Button
                    @click="deleting = profile"
                    title="Edit Payment Method"
                    display="outline-danger"
                    v-if="profiles.length > 1"
                >
                  Delete
                </Button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <add-ach-payment-modal
        :show="showAddAchModal"
        @close="showAddAchModal = false"
        @added="() => {}"
    />

    <add-payment-modal
        :show="showAddPaymentModal"
        @close="showAddPaymentModal = false"
        @added="onPaymentAdded"
    />

    <delete-payment-method-modal
        :method="deleting"
        :show="Boolean(deleting)"
        @close="deleting = null"
        @deleted="onDeleted"
    />

  </BreezeAuthenticatedLayout>
</template>
