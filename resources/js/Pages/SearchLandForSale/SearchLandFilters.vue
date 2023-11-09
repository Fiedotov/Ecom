<!--suppress CommaExpressionJS -->
<template>
  <div class="flex flex-col lg:flex-row items-center text-dark-blue">
    <div class="flex-1 px-4 lg:px-0 lg:pr-16 pb-2 lg:pb-4 w-full">
      <div class="font-bold font-serif text-xl mb-4">State</div>
      <Multiselect
          placeholder="Select"
          v-model="filters.state"
          :options="stateOptions"
          :classes="selectClasses"
          mode="tags"
          :caret="true"
          :show-options="true"
      />
    </div>
    <div class="flex-1 px-4 lg:px-0 lg:pr-16 pb-2 lg:pb-4 w-full">
      <div class="font-bold font-serif text-xl mb-4">County</div>
      <Multiselect
          placeholder="Select"
          v-model="filters.county"
          :options="countyOptions"
          :classes="selectClasses"
          mode="tags"
      />
    </div>
    <div class="flex-1 px-4 lg:px-0 lg:pr-16 pb-2 lg:pb-4 w-full">
      <div class="font-bold font-serif text-xl mb-4">Monthly Payment</div>
      <div class="py-2">
        <range-filter
            v-model="filters.monthly_payment"
            :min="filter_settings.min_monthly_payment"
            :max="filter_settings.max_monthly_payment"
            :display="`$${Number(filters.monthly_payment[0]).toLocaleString()}/mo - $${Number(filters.monthly_payment[1]).toLocaleString()}/mo`"
        />
      </div>
    </div>
    <div class="flex-1 px-4 lg:px-0 lg:pr-16 pb-2 lg:pb-4 w-full">
      <div class="font-bold font-serif text-xl mb-4">Price</div>
      <div class="py-2">
        <range-filter
            v-model="filters.price"
            :min="filter_settings.min_price"
            :max="filter_settings.max_price"
            :display="`$${Number(filters.price[0]).toLocaleString()} - $${Number(filters.price[1]).toLocaleString()}`"
        />
      </div>
    </div>
    <div class="flex-1 px-4 lg:px-0 lg:pr-16 pb-2 lg:pb-4 w-full">
      <div class="font-bold font-serif text-xl mb-4">Acreage</div>
      <div class="py-2">
        <range-filter
            v-model="filters.acreage"
            :min="filter_settings.min_acreage"
            :max="filter_settings.max_acreage"
            :display="`${filters.acreage[0]} - ${filters.acreage[1]} acres`"
        />
      </div>
    </div>
  </div>
</template>

<script>
import { clone, find, flatMap } from 'lodash'
import Multiselect from '@vueform/multiselect'
import RangeFilter from '@/Components/RangeFilter.vue'
import SelectField from '@/Components/SelectField.vue'
import Slider from '@vueform/slider'

