<template>
  <div class="border-b border-gray-300 pb-2">
    <template v-for="item in items" :key="`item-${item.key}`">
      <div class="pb-2 flex items-center" v-if="Boolean(property[item.key])">
        <div class="text-sm uppercase leading-none w-40">{{ item.label }}</div>
        <div class="text-discount-blue font-bold leading-none">{{ property[item.key] }}</div>
      </div>
    </template>
  </div>
</template>

<script>
export default {
  name: 'PropertyInfo',
  props: {
    property: { type: Object, required: true },
  },
  data () {
    return {
      items: [
        { label: 'City', key: 'city' },
        { label: 'State', key: 'state' },
        { label: 'County', key: 'county' },
        { label: 'Zip Code', key: 'zip_code' },
        { label: 'APN', key: 'apn' },
      ]
    }
  }
}
</script>