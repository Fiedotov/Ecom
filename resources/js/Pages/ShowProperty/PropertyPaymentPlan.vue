<template>
  <div class="property-payment-plan text-dark-blue py-12 mx-auto max-w-screen-lg" id="buy-now">
    <div class="text-center font-serif font-bold tracking-tight text-4xl mb-12">
      Select Your Payment Plan
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-8 mx-2 xl:mx-0">

      <pay-in-installments
          :property="property"
      />

      <pay-in-full
          :property="property"
          v-if="property.cash_price_current"
      />

    </div>

    <div class="text-center pt-4 text-gray-800 text-sm">All purchases are subject to review and the following <a href="#">policies</a>.</div>
  </div>
</template>

<script>
import CtaButton from '@/Components/CtaButton.vue'
import PayInFull from '@/Pages/ShowProperty/Pricing/PayInFull.vue'
import PayInInstallments from '@/Pages/ShowProperty/Pricing/PayInInstallments.vue'

export default {
  name: 'PropertyPaymentPlan',
  components: {
    CtaButton,
    PayInFull,
    PayInInstallments,
  },
  props: {
    property: {
      type: Object,
      required: true,
    }
  },
}
</script>