export default {
  name: 'SearchLandFilters',
  props: {
    states: {
      type: Array,
      required: true,
    },
    filter_settings: {
      type: Object,
      required: true,
    },
  },
  components: {
    Multiselect,
    RangeFilter,
    SelectField,
    Slider,
  },
  data () {
    return {
      filters: {
        state: [],
        county: [],
        monthly_payment: [this.filter_settings.min_monthly_payment, this.filter_settings.max_monthly_payment],
        price: [this.filter_settings.min_price, this.filter_settings.max_price],
        acreage: [this.filter_settings.min_acreage, this.filter_settings.max_acreage],
      },
      selectClasses: {
        container: 'relative mx-auto w-full flex items-center justify-end box-border cursor-pointer border border-gray-300 rounded bg-white text-base leading-snug outline-none',
        containerDisabled: 'cursor-default bg-gray-100',
        containerOpen: 'rounded-b-none',
        containerOpenTop: 'rounded-t-none',
        containerActive: 'ring ring-red-500 ring-opacity-30',
        singleLabel: 'flex items-center h-full max-w-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 pr-16 box-border rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5',
        singleLabelText: 'overflow-ellipsis overflow-hidden block whitespace-nowrap max-w-full',
        multipleLabel: 'flex items-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5',
        search: 'w-full absolute inset-0 outline-none focus:ring-0 appearance-none box-border border-0 text-base font-sans bg-white rounded pl-3.5 rtl:pl-0 rtl:pr-3.5',
        tags: 'flex-grow flex-shrink flex flex-wrap items-center mt-1 pl-2 rtl:pl-0 rtl:pr-2',
        tag: 'bg-red-500 text-white text-sm font-semibold py-0.5 pl-2 rounded mr-1 mb-1 flex items-center whitespace-nowrap rtl:pl-0 rtl:pr-2 rtl:mr-0 rtl:ml-1',
        tagDisabled: 'pr-2 opacity-50 rtl:pl-2',
        tagRemove: 'flex items-center justify-center p-1 mx-0.5 rounded-sm hover:bg-black hover:bg-opacity-10 group',
        tagRemoveIcon: 'bg-multiselect-remove bg-center bg-no-repeat opacity-30 inline-block w-3 h-3 group-hover:opacity-60',
        tagsSearchWrapper: 'inline-block relative mx-1 mb-1 flex-grow flex-shrink h-full',
        tagsSearch: 'absolute inset-0 border-0 outline-none focus:ring-0 appearance-none p-0 text-base font-sans box-border w-full',
        tagsSearchCopy: 'invisible whitespace-pre-wrap inline-block h-px',
        placeholder: 'flex items-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 text-gray-400 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5',
        caret: 'bg-multiselect-caret bg-center bg-no-repeat w-2.5 h-4 py-px box-content mr-3.5 relative z-10 flex-shrink-0 flex-grow-0 transition-transform transform pointer-events-none rtl:mr-0 rtl:ml-3.5',
        caretOpen: 'rotate-180 pointer-events-auto',
        clear: 'pr-3.5 relative z-10 opacity-40 transition duration-300 flex-shrink-0 flex-grow-0 flex hover:opacity-80 rtl:pr-0 rtl:pl-3.5',
        clearIcon: 'bg-multiselect-remove bg-center bg-no-repeat w-2.5 h-4 py-px box-content inline-block',
        spinner: 'bg-multiselect-spinner bg-center bg-no-repeat w-4 h-4 z-10 mr-3.5 animate-spin flex-shrink-0 flex-grow-0 rtl:mr-0 rtl:ml-3.5',
        inifite: 'flex items-center justify-center w-full',
        inifiteSpinner: 'bg-multiselect-spinner bg-center bg-no-repeat w-4 h-4 z-10 animate-spin flex-shrink-0 flex-grow-0 m-3.5',
        dropdown: 'max-h-60 absolute -left-px -right-px bottom-0 transform translate-y-full border border-gray-300 -mt-px overflow-y-scroll z-50 bg-white flex flex-col rounded-b',
        dropdownTop: '-translate-y-full top-px bottom-auto rounded-b-none rounded-t',
        dropdownHidden: 'hidden',
        options: 'flex flex-col p-0 m-0 list-none',
        optionsTop: '',
        group: 'p-0 m-0',
        groupLabel: 'flex text-sm box-border items-center justify-start text-left py-1 px-3 font-semibold bg-gray-200 cursor-default leading-normal',
        groupLabelPointable: 'cursor-pointer',
        groupLabelPointed: 'bg-gray-300 text-gray-700',
        groupLabelSelected: 'bg-red-600 text-white',
        groupLabelDisabled: 'bg-gray-100 text-gray-300 cursor-not-allowed',
        groupLabelSelectedPointed: 'bg-red-600 text-white opacity-90',
        groupLabelSelectedDisabled: 'text-red-100 bg-red-600 bg-opacity-50 cursor-not-allowed',
        groupOptions: 'p-0 m-0',
        option: 'flex items-center justify-start box-border text-left cursor-pointer text-base leading-snug py-2 px-3',
        optionPointed: 'text-gray-800 bg-gray-100',
        optionSelected: 'text-white bg-red-500',
        optionDisabled: 'text-gray-300 cursor-not-allowed',
        optionSelectedPointed: 'text-white bg-red-500 opacity-90',
        optionSelectedDisabled: 'text-red-100 bg-red-500 bg-opacity-50 cursor-not-allowed',
        noOptions: 'py-2 px-3 text-gray-600 bg-white text-left',
        noResults: 'py-2 px-3 text-gray-600 bg-white text-left',
        fakeInput: 'bg-transparent absolute left-0 right-0 -bottom-px w-full h-px border-0 p-0 appearance-none outline-none text-transparent',
        spacer: 'h-9 py-px box-content',
      },
    }
  },
  computed: {
    stateOptions () {
      const states = this.states.map(state => ({ label: `${state.name} (${state.property_count})`, value: state.name }))
      states.unshift({ label: 'All States', value: null })

      return states
    },
    countyOptions () {
      if (!this.filters.state || this.filters.state.length === 0) {
        return [{ label: 'All Counties', value: null }]
      }

      const counties = flatMap(
        this.filters.state.filter(state => Boolean(state)),
        (stateFilter) => find(this.states, { name: stateFilter }).counties.map(
          county => ({ label: `${county.name} (${county.property_count})`, value: county.name }),
        ),
      )

      counties.unshift({ label: 'All Counties', value: null })

      return counties
    },
  },
  watch: {
    filters: {
      deep: true,
      handler: function (newValue, oldValue) {
        const search = clone(newValue)
        search.county = search.county.filter(value => Boolean(value))
        search.state = search.state.filter(value => Boolean(value))
        this.updateUrl(search)
        this.$emit('filtered', search)
      },
    },
  },
  methods: {
    initializeFilters () {
      const params = new URLSearchParams(window.location.search)
      if (params.has('states')) { this.filters.state = params.get('states').split(',') }
      if (params.has('counties')) { this.filters.county = params.get('counties').split(',') }

      const ranges = ['acreage', 'price', 'monthly_payment']
      ranges.forEach(filter => {
        if (params.has(`${filter}.min`)) { this.filters[filter][0] = params.get(`${filter}.min`) }
        if (params.has(`${filter}.max`)) { this.filters[filter][1] = params.get(`${filter}.max`) }
      })
    },
    updateUrl (search) {
      const searchURL = new URL(window.location)
      searchURL.searchParams.set('states', search.state.join(','))
      searchURL.searchParams.set('counties', search.county.join(','))
      const filters = ['acreage', 'price', 'monthly_payment']
      filters.forEach(filter => {
        searchURL.searchParams.set(`${filter}.min`, search[filter][0])
        searchURL.searchParams.set(`${filter}.max`, search[filter][1])
      })
      window.history.pushState({}, '', searchURL)
    },
  },
  mounted () {
    this.initializeFilters()
  },
}
</script>

