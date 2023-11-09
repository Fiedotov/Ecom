<template>
<div class="property-search-results-filter">
    <select-field
        v-model="filter"
        :options="options"
    />
</div>
</template>

<script>
import SelectField from "@/Components/SelectField.vue";

export default {
    name: 'PropertySearchResultsFilter',
    components: {
        SelectField,
    },
    data() {
        return {
            filter: null,
            options: [
                {label: 'Sort By', value: null},
                {label: 'Price: Low to High', value: 'cash_price_current-asc'},
                {label: 'Price: High to Low', value: 'cash_price_current-desc'},
                {label: 'Acreage: Low to High', value: 'acreage-asc'},
                {label: 'Acreage: High to Low', value: 'acreage-desc'},
            ]
        }
    },
    watch: {
        filter(newValue, oldValue) {
            let payload = null

            if (Boolean(newValue)) {
                let parts = newValue.split('-')
                payload = {field: parts[0], direction: parts[1]}
            }

            this.$emit('sorted', payload)
        }
    }
}
</script>
