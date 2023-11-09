<template>
  <div class="property-details bg-gray-100 text-dark-blue">
    <div class="mx-auto lg:max-w-[60vw] flex flex-col lg:flex-row py-12">
      <div class="lg:w-2/3">
        <div class="text-center lg:text-left font-bold font-serif text-4xl">Property Details</div>
        <div class="grid gap-4 grid-cols-2 lg:grid-cols-3 mt-12 mb-4">
          <div class="text-center p-2">
            <i class="fa fa-map-pin text-money-green fa-2x"></i>
            <div class="font-bold my-2">Parcel Number</div>
            <div class="text-sm text-gray-500">{{ property.apn }}</div>
          </div>

          <div class="text-center p-2">
            <i class="fa fa-landmark text-money-green fa-2x"></i>
            <div class="font-bold my-2">County</div>
            <div class="text-gray-500">{{ property.county }}</div>
          </div>

          <div class="text-center p-2">
            <i class="fa fa-flag text-money-green fa-2x"></i>
            <div class="font-bold my-2">State</div>
            <div class="text-gray-500">{{ property.state }}</div>
          </div>

          <div class="text-center p-2">
            <i class="fa fa-square text-money-green fa-2x"></i>
            <div class="font-bold my-2">Parcel Size</div>
            <div class="text-gray-500">{{ Number(property.acreage).toFixed(2) }} acres</div>
          </div>

          <div class="text-center p-2">
            <i class="fa fa-city text-money-green fa-2x"></i>
            <div class="font-bold my-2">City</div>
            <div class="text-gray-500">{{ property.city }}</div>
          </div>

          <div class="text-center p-2">
            <i class="fa fa-envelope text-money-green fa-2x"></i>
            <div class="font-bold my-2">Zip Code</div>
            <div class="text-gray-500">{{ property.zip_code }}</div>
          </div>

          <div class="text-center p-2">
            <i class="fa fa-map-marker text-money-green fa-2x"></i>
            <div class="font-bold my-2">GPS Coordinates</div>
            <div class="text-gray-500">{{ property.latitude }}, {{ property.longitude }}</div>
          </div>

          <div class="text-center p-2">
            <i class="fa fa-ruler-vertical text-money-green fa-2x"></i>
            <div class="font-bold my-2">Elevation</div>
            <div class="text-gray-500">{{ property.elevation }}</div>
          </div>

          <div class="text-center p-2">
            <i class="fa fa-road text-money-green fa-2x"></i>
            <div class="font-bold my-2">Property Access</div>
            <div class="text-gray-500">{{ property.road_access ? 'Yes' : 'No' }}</div>
          </div>

          <div class="text-center p-2">
            <i class="fa fa-map text-money-green fa-2x"></i>
            <div class="font-bold my-2">Zoning</div>
            <div class="text-gray-500">{{ property.zoning }}</div>
          </div>

          <div class="text-center p-2">
            <i class="fa fa-mountain text-money-green fa-2x"></i>
            <div class="font-bold my-2">Terrain</div>
            <div class="text-gray-500">{{ property.terrain }}</div>
          </div>

          <div class="text-center p-2">
            <i class="fa fa-clock text-money-green fa-2x"></i>
            <div class="font-bold my-2">Time Limit</div>
            <div class="text-gray-500">{{ property.time_limit }}</div>
          </div>

          <div class="text-center p-2">
            <i class="fa fa-file-invoice-dollar text-money-green fa-2x"></i>
            <div class="font-bold my-2">Tax Amount</div>
            <div class="text-gray-500">{{ Number(property.annual_property_taxes).toFixed(2) }}</div>
          </div>

          <div class="text-center p-2">
            <i class="fa fa-house text-money-green fa-2x"></i>
            <div class="font-bold my-2">HOA</div>
            <div class="text-gray-500">{{ Number(property.hoa_poa_annual_fee).toFixed(2) }}</div>
          </div>

          <div class="text-center p-2">
            <i class="fa fa-water text-money-green fa-2x"></i>
            <div class="font-bold my-2">Water</div>
            <div class="text-gray-500">{{ property.water_connection }}</div>
          </div>

          <div class="text-center p-2">
            <i class="fa fa-toilet text-money-green fa-2x"></i>
            <div class="font-bold my-2">Sewer</div>
            <div class="text-gray-500">{{ property.sewer_connection }}</div>
          </div>

          <div class="text-center p-2">
            <i class="fa fa-plug text-money-green fa-2x"></i>
            <div class="font-bold my-2">Power</div>
            <div class="text-gray-500">{{ property.power_connection }}</div>
          </div>

          <div class="text-center p-2">
            <i class="fa fa-file text-money-green fa-2x"></i>
            <div class="font-bold my-2">Doc Fee</div>
            <div class="text-gray-500">{{ Number(property.document_fee).toFixed(2) }}</div>
          </div>
        </div>
      </div>
      <div class="lg:w-1/3">
        <property-inquiry-form :property="property"/>
      </div>
    </div>
  </div>
</template>

<script>
import PropertyInquiryForm from '@/Pages/ShowProperty/PropertyInquiryForm.vue'

export default {
  name: 'PropertyDetails',
  components: {
    PropertyInquiryForm,
  },
  props: {
    property: {
      type: Object,
      required: true,
    },
  },
}
</script>
