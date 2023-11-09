<template>
  <div class="select-field">
    <label :for="id">
      {{ label }}
      <span
          v-if="required"
          class="ml-1 font-bold text-red-500"
      >*</span>
    </label>
    <div class="relative">
      <select
          v-model="data"
          :id="id"
          class="z-20 appearance-none block w-full border border-gray-300 pb-2 pt-3 px-4 leading-tight focus:outline-none bg-white focus:border-gray-500 rounded"
          :class="fieldClass"
          @change="onChange"
          @blur="$emit('blur')"
          :disabled="disabled"
      >
        <option v-for="(option, index) in options" :disabled="option.disabled" :value="option.value"
                :key="`option-${index}`">{{ option.label }}
        </option>
      </select>
      <div
          class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-dark-blue">
        <svg class="fill-current h-4 w-4 text-transparent" xmlns="http://www.w3.org/2000/svg"
             viewBox="11 0 11 20">
          <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
        </svg>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'SelectField',
  props: {
    modelValue: {
      required: true,
    },
    label: {
      type: String,
      default: '',
    },
    id: {
      type: String,
      default: '',
    },
    required: {
      type: Boolean,
      default: false,
    },
    options: {
      type: Array,
      required: true,
    },
    disabled: {
      type: Boolean,
      default: false,
    },
    align: {
      type: String,
      default: '',
    },
    placeholder: {
      type: String,
      required: false,
    },
  },
  data () {
    return {
      data: null,
    }
  },
  watch: {
    modelValue (newValue, oldValue) {
      this.data = newValue
    },
    data (newValue, oldValue) {
      this.$emit('update:modelValue', newValue)
    },
  },
  methods: {
    onChange (e) {
      this.$emit('change', e.target.value)
    },
  },
  computed: {
    fieldClass () {
      return {
        'text-gray-700': !this.disabled,
        'text-gray-500': this.disabled,
        'text-left': this.align === 'left',
        'text-right': this.align === 'right',
        'text-center': this.align === 'center',
      }
    },
  },
  mounted () {
    this.data = this.modelValue
  },
}
</script>
