<script>
import DiscountModal from '@/Components/DiscountModal.vue'
import { defineComponent } from 'vue'
import SelectField from '@/Components/SelectField.vue'
import Button from '@/Components/Button.vue'

export default defineComponent({
  components: { Button, DiscountModal, SelectField },
  data () {
    return {
      form: {
        payment_method: undefined,
      },
      submitting: false,
      error: null,
    }
  },
  props: {
    show: {
      type: Boolean,
      required: true,
    },
    payment: {
      type: Object,
    },
    user: {
      type: Object,
    },
  },
  methods: {
    async submit () {
      try {
        this.error = null
        this.submitting = true
        await axios.post(`/api/payments`, this.payload)
        this.$emit('payment')
      } catch (e) {
        this.error = e.response?.data.message || 'Payment error.'
        console.log('payment error', e)
      } finally {
        this.submitting = false
      }
    },
    selectCard (card) {
      this.form.payment_method = card
    },
    cancel () {
      this.form.payment_method = null
      this.$emit('close')
    },
  },
  computed: {
    creditCards () {
      return this.user.customer_profile.data.paymentProfiles
    },
    payload () {
      return {
        profile_id: this.form.payment_method?.customerPaymentProfileId,
        payment_id: this.payment.payment_id,
      }
    },
  },
})
</script>

<template>
  <discount-modal :show="show" @close="cancel" size="sm">
    <template #header>
      <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
        <i class="fa fa-credit-card text-gray-600 mr-2"></i> Make Payment
      </h2>
    </template>

    <template #default>

      <div class="max-w-[500px] mx-auto mb-6">
        <div class="flex items-center leading-none">
          <div class="text-gray-500">ID: {{ payment.payment_id }}</div>
          <img src="/img/discount-lots-logo.png" alt="Discount Lots" class="w-32 ml-auto"/>
        </div>
        <div class="font-bold text-xl mt-3">$ {{ payment.data.Total_Outstanding_Amount_with_Fees__c }}</div>
      </div>

      <div class="mx-auto max-w-[500px]">
        <div class="uppercase font-bold leading-none my-3">Select Payment Method</div>
        <div
            v-for="card in creditCards"
            :key="`card-${card.customerPaymentProfileId}`"
            class="border-2 border-light-blue rounded-lg p-3 mb-4 gap-6 flex items-center cursor-pointer"
            :class="{'bg-gray-100 border-dark-blue': form.payment_method?.customerPaymentProfileId === card?.customerPaymentProfileId}"
            @click="selectCard(card)"
        >
          <div
              class="border border-dark-blue rounded-full w-5 h-5"
              :class="{'bg-dark-blue': form.payment_method?.customerPaymentProfileId === card?.customerPaymentProfileId}"
          ></div>
          <div class="leading-tight">
            <i class="fa fa-credit-card fa-2x leading-none text-gray-400"></i>
            <div class="font-bold text-lg">{{ card.billTo.firstName }} {{ card.billTo.lastName }}</div>
          </div>
          <div class="leading-tight">
            <div class="uppercase">{{ card.payment.creditCard.cardType }}</div>
            <div>{{ card.payment.creditCard.cardNumber }}</div>
          </div>
          <div class="ml-auto">
            <div class="mt-1 text-sm">exp. {{ card.payment.creditCard.expirationDate }}</div>
          </div>
        </div>

        <div class="mt-4">
          <div v-if="error" class="my-2 text-red-500 flex items-center">
            <i class="fa fa-exclamation-triangle mr-2"></i> {{ error }}
          </div>
          <Button class="w-full" :disabled="! form.payment_method || submitting" @click="submit">
            <i class="fa fa-spin fa-spinner mr-2" v-if="submitting"></i>
            Make Payment
          </Button>
        </div>
      </div>

    </template>
  </discount-modal>
</template>