<script setup>
import DiscountModal from '@/Components/DiscountModal.vue'
import Button from '@/Components/Button.vue'
import { ref } from 'vue'
import axios from 'axios'

const props = defineProps({
  show: {
    type: Boolean,
    required: true,
  },
  method: {
    type: Object,
    required: true,
  }
})

const deleting = ref(false)
const emit = defineEmits(['deleted'])

const deletePaymentMethod = () => {
  deleting.value = true
  axios.delete(`/dashboard/payment-methods`, {data: { id: props.method.customerPaymentProfileId}})
    .then(() => {
      deleting.value = false
      emit('deleted')
    })
    .catch(error => {
      console.log(error)
    })
}
</script>


<template>
  <discount-modal :show="show" @close="$emit('close')" size="sm">
<template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        <i class="fa fa-credit-card mr-2"></i> Delete Payment Method
      </h2>
    </template>

    <template #default>
      <div class="text-dark-blue font-bold mt-6">
        Are you sure you want to delete this payment method?
      </div>
      <div class="flex my-8">
        <div class="flex-1">
          <div class="font-bold">{{ method.payment.creditCard.cardType }}</div>
          <div>{{ method.payment.creditCard.cardNumber }}</div>
          <div>Expiry: {{ method.payment.creditCard.expirationDate }}</div>
        </div>
        <div class="flex-1">
          <div class="font-bold">{{ method.billTo.firstName }} {{ method.billTo.lastName }}</div>
          <div>{{ method.billTo.city }}, {{ method.billTo.state }}</div>
          <div>{{ method.billTo.country }}</div>
          <div>{{ method.billTo.zip }}</div>
          <div>{{ method.billTo.phoneNumber }}</div>
        </div>
      </div>
      <div class="py-4">
        <Button @click="deletePaymentMethod" :disabled="deleting" title="Delete Payment Method" display="danger">
          <i v-if="deleting" class="fa fa-spinner fa-spin mr-2"></i> Yes, Delete
        </Button>

        <Button class="ml-2" display="outline" @click="$emit('close')">
          Cancel
        </Button>
      </div>
    </template>
  </discount-modal>
</template>