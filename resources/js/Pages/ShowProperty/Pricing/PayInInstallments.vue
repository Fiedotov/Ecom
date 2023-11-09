<template>
  <div class="w-full mx-auto bg-gray-100 border-2 border-cta-orange">
    <div class="text-black text-center py-6 font-serif text-2xl font-bold">
      Pay In Installments (No Credit Check)
    </div>
    <div class="bg-dark-blue text-white text-center pt-2 pb-4 text-sm flex items-center justify-center">
      as low as <span class="text-4xl mx-1">${{ property.payment_1 }}</span> per month
    </div>
    <div class="px-6 pb-6 pt-2">
      <div class="font-semibold text-center mt-2 mb-6">Choose Payment For Each Month</div>

      <div class="flex items-center justify-around my-2 text-lg font-bold">
        <div
            @click="selectPayment(property.term_1)"
            class="text-center cursor-pointer hover:border hover:border-dark-blue overflow-hidden h-16 flex items-center justify-center flex-1"
            :class="{'border border-dark-blue text-red-800 bg-white': selectedPayment === property.term_1, 'border border-gray-150': selectedPayment !== property.term_1}"
            v-if="property.payment_1"
        >
          <div class="py-3 text-xl">$ {{ property.payment_1 }}</div>
        </div>
        <div
            @click="selectPayment(property.term_2)"
            class="text-center cursor-pointer hover:border hover:border-dark-blue overflow-hidden h-16 flex items-center justify-center flex-1"
            :class="{'border border-dark-blue text-red-800 bg-white': selectedPayment === property.term_2, 'border border-gray-150': selectedPayment !== property.term_2}"
            v-if="property.payment_2"
        >
          <div class="py-3 text-xl">$ {{ property.payment_2 }}</div>
        </div>
        <div
            @click="selectPayment(property.term_3)"
            class="text-center cursor-pointer hover:border hover:border-dark-blue overflow-hidden h-16 flex items-center justify-center flex-1"
            :class="{'border border-dark-blue text-red-800 bg-white': selectedPayment === property.term_3, 'border border-gray-150': selectedPayment !== property.term_3}"
            v-if="property.payment_3"
        >
          <div class="py-3 text-xl">$ {{ property.payment_3 }}</div>
        </div>
      </div>

      <div class="bg-white p-4 text-gray-800">
        <div class="">
          <div class="font-bold text-lg font-serif">Payment Terms</div>
          <div class="flex items-center my-2">
            <div>Term</div>
            <div class="ml-auto">{{ term }}</div>
          </div>
          <div class="flex items-center my-2">
            <div>Monthly Payment</div>
            <div class="ml-auto">{{ (monthlyPayment) }}</div>
          </div>
        </div>

        <div class="my-4">
          <div class="font-bold text-lg font-serif">Due Today</div>
          <div class="flex items-center my-2">
            <div>Down Payment</div>
            <div class="ml-auto">$ {{ (property.down_payment || 0) }}</div>
          </div>
          <div class="flex items-center my-2">
            <div>Document Fee</div>
            <div class="ml-auto">$ {{ (property.document_fee) }}</div>
          </div>
          <div class="flex items-center mb-2 mt-4 pt-4 border-t border-gray-200">
            <div class="font-bold text-lg font-serif uppercase">Total</div>
            <div class="ml-auto font-bold text-lg font-serif">$ {{ total }}</div>
          </div>
        </div>

        <cta-button
            label=""
            type="Link"
            align="center"
            :href="checkoutUrl"
            target="_blank"
            class="inline-block w-full text-center mb-3 py-1 rounded-xl"
            title="Checkout"
        >
        <span class="text-xl uppercase font-normal">
          Start Today
        </span>
        </cta-button>
      </div>
    </div>
  </div>
</template>

<script>
import CtaButton from '@/Components/CtaButton.vue'
import { Link } from '@inertiajs/inertia-vue3'
import { moneyFormat } from '@/utils'

export default {
  name: 'PayInInstallments',
  components: {
    CtaButton,
    Link,
  },
  props: {
    property: {
      type: Object,
    }
  },
  data () {
    return {
      selectedPayment: null,
    }
  },
  computed: {
    checkoutUrl () {
      if (!this.selectedPayment) {
        return null
      }

      return this.route('properties.checkout', { apn: this.property.apn, type: `monthly-${this.selectedPayment}` })
    },
    monthlyPayment () {
      if (!this.selectedPayment) {
        return ``
      }

      const payment = {
        [this.property.term_3]: this.property.payment_3,
        [this.property.term_2]: this.property.payment_2,
        [this.property.term_1]: this.property.payment_1,
      }[this.selectedPayment]

      return `$ ${payment}`
    },
    term () {
      if (!this.selectedPayment) {
        return ``
      }

      return `${this.selectedPayment} months`
    },
    total () {
      if (!this.selectedPayment) {
        return ``
      }

      return ((this.property.down_payment || 0) + this.property.document_fee)
    },
  },
  methods: {
    moneyFormat,
    selectPayment (payment) {
      this.selectedPayment = payment
    }
  },
  mounted () {
    if (this.property.payment_1) {
      this.selectedPayment = (this.property.term_3 || this.property.term_2 || this.property.term_1)
    }
  }
}
</script>