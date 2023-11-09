<template>
  <div class="w-full mx-auto bg-gray-100 border-2 border-gray-150">
    <div class="text-black text-center py-6 font-serif text-4xl font-bold">
      Pay In Full
    </div>

    <div class="text-center pb-4 pt-2 relative bg-dark-blue text-white">
      <div>
        <span class="text-3xl mr-2">${{ property.cash_price_current }}</span>
        <span class="line-through">${{ property.original_price }}</span> <span class="font-bold text-red-500"></span></div>
    </div>
    <div class="p-6">
      <div class="text-center tracking-tight leading-5">
        <span class="font-bold">Get a BIG discount when you pay in full!</span> Submit your initial payment & our team
        will contact you to arrange payment for the remaining balance.
      </div>

      <div class="my-4 text-gray-800">
        <div class="font-bold text-lg font-serif">Your Price</div>
        <div class="flex items-center my-2">
          <div>Original Price</div>
          <div class="ml-auto">$ {{ property.original_price }}</div>
        </div>
        <div class="flex items-center my-2">
          <div>Limited Offer Discount</div>
          <div class="ml-auto text-red-500">
            - $ {{ property.discount_amount }}
          </div>
        </div>
        <div class="flex items-center my-2">
          <div>Discounted Price</div>
          <div class="ml-auto">$ {{ property.cash_price_current }}</div>
        </div>

        <div class="my-4">
          <div class="font-bold text-lg font-serif">Due Today</div>
          <div class="flex items-center my-2">
            <div>Initial Payment</div>
            <div class="ml-auto">$ {{ (property.cash_price_current) }}</div>
          </div>
          <div class="flex items-center my-2">
            <div>Document Fee</div>
            <div class="ml-auto">$ {{ (300) }}</div>
          </div>
          <div class="flex items-center mb-2 pt-4 font-serif border-t border-gray-200">
            <div class="font-bold text-lg uppercase">Total</div>
            <div class="ml-auto font-bold text-lg">$ {{ (property.cash_total) }}</div>
          </div>
        </div>
      </div>

      <cta-button
          label=""
          type="Link"
          align="center"
          :href="route('properties.checkout', { apn: property.apn, type: 'full' })"
          target="_blank"
          class="inline-block w-full mx-auto py-1 rounded-xl mt-6"
      >
        <span class="text-xl uppercase font-normal">
          Pay In Full and Save
        </span>
      </cta-button>
    </div>
  </div>
</template>

<script>


import CtaButton from '@/Components/CtaButton.vue'
import { Link } from '@inertiajs/inertia-vue3'
import { moneyFormat } from '@/utils'

export default {
  name: 'PayInFull',
  components: {
    CtaButton,
    Link,
  },
  props: {
    property: {
      type: Object,
      required: true,
    }
  },
  methods: {
    moneyFormat,
  }
}
</script>