<style>
.bg-multiselect-remove {
    -webkit-mask-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 320 512' fill='currentColor' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M207.6 256l107.72-107.72c6.23-6.23 6.23-16.34 0-22.58l-25.03-25.03c-6.23-6.23-16.34-6.23-22.58 0L160 208.4 52.28 100.68c-6.23-6.23-16.34-6.23-22.58 0L4.68 125.7c-6.23 6.23-6.23 16.34 0 22.58L112.4 256 4.68 363.72c-6.23 6.23-6.23 16.34 0 22.58l25.03 25.03c6.23 6.23 16.34 6.23 22.58 0L160 303.6l107.72 107.72c6.23 6.23 16.34 6.23 22.58 0l25.03-25.03c6.23-6.23 6.23-16.34 0-22.58L207.6 256z'%3E%3C/path%3E%3C/svg%3E");
    mask-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 320 512' fill='currentColor' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M207.6 256l107.72-107.72c6.23-6.23 6.23-16.34 0-22.58l-25.03-25.03c-6.23-6.23-16.34-6.23-22.58 0L160 208.4 52.28 100.68c-6.23-6.23-16.34-6.23-22.58 0L4.68 125.7c-6.23 6.23-6.23 16.34 0 22.58L112.4 256 4.68 363.72c-6.23 6.23-6.23 16.34 0 22.58l25.03 25.03c6.23 6.23 16.34 6.23 22.58 0L160 303.6l107.72 107.72c6.23 6.23 16.34 6.23 22.58 0l25.03-25.03c6.23-6.23 6.23-16.34 0-22.58L207.6 256z'%3E%3C/path%3E%3C/svg%3E");
    -webkit-mask-position: center;
    mask-position: center;
    -webkit-mask-repeat: no-repeat;
    mask-repeat: no-repeat;
    -webkit-mask-size: contain;
    mask-size: contain;
    background-color: currentColor;
    opacity: 0.8;
    display: inline-block;
    width: 0.75rem;
    height: 0.75rem;
}

.bg-multiselect-caret {
    width: 0;
    height: 0;
    border-left: 7px solid transparent;
    border-right: 7px solid transparent;
    border-top: 7px solid rgba(239, 68, 68, 1);
}

.bg-multiselect-caret::before {
    content: '';
    width: 0;
    height: 0;
    border-left: 7px solid transparent;
    border-right: 7px solid transparent;
    border-top: 7px solid white;
    position: relative;
    top: -4px;
    left: -7px;
}
</style>
