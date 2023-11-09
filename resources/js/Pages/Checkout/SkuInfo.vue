<template>
  <div>
    <ApplyCoupon :property="property"
                 :paymentCount="paymentCount"
                 @applyDiscount="applyDiscount" />

    <div class="mt-8">
      <template v-for="item in items" :key="`item-${item.key}`">
        <div class="pb-2 flex justify-between">
          <div class="text-sm uppercase leading-none w-64">{{ item.label }}</div>
          <div class="text-discount-blue font-bold leading-none">{{ item.value }}</div>
        </div>
      </template>
    </div>

    <div class="flex items-center justify-between mt-4"
        v-if="appliedDiscount">
      <div class="text-sm leading-none w-64">
        <i class="fa fa-tag align-middle"></i> {{ appliedDiscount.label }}
      </div>
      <div class="text-red-600 font-bold leading-none">- {{ currencyFormat(totalDiscountAmount) }}</div>
    </div>

    <div class="mt-8 flex mb-2">
      <div class="uppercase leading-none font-bold rounded">Due Today</div>
    </div>
    <div>
      <div v-for="(item, index) in lineItems"
           :key="`item-${index}`"
           class="flex items-center justify-between pt-2">
        <div class="font-bold">{{ item.name }}</div>
        <div>{{ currencyFormat(item.amount) }}</div>
      </div>
    </div>

    <div class="border-t border-gray-300 pt-4 py-2">
      <div class="flex items-center justify-between">
        <div class="font-bold uppercase">Total</div>
        <div class="font-bold text-3xl leading-none tracking-tight">{{ currencyFormat(total) }}</div>
      </div>
    </div>

    <template v-if="paymentCount > 1">
      <div class="pt-2 flex">
        <div class="text-sm uppercase leading-none w-40">Recurring Total</div>
        <div class="text-discount-blue font-bold leading-none">
          $ {{ recurringTotal }} / month for {{ paymentCount }} months
        </div>
      </div>
      <div class="flex">
        <div class="w-40"></div>
        <div class="mt-2 text-sm">First Renewal: <span class="ml-1">{{ first_renewal_date }}</span></div>
      </div>
    </template>
  </div>
</template>

<script>
import { sum } from 'lodash'
import { currencyFormat } from '@/utils'
import { SkuInfo } from '@/sku-info'
import skuInfo from './SkuInfo.vue'
import ApplyCoupon from '@/Pages/Checkout/Form/ApplyCoupon.vue'

export default {
  name: 'SkuInfo',

  components: {
    ApplyCoupon,
  },

  props: {
    property: {
      type: Object,
      required: true,
    },
    
    lineItems: {
      type: Array,
      required: true,
    },

    paymentCount: {
      type: Number,
      required: true,
    },

    first_renewal_date: {
      type: String,
      required: true,
    },
  },

  data() {
    return {
      skuInfo: new SkuInfo(this.property, this.paymentCount, this.product_group_ids),
      appliedDiscount: null
    }
  },

  computed: {
    skuInfo () {
      return skuInfo
    },

    description () {
      return this.skuInfo.description()
    },

    items () {
      return this.skuInfo.items()
    },

    total () {
      if (this.paymentCount === 1) {
        return sum(this.lineItems.map(item => item.amount)) - this.skuInfo.totalDiscount()
      }
      
      return sum(this.lineItems.map(item => item.amount))
    },

    recurringTotal() {
      return this.skuInfo.recurringTotal().toFixed(2)
    },

    totalDiscountAmount() {
      return this.skuInfo.totalDiscount().toFixed(2)
    }
  },
  methods: {
    currencyFormat,

    applyDiscount(discountPromo) {
      this.appliedDiscount = discountPromo
      this.skuInfo.clearDiscount()

      if (!discountPromo) {
        this.$emit('discountApplied', null)
        return
      }

      this.skuInfo.setDiscount(discountPromo.promotion_rewards[0].config)

      const markedPrice = this.paymentCount > 1
        ? this.skuInfo.paymentAmount()
        : this.skuInfo.subtotal()

      this.$emit('discountApplied', {
        promotionId: discountPromo.id,
        promotionLabel: discountPromo.label,
        discountAmount: +this.totalDiscountAmount,
        markedPrice: markedPrice
      });
    }
  },
}
</script>