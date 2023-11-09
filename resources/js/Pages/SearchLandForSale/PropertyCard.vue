<template>
  <div class="bg-white p-4 rounded property-card">
    <div>
      <div v-if="dataProperty.images.length > 0">
        <img class="rounded-lg" :src="dataProperty.images[0].url" :alt="dataProperty.name"/>
      </div>

      <div class="saturate-0 border border-gray-200 rounded max-h-[160px] flex" v-else>
        <img src="/img/discount-lots-logo.png" alt="Discount Lots" class="block mx-auto h-12 my-14"/>
      </div>
    </div>

    <div class="px-4">
      <div class="font-bold text-money-green mt-4 text-2xl">Just ${{ dataProperty.payment_1 }}/month</div>

      <div class="my-3 text-dark-blue text-sm">Zoning: {{ property.zoning }}</div>

      <div class="flex items-center text-dark-blue border-t py-5 gap-6">
        <div class="flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="text-money-green w-6">
            <polygon fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-miterlimit="10" points="2,22 6,14 10,17 16,9 22,22 " stroke-linejoin="round"></polygon>
            <circle fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-miterlimit="10" cx="8" cy="5" r="3" stroke-linejoin="round"></circle>
          </svg>
          <div>{{ Number(dataProperty.acreage).toFixed(2) }} acres</div>
        </div>
        <div class="flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="text-money-green w-6">
            <path fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-miterlimit="10" d="M20,9c0,4.9-8,13-8,13 S4,13.9,4,9c0-5.1,4.1-8,8-8S20,3.9,20,9z" stroke-linejoin="round"></path>
            <circle fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-miterlimit="10" cx="12" cy="9" r="3" stroke-linejoin="round"></circle>
          </svg>
          <div>{{ dataProperty.city }}, {{ dataProperty.state }}</div>
        </div>
      </div>

      <div>
        <cta-button
            label=""
            type="Link"
            align="center"
            :href="route('properties.show', dataProperty.apn)"
            target="_blank"
            class="py-1 mx-auto px-1 w-4/5"
        >
          <div class="text-xl uppercase font-bold mx-auto py-1">
            Learn More <span class="inline-block ml-2">👉</span>
          </div>
        </cta-button>
      </div>
    </div>
  </div>
</template>

<script>
import CtaButton from '@/Components/CtaButton.vue'

export default {
  name: 'PropertyCard',
  components: {
    CtaButton,
  },
  data () {
    return {
      dataProperty: {
        apn: '',
        acreage: '',
        city: '',
        images: [],
        name: '',
        payment_1: '',
        cash_price_current: '',
        state: '',
      },
    }
  },
  props: {
    property: {
      type: Object,
      required: true,
    },
  },
  mounted () {
    this.dataProperty = JSON.parse(JSON.stringify(this.property))
  },
}
</script>
