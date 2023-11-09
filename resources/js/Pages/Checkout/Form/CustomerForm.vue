<template>
  <div>
    <div class="text-lg font-bold mb-2 text-xl">Customer Information</div>

    <div class="w-full flex flex-wrap text-gray-500">
      <div class="w-full xl:w-1/2 py-2 xl:pr-2">
        <label for="first_name">First Name</label>
        <div class="flex items-center">
          <div class="flex-1">
            <TextInput
                id="first_name"
                type="text"
                class="mt-1 block w-full"
                :class="{'border-red-500': Boolean(errors.first_name)}"
                v-model="form.first_name"
                required
                autofocus
                autocomplete="off"
                placeholder="Joe"
                @change="validateFirstName"
            />
          </div>
        </div>
        <div v-if="errors.first_name" class="text-red-500">{{ errors.first_name }}</div>
      </div>
      <div class="w-full xl:w-1/2 py-2 xl:pl-2">
        <label for="last_name">Last Name</label>
        <div class="flex items-center">
          <div class="flex-1">
            <TextInput
                id="last_name"
                type="text"
                class="mt-1 block w-full"
                v-model="form.last_name"
                :class="{'border-red-500': Boolean(errors.last_name)}"
                required
                autofocus
                autocomplete="off"
                placeholder="Smith"
                @change="validateLastName"
            />
          </div>
        </div>
        <div v-if="errors.last_name" class="text-red-500">{{ errors.last_name }}</div>
      </div>
      <div class="w-full py-2">
        <label for="">Address</label>
        <div class="flex items-center">
          <div class="flex-1">
            <TextInput
                type="text"
                class="mt-1 block w-full"
                v-model="form.address"
                :class="{'border-red-500': Boolean(errors.address)}"
                required
                autofocus
                autocomplete="off"
                placeholder="123 Any Street"
                @change="validateAddress"
            />
          </div>
        </div>
        <div v-if="errors.address" class="text-red-500">{{ errors.address }}</div>
      </div>
      <div class="w-full py-2">
        <label for="">Apartment, Suite, etc.</label>
        <div class="flex items-center">
          <div class="flex-1">
            <TextInput
                type="text"
                class="mt-1 block w-full"
                v-model="form.address2"
                required
                autofocus
                autocomplete="off"
                placeholder="Suite 6"
            />
          </div>
        </div>
      </div>
      <div class="w-full xl:w-1/3 py-2 xl:pr-2">
        <label for="">City</label>
        <div class="flex items-center">
          <div class="flex-1">
            <TextInput
                type="text"
                class="mt-1 block w-full"
                v-model="form.city"
                :class="{'border-red-500': Boolean(errors.city)}"
                required
                autofocus
                autocomplete="off"
                placeholder="New York"
                @change="validateCity"
            />
          </div>
        </div>
        <div v-if="errors.city" class="text-red-500">{{ errors.city }}</div>
      </div>
      <div class="w-full xl:w-1/3 py-2 xl:px-2">
        <label for="">State</label>
        <div class="flex items-center">
          <div class="flex-1">
            <select-field
                v-model="form.state"
                :class="{'border border-red-500': Boolean(errors.state)}"
                :options="states"
                @change="validateState"
                class="mt-1"
            />
          </div>
        </div>
        <div v-if="errors.state" class="text-red-500">{{ errors.state }}</div>
      </div>
      <div class="w-full xl:w-1/3 py-2 xl:pl-2">
        <label for="">ZIP Code</label>
        <div class="flex items-center">
          <div class="flex-1">
            <TextInput
                type="text"
                class="mt-1 block w-full"
                v-model="form.postal_code"
                :class="{'border-red-500': Boolean(errors.postal_code)}"
                required
                autofocus
                autocomplete="off"
                placeholder="90210"
                maxlength="5"
                :mask="{ mask: '#####' }"
                @change="validatePostalCode"
            />
          </div>
        </div>
        <div v-if="errors.postal_code" class="text-red-500">{{ errors.postal_code }}</div>
      </div>
      <div class="w-full py-2">
        <label for="">Full Legal Name</label>
        <div class="flex items-center">
          <div class="flex-1">
            <TextInput
                type="text"
                class="mt-1 block w-full"
                v-model="form.full_legal_name"
                :class="{'border-red-500': Boolean(errors.full_legal_name)}"
                required
                autofocus
                autocomplete="off"
                placeholder="Joseph William Smith"
                @change="validateFullLegalName"
            />
          </div>
        </div>
        <div v-if="errors.full_legal_name" class="text-red-500">{{ errors.full_legal_name }}</div>
      </div>
      <div class="w-full xl:w-1/2 py-2 xl:pr-2">
        <label for="">Phone</label>
        <div class="flex items-center">
          <div class="flex-1">
            <TextInput
                type="text"
                class="mt-1 block w-full"
                v-model="form.phone"
                :class="{'border-red-500': Boolean(errors.phone)}"
                required
                autofocus
                autocomplete="off"
                placeholder="456-555-1234"
                @change="validatePhone"
                :mask="{ mask: '###-###-####' }"
            />
          </div>
        </div>
        <div v-if="errors.phone" class="text-red-500">{{ errors.phone }}</div>
      </div>
      <div class="w-full xl:w-1/2 py-2 xl:pl-2">
        <label for="">Email</label>
        <div class="flex items-center">
          <div class="flex-1">
            <TextInput
                type="email"
                class="mt-1 block w-full"
                v-model="form.email"
                :class="{'border-red-500': Boolean(errors.email)}"
                required
                autofocus
                autocomplete="off"
                placeholder="user@example.com"
                @change="validateEmail"
            />
          </div>
        </div>
        <div v-if="errors.email" class="text-red-500">{{ errors.email }}</div>
      </div>
      <div class="w-full xl:w-1/2 py-2">
        <label for="">Referrer (optional)</label>
        <div class="flex items-center">
          <div class="flex-1">
            <TextInput
                type="text"
                class="mt-1 block w-full"
                v-model="form.referrer_name"
                required
                autofocus
                autocomplete="off"
                placeholder="Jane Doe"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import TextInput from '@/Components/Input.vue'
