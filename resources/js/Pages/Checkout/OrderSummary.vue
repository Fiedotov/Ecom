<template>
  <div
      class="leading-none py-3 mb-4 flex items-center justify-between text-sm xl:hidden"
      :class="{'border-b border-gray-200': open}"
  >
    <div class="text-discount-blue flex items-center" @click="toggleDisplay">
      <i class="fa fa-shopping-cart mr-2"></i>
      <div>{{ open ? 'Hide' : 'Show' }} Order Summary</div>
      <i class="fa ml-2" :class="{'fa-chevron-down': ! open, 'fa-chevron-up': open}"></i>
    </div>
    <div class="font-bold tracking-tight">${{ moneyFormat(total) }}</div>
  </div>

  <div v-show="open">
    <property-info
        :property="property"
        class="mb-4 xl:mt-4"
    />

    <sku-info
        :property="property"
        :line-items="checkout.lineItems"
        :payment-count="checkout.paymentCount"
        :first_renewal_date="first_renewal_date"
        @discountApplied="discountApplied"
    />
  </div>

</template>

<script>
import PropertyInfo from '@/Pages/Checkout/PropertyInfo.vue'
import SkuInfo from '@/Pages/Checkout/SkuInfo.vue'
import { sum } from 'lodash'
import { moneyFormat } from '@/utils'

export default {
  name: 'OrderSummary',
  components: {
    PropertyInfo,
    SkuInfo,
  },
  props: {
    property: {
      type: Object,
      required: true,
    },
    checkout: {
      type: Object,
      required: true,
    },
    first_renewal_date: {
      type: String,
      required: true,
    },
  },
  data () {
    return {
      open: true,
    }
  },
  computed: {
    total () {
      return sum(this.checkout.lineItems.map(item => item.amount))
    }
  },
  methods: {
    moneyFormat,
    toggleDisplay () {
      this.open = !this.open
    },

    discountApplied(data) {
      this.$emit('discountApplied', data)
    }
  }
}
</script>