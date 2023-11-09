<template>
  <div class="property-description bg-white" id="property-description">
    <div class="mx-auto lg:max-w-[60vw] flex flex-col lg:flex-row text-dark-blue py-12">
      <div class="lg:flex-1 px-6 lg:pr-12">
        <div v-if="property.video_tour_url" class="mb-8">
          <div class="text-3xl font-serif font-bold">Property Video Tour</div>

          <div style="left: 0; width: 100%; height: 0; position: relative; padding-bottom: 56.338%;" class="my-4">
            <iframe
                :src="property.video_tour_url"
                style="top: 0; left: 0; width: 100%; height: 100%; position: absolute; border: 0;"
                allowfullscreen
                scrolling="no"
                allow="encrypted-media;"
            ></iframe>
          </div>
        </div>

        <div class="text-3xl font-serif font-bold mb-4">Property Description</div>
        <div
            class="my-2"
            v-if="property.description"
        >
          <div v-for="(paragraph, index) in property.description" :key="`paragraph-${index}`" class="my-6">
            {{ paragraph }}
          </div>
        </div>

        <div class="font-bold mt-8">{{ property.zoning_headline }}</div>
        <div v-if="property.zone_item_4" class="my-4 flex items-center">
          <i class="fa fa-check-circle text-green-700 fa-2x mr-4"></i> {{ property.zone_item_4 }}
        </div>
        <div v-if="property.zone_item_3" class="my-4 flex items-center">
          <i class="fa fa-check-circle text-green-700 fa-2x mr-4"></i> {{ property.zone_item_3 }}
        </div>
        <div v-if="property.zone_item_2" class="my-4 flex items-center">
          <i class="fa fa-check-circle text-green-700 fa-2x mr-4"></i> {{ property.zone_item_2 }}
        </div>
        <div v-if="property.zone_item_1" class="my-4 flex items-center">
          <i class="fa fa-check-circle text-green-700 fa-2x mr-4"></i> {{ property.zone_item_1 }}
        </div>
        <div v-if="property.usage">
          <div class="font-bold">On Property Usage / Potential:</div>
          <div class="grid grid-cols-2 gap-2 my-6">
            <div v-for="(usage, index) in property.usage" :key="`usage-${index}`" class="flex items-center gap-2">
              <img :src="usage.icon" :alt="usage.name" class="max-w-[42px]" />
              <div>{{ usage.name }}</div>
            </div>
          </div>
        </div>
        <div class="my-4">{{ property.after_zoning_text }}</div>
        <div class="my-4">{{ property.cta_text }}</div>
        <div class="my-4">
          <span class="font-bold mr-2">Disclaimer:</span>
          <span class="italic">
            We always encourage buyers to perform their due diligence before committing to the property.
          </span>
        </div>
      </div>
      <div class="lg:flex-1 px-6 lg:pl-12">
        <div class="text-3xl font-serif font-bold mb-4">Property Location</div>

        <div class="my-4"><span class="font-bold mr-2 text-lg">Property Address:</span>
          <template v-if="property.street">{{ property.street }}</template>
          <template v-if="property.city">, {{ property.city }}</template>
          <template v-if="property.state">, {{ property.state }}</template>
          <template v-if="property.county">, {{ property.county }}</template>
          <template v-if="property.zip_code">, {{ property.zip_code }}</template>
        </div>
        <div class="my-8">
          <div class="font-bold mr-2 text-lg">The boundaries of the lot are at GPS coordinates:</div>
          <div
              v-for="(coordinate, index) in property.corner_coordinates"
              :key="`coordinate-${index}`"
          >
            {{ coordinate.lat }}, {{ coordinate.lng }}
          </div>
        </div>

        <div class="h-auto">
          <property-map :property="property"/>
        </div>

        <div class="my-6"><span class="font-bold">Legal Description:</span> {{ property.legal_description }}</div>

      </div>
    </div>
  </div>
</template>

<script>
import VueGoogleMaps from '@fawmi/vue-google-maps'
import PropertyMap from '@/Pages/ShowProperty/PropertyMap.vue'

export default {
  name: 'PropertyDescription',
  props: {
    property: {
      type: Object,
      required: true,
    },
  },
  components: {
    PropertyMap,
    VueGoogleMaps,
  },
  data () {
    return {
      polygonOptions: {
        strokeColor: '#489CD8',
        strokeOpacity: 0.8,
        strokeWeight: 3,
        fillColor: '#489CD8',
        fillOpacity: 0.35,
      },
    }
  },
}
</script>
