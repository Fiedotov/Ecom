<template>
  <div class="search-land-for-sale">
    <discount-lots-header/>

    <div class="bg-discount-gray">
      <div class="container mx-auto pt-10 pb-10 lg:px-12">
        <div class="text-center font-serif text-5xl text-dark-blue mb-18 font-bold">
          Find Your Dream Property
        </div>

        <search-land-filters
            :states="states"
            :filter_settings="filter_settings"
            class="mt-8"
            @filtered="onFiltered"
        />
      </div>

      <div class="container mx-auto pb-10">
        <search-land-map
            :points="filteredPoints"
            @filtered="onMapFiltered"
            v-if="filteredPoints.length > 0"
        />
      </div>

      <property-search-results
          class="container mx-auto"
          :properties="mapPoints"
      />

      <testimonials-banner/>

      <faq-banner/>
    </div>

    <discount-lots-footer/>
  </div>
</template>

<script>
import { clone } from 'lodash'
import DiscountLotsHeader from '@/Layouts/Partials/DiscountLotsHeader.vue'
import DiscountLotsFooter from '@/Layouts/Partials/DiscountLotsFooter.vue'
import FaqBanner from '@/Components/FaqBanner.vue'
import PropertySearchResults from '@/Pages/SearchLandForSale/PropertySearchResults.vue'
import SearchLandFilters from '@/Pages/SearchLandForSale/SearchLandFilters.vue'
import SearchLandMap from '@/Pages/SearchLandForSale/SearchLandMap.vue'
import TestimonialsBanner from '@/Components/TestimonialsBanner.vue'

export default {
  name: 'SearchLandForSale',
  components: {
    PropertySearchResults,
    DiscountLotsHeader,
    DiscountLotsFooter,
    FaqBanner,
    SearchLandFilters,
    SearchLandMap,
    TestimonialsBanner,
  },
  props: {
    states: {
      type: Array,
      required: true,
    },
    filter_settings: {
      type: Object,
      required: true,
    },
    points: {
      type: Array,
      required: true,
    }
  },
  data () {
    return {
      mapPoints: [],
      filteredPoints: [],
    }
  },
  methods: {
    onFiltered (filters) {
      this.filteredPoints = clone(this.points)
          .filter(point => filters.state.length === 0 ? true : filters.state.includes(point.state))
          .filter(point => filters.county.length === 0 ? true : filters.county.includes(point.county))
          .filter(point => filters.monthly_payment[0] <= point.payment_1 && filters.monthly_payment[1] >= point.payment_1)
          .filter(point => filters.price[0] <= point.cash_price_current && filters.price[1] >= point.cash_price_current)
          .filter(point => filters.acreage[0] <= point.acreage && filters.acreage[1] >= point.acreage)
    },
    onMapFiltered (mapPoints) {
      this.mapPoints = mapPoints
    }
  },
  mounted () {
    this.filteredPoints = this.points
  }
}
</script>
