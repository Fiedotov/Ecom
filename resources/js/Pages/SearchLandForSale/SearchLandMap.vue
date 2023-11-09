<template>
  <GMapMap
      :center="center"
      :zoom="getInitialZoomLevel()"
      map-type-id="hybrid"
      style="width: 100%; height: 70vh; max-height: 1000px; margin: auto;"
      ref="propertyMap"
  >
    <GMapCluster
        :zoomOnClick="true"
    >
      <GMapMarker
          v-for="(point, index) in dataPoints"
          :key="`point-${point.id}-${index}`"
          :position="{ lat: point.latitude, lng: point.longitude }"
          @click="setFocus(point)"
          :title="`${point.acreage} acres in ${point.city}, ${point.state}`"
          :ref="`marker${point.id}`"
          :icon="{ url: '/img/map-marker.png', size: { width: 60, height: 60 }, scaledSize: { width: 60, height: 60 }}"
      >
        <GMapInfoWindow
            :opened="focusPoint && focusPoint.id === point.id"
            :closeclick="true"
            @closeclick="setFocus(null)"
        >
          <div class="font-bold text-lg">{{ `${point.acreage.toFixed(2)} acres in ${point.city}, ${point.state}` }}</div>
          <div class="text-blue-800 text-md">Just ${{ point.payment_1 }}/month and $1 down!</div>
          <div>
            <Link
                :href="route('properties.show', point.apn)"
                class="text-white bg-orange-500 leading-none m-0 p-2 rounded mt-2 inline-block"
                :title="`${point.acreage} acres in ${point.city}, ${point.state}`"
            >
              Buy Now
            </Link>
          </div>
        </GMapInfoWindow>
      </GMapMarker>
    </GMapCluster>
  </GMapMap>
</template>

<script>
import VueGoogleMaps from '@fawmi/vue-google-maps'
import { clone, find } from 'lodash'
import { Link } from '@inertiajs/inertia-vue3'

export default {
  name: 'SearchLandMap',
  components: {
    VueGoogleMaps,
    Link,
  },
  props: {
    points: {
      type: Array,
      required: true,
    },
  },
  data () {
    return {
      center: { lat: 36.820812, lng: -97.378872 },
      map: null,
      focusPoint: null,
      markers: [],
      visibleIds: [],
      dataPoints: [],
    }
  },
  methods: {
    setFocus (marker) {
      if (!marker) {
        return
      }

      this.focusPoint = marker
    },
    setDataPoints () {
      this.dataPoints = clone(this.points)
    },
    async getMap () {
      if (!this.$refs.propertyMap) {
        return null
      }

      this.map = await this.$refs.propertyMap.$mapPromise

      return this.map
    },
    async setListeners () {
      this.map = await this.getMap()

      this.map.addListener('bounds_changed', () => {
        this.emitVisibleMarkers()
      })
    },
    async emitVisibleMarkers () {
      const allPoints = this.dataPoints
          .filter(dataPoint => {
            return Boolean(this.$refs[`marker${dataPoint.id}`][0])
          })
          .map((dataPoint) => ({
            marker: this.$refs[`marker${dataPoint.id}`][0].$markerPromise,
            point: dataPoint
          }))

      const map = await this.getMap()

      if (!map.getBounds()) { return }

      Promise.all(allPoints.map(point => point.marker)).then((markers) => {
        markers = markers.filter(point => map.getBounds().contains(point.getPosition()))

        const points = markers.map(marker => {
          return find(this.dataPoints, {
            latitude: marker.getPosition().lat(),
            longitude: marker.getPosition().lng(),
          })
        })

        this.$emit('filtered', points)
      })
    },
    getInitialZoomLevel () {
      return window.innerWidth < 768 ? 3 : 4
    },
    async zoomMap () {
      const bounds = new google.maps.LatLngBounds()

      this.dataPoints.forEach(point => bounds.extend({ lat: point.latitude, lng: point.longitude }))

      this.map.fitBounds(bounds)
    },
    async waitForGoogle() {
      if (typeof google === 'undefined') {
        await new Promise(resolve => setTimeout(resolve, 100))
        return await this.waitForGoogle()
      }
    }
  },
  watch: {
    points: {
      deep: true,
      handler: function (points, _) {
        this.setDataPoints()

        setTimeout(() => {
          this.zoomMap()
          this.emitVisibleMarkers()
        }, 100)
      }
    }
  },
  async mounted () {
    await this.waitForGoogle()

    this.setDataPoints()

    await this.$nextTick(() => {
      this.setListeners()

      setTimeout(() => {
        this.zoomMap()
        this.emitVisibleMarkers()
      }, 100)
    })
  }
}
</script>
