<template>
  <div class="property-inquiry-form border border-discount-blue border-t-4 rounded-xl py-4 px-4 mx-4 lg:mx-0">
    <div class="relative">
      <div class="font-serif font-bold text-2xl text-center lg:text-left lg:mr-4">Have a Question About This Property?</div>

      <div class="mt-6 text-dark-blue font-thin">Your Name*</div>
      <div class="flex flex-col lg:flex-row items-center gap-2">
        <TextInput
            id="first_name"
            type="text"
            class="mt-1 block w-full border-discount-blue rounded-xl"
            v-model="form.first_name"
            required
            autocomplete="off"
            placeholder="First"
        />

        <TextInput
            id="last_name"
            type="text"
            class="mt-1 block w-full border-discount-blue rounded-xl"
            v-model="form.last_name"
            required
            autocomplete="off"
            placeholder="Last"
        />
      </div>

      <div class="mt-6 text-dark-blue">Email Address*</div>
      <TextInput
          id="email"
          type="text"
          class="mt-1 block w-full border-discount-blue rounded-xl"
          v-model="form.email"
          required
          autocomplete="off"
          placeholder=""
      />

      <div class="mt-6 text-dark-blue">Phone*</div>
      <TextInput
          id="phone"
          type="text"
          class="mt-1 block w-full border-discount-blue rounded-xl"
          v-model="form.phone"
          required
          autocomplete="off"
          placeholder=""
      />

      <div class="mt-6 text-dark-blue">Why do you want to buy land?*</div>
      <div class="grid grid-cols-3 gap-x-2 gap-y-3 mt-2">
        <label v-for="(reason, index) in buyReasons" :key="`reason-${index}`" class="flex items-center text-xs">
          <input
              type="checkbox"
              class="mr-1"
              v-model="form.buy_reasons"
              :value="reason.value"
          /> {{ reason.label }}
        </label>
      </div>

      <div class="mt-6 text-dark-blue">Do you have any questions about this property?*</div>
      <textarea class="resize-none w-full border border-discount-blue rounded-xl" v-model="form.question"
                rows="3"></textarea>

      <div class="mt-6 flex items-center gap-2 text-sm">
        <input type="checkbox" v-model="form.spanish" :value="true"/>
        <div>Te gustaria hablar con alguien en espanol</div>
      </div>

      <div class="mt-6 flex items-center gap-2 text-sm">
        <input type="checkbox" v-model="form.contact_allowed" :value="true"/>
        <div>Yes, you may contact me</div>
      </div>

      <div class="mt-6 flex items-center gap-2 text-sm">
        I understand that by submitting this form, I may be contacted by Discount Lots of related businesses and
        their representatives by phone, SMS, email, or postal mail. Data rates may apply.
      </div>

      <transition>
        <div v-if="error" class="text-red-500 font-bold mt-3 bg-red-50 p-2 rounded">
          <i class="fa fa-exclamation-triangle mr-1"></i> {{ error }}
        </div>
      </transition>

      <cta-button
          class="w-full rounded-xl uppercase mt-4"
          :disabled="! readyToSubmit || submitting"
          @click="submit"
      >
        <i class="fa fa-spinner fa-spin mr-1" v-if="submitting"></i> Get Info
      </cta-button>

      <transition>
        <div v-if="complete"
             class="flex flex-col justify-center items-center font-bold absolute top-0 left-0 right-0 bottom-0 bg-blue-50">
          <img src="/img/discount-lots-logo.png" alt="Discount Lots" class="w-40 mx-auto my-2"/>
          <div class="my-2">
            Thank You For Your Submission!
          </div>
        </div>
      </transition>
    </div>
  </div>
</template>

<script>
import TextInput from '@/Components/Input.vue'
import CtaButton from '@/Components/CtaButton.vue'
import { isValidEmail } from '@/utils'

export default {
  name: 'PropertyInquiryForm',
  components: {
    CtaButton,
    TextInput,
  },
  props: {
    property: {
      type: Object,
      required: true,
    },
  },
  computed: {
    readyToSubmit () {
      return Boolean(this.form.first_name)
          && Boolean(this.form.last_name)
          && isValidEmail(this.form.email)
          && Boolean(this.form.phone)
          && this.form.buy_reasons.length > 0
          && Boolean(this.form.question)
    },
  },
  data () {
    return {
      form: {
        first_name: '',
        last_name: '',
        email: '',
        phone: '',
        buy_reasons: [],
        question: '',
        spanish: false,
        contact_allowed: false,
      },
      buyReasons: [
        { label: 'Investment', value: 'investment' },
        { label: 'RV/Trailer', value: 'rv_trailer' },
        { label: 'Camp', value: 'camp' },
        { label: 'Build Home', value: 'build_home' },
        { label: 'Other', value: 'other' },
      ],
      submitting: false,
      complete: false,
      error: null,
    }
  },
  methods: {
    async submit () {
      this.error = null
      this.submitting = true
      try {
        await axios.post(`/api/property-inquiries/${this.property.id}`, { ...this.form, page: window.location.href })
        this.complete = true
      } catch (e) {
        this.error = e.response.data.message
      } finally {
        this.submitting = false
      }
    },
  },
}
</script>