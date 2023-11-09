<template>
  <GMapMap
      v-if="property.corner_coordinates.length > 0"
      :center="{lat: property.latitude, lng: property.longitude}"
      :zoom="17"
      map-type-id="hybrid"
      style="width: 100%; height: 40vh; max-height: 600px; margin: auto;"
      ref="propertyMap"
  >
    <GMapPolygon
        :paths="property.corner_coordinates"
        :options="polygonOptions"
    ></GMapPolygon>
  </GMapMap>
</template>

<script>
import VueGoogleMaps from '@fawmi/vue-google-maps'

export default {
  name: 'PropertyMap',
  components: {
    VueGoogleMaps,
  },
  props: {
    property: {
      type: Object,
      required: true,
    },
  },
  data () {
    return {
      polygonOptions: {
        strokeColor: '#489CD8',
        strokeOpacity: 0.8,
        strokeWeight: 3,
        fillColor: '#489CD8',
        fillOpacity: 0.35,
      }
    }
  },
}
</script>