<template>
  <div class="bg-discount-gray py-2 xl:py-12">
    <div class="mx-auto xl:max-w-[96vw]">
      <div class="flex flex-col md:flex-row">
        <div class="md:w-1/2">
            <property-images :property="property" />
        </div>
        <div class="md:w-1/2 px-6">

          <div v-if="property.is_available">
            <div class="text-dark-blue text-3xl font-serif font-bold leading-9 tracking-tight mt-4 mb-2 md:my-0">
              {{ property.title }}
            </div>

            <div class="py-2 text-dark-blue">
              <i class="fa fa-map mr-3"></i>
              <a href="#property-description" class="underline text-black">Check Property On Map</a>
            </div>

            <property-stars />

            <div class="py-2 flex flex-col sm:flex-row sm:gap-3 items-baseline">
              <div class="text-money-green font-semibold text-3xl">
                Just ${{ property.payment_1 }}/month
              </div>
              <div class="text-dark-blue tracking-tight font-bold my-2 sm:my-0">
                ${{ Number(property.cash_price_current) }}/pay in full
              </div>
            </div>

            <div class="mb-2 text-sm text-dark-blue">
              <i class="fa fa-eye mr-3"></i> {{ randomInt }} people are looking at this property
            </div>

            <ul class="text-dark-blue">
              <li v-if="! property.down_payment" class="my-1">
                <i class="fa fa-check-circle text-lg text-money-green mr-3"></i>
                <span class="font-bold">No Down Payment</span>
              </li>
              <li v-if="property.down_payment" class="my-1">
                <i class="fa fa-check-circle text-lg text-money-green mr-3"></i>
                <span class="font-bold" v-if="property.down_payment > 197.00">Low down payment</span>
                <span class="font-bold" v-else>${{ Number(property.down_payment).toFixed(2) }} down payment</span>
              </li>
              <li class="my-1">
                <i class="fa fa-check-circle text-lg text-money-green mr-3"></i>
                <span class="font-bold">0%</span> realtor fees or commission
              </li>
              <li class="my-1">
                <i class="fa fa-check-circle text-lg text-money-green mr-3"></i>
                <span class="font-bold">No credit check</span> or formal underwriting process
              </li>
            </ul>

            <cta-button
                type="Link"
                align="left"
                :href="`#buy-now`"
                target="_blank"
                class="inline-block text-center my-4 py-1 px-16 rounded-xl"
            >
              <div class="text-2xl uppercase font-bold mx-auto py-2 leading-none">
                Buy Now
              </div>
            </cta-button>
            <img src="/images/payment-methods.png" alt="Available Payment Methods" class="my-1"/>
          </div>
          <div v-else class="min-h-[40vh]">
            <div class="font-bold text-2xl font-serif text-center">{{ property.title }}</div>
            <div class="text-center text-3xl text-blue-900 my-10">This Property Has Been Sold</div>

            <cta-button
                type="Link"
                :href="route('home')"
                align="left"
                class="text-center rounded py-[10px]"
            >
              <div class="text-xl uppercase font-bold mx-auto leading-none">
                Search Available Properties
              </div>
            </cta-button>

            <img src="/images/payment-methods.png" alt="Available Payment Methods" class="mt-12 block mx-auto"/>
          </div>

        </div>
      </div>
    </div>
  </div>
</template>

<script>
import CtaButton from '@/Components/CtaButton.vue'
import PropertyImages from '@/Pages/ShowProperty/PropertyImages.vue'
import PropertyStars from '@/Pages/ShowProperty/PropertyStars.vue'

export default {
  name: 'PropertyHeader',
  props: {
    property: {
      type: Object,
      required: true,
    }
  },
  computed: {
    randomInt() {
      return Math.floor(Math.random() * (40 - 10 + 1)) + 10
    }
  },
  components: {
    PropertyStars,
    CtaButton,
    PropertyImages,
  }
}
</script>