import SelectField from '@/Components/SelectField.vue'
import states from '@/Pages/Checkout/Form/states.js'
import { isValidEmail } from '@/utils'

export default {
  name: 'CustomerForm',
  components: {
    SelectField,
    TextInput,
  },
  props: {
    form: {
      type: Object,
      default: () => ({
        first_name: '',
        last_name: '',
        address: '',
        address2: '',
        city: '',
        state: '',
        postal_code: '',
        country: 'US',
        phone: '',
        email: '',
        full_legal_name: '',
        referrer_name: '',
      })
    }
  },
  data () {
    return {
      states: [{label: 'Select', value: ''}, ...states],
      countries: [
        { label: 'USA', value: 'US' },
      ],
      sizes: [
        {label: 'Select', value: ''},
        { label: 'S', value: 'S' },
        { label: 'M', value: 'M' },
        { label: 'L', value: 'L' },
        { label: 'XL', value: 'XL' },
        { label: '2XL', value: '2XL' },
      ],
      errors: {},
    }
  },
  methods: {
    validate() {
      this.errors = {}
      this.validateFirstName()
      this.validateLastName()
      this.validateAddress()
      this.validateCity()
      this.validateState()
      this.validateFullLegalName()
      this.validatePhone()
      this.validateEmail()
      this.validatePostalCode()

      return this.errors
    },
    validateField(key, message) {
      this.errors[key] = null

      if (this.form[key].length === 0) {
        this.errors[key] = message
      } else {
        delete this.errors[key]
      }
    },
    validateFirstName () {
      this.validateField('first_name', 'First Name is required')
    },
    validateLastName () {
      this.validateField('last_name', 'Last Name is required')
    },
    validateAddress () {
      this.validateField('address', 'Address is required')
    },
    validateCity() {
      this.validateField('city', 'City is required')
    },
    validateState() {
      this.validateField('state', 'State is required')
    },
    validateFullLegalName() {
      this.validateField('full_legal_name', 'Full Legal Name is required')
    },
    validatePostalCode() {
      this.errors.postal_code = null
      if (this.form.postal_code.length < 5) {
        this.errors.postal_code = 'ZIP Code is required'
      } else {
        delete this.errors.postal_code
      }
    },
    validatePhone() {
      this.errors.phone = null
      if (this.form.phone.length < 12) {
        this.errors.phone = 'Phone is required'
      } else {
        delete this.errors.phone
      }
    },
    validateEmail() {
      this.errors.email = null
      if (! isValidEmail(this.form.email)) {
        this.errors.email = 'Valid email address is required'
      } else {
        delete this.errors.email
      }
    },
  },
}
</script>