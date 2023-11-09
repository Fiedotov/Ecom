<template>
  <div class="property-search-results px-4 md:px-0">
    <div class="text-2xl font-bold text-dark-blue mb-4"><span class="font-bold">{{ properties.length }}</span>
      Properties Match Your Search
    </div>

    <div class="w-1/3 pb-5">
      <property-search-results-filter @sorted="onSorted"/>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <property-card
          v-for="(property, index) in displayedResults"
          :property="property"
          :key="`property-${property.id}`"
      />
    </div>

    <button
        v-if="displayedResults.length < properties.length"
        @click="loadMore"
        class="border-2 border-cta-orange py-3 px-14 leading-none rounded uppercase font-bold text-cta-orange mx-auto block my-16 hover:text-white hover:bg-cta-orange"
        title="Load More Results"
    >
      Load More
    </button>
  </div>
</template>

<script>
import CtaButton from '@/Components/CtaButton.vue'
import { Link } from '@inertiajs/inertia-vue3'
import PropertyCard from '@/Pages/SearchLandForSale/PropertyCard.vue'
import PropertySearchResultsFilter from '@/Pages/SearchLandForSale/PropertySearchResultsFilter.vue'
import { orderBy } from 'lodash'

export default {
  name: 'PropertySearchResults',
  components: {
    CtaButton,
    Link,
    PropertyCard,
    PropertySearchResultsFilter,
  },
  props: {
    properties: {
      type: Array,
      required: true,
    },
  },
  data () {
    return {
      visibleCount: 18,
      sort: null,
    }
  },
  computed: {
    displayedResults () {
      if (!this.sort) {
        return this.properties.slice(0, this.visibleCount)
      }

      const properties = orderBy(this.properties, [this.sort.field], [this.sort.direction])

      return properties.slice(0, this.visibleCount)
    },
  },
  methods: {
    loadMore () {
      this.visibleCount = Math.min(this.visibleCount + 18, this.properties.length)
    },
    onSorted (sort) {
      this.sort = sort
    },
  },
  watch: {
    properties (newValue, oldValue) {
      this.visibleCount = 18
    },
  },
}
</script>
