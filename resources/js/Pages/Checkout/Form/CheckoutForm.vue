<template>
  <div class="flex flex-col xl:flex-row xl:flex-row-reverse xl:items-baseline">
    <div class="xl:w-5/12 px-8 xl:pb-4 xl:bg-gray-50">
      <order-summary :property="property"
                     :checkout="checkout"
                     :first_renewal_date="first_renewal_date"
                     @discountApplied="discountApplied" />
    </div>
    <div class="xl:w-7/12 px-8 bg-white">
      <div class="relative">

        <customer-form ref="customer" :form="form.customer"/>

        <payment-form ref="payment" :form="form.payment" class="mt-4"/>

        <div class="w-full">
          <div v-if="paymentErrors.length" class="text-red-500 my-3">
            <div class="font-bold"><i class="fa fa-exclamation-triangle"></i> Error submitting payment</div>
            <div v-for="error in paymentErrors" :key="error.code" class="pt-1 pl-3">
              {{ error.text }}
            </div>
          </div>

          <div v-if="errors">
            <div class="font-bold"><i class="fa fa-exclamation-triangle"></i> Error submitting order</div>
            <div class="pt-1 pl-3">{{ errors }}</div>
          </div>
        </div>

        <cta-button
            @click="submitPayment"
            class="mt-4 w-1/2 ml-auto"
            :disabled="submitting"
        >
          Pay Now
        </cta-button>

        <div v-if="submitting"
             class="absolute bg-gray-200 top-0 left-0 right-0 bottom-0 z-50 bg-opacity-50 flex items-center justify-center">
          <i class="fa fa-spinner fa-spin fa-3x opacity-50"></i>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import CustomerForm from '@/Pages/Checkout/Form/CustomerForm.vue'
import PaymentForm from '@/Pages/Checkout/Form/PaymentForm.vue'
import AuthorizeNet from '@/Pages/Checkout/Form/AuthorizeNet'
import CtaButton from '@/Components/CtaButton.vue'
import OrderSummary from '@/Pages/Checkout/OrderSummary.vue'

export default {
  name: 'CheckoutForm',
  components: {
    CtaButton,
    CustomerForm,
    OrderSummary,
    PaymentForm,
  },
  props: {
    property: {
      type: Object,
      required: true,
    },
    checkout: {
      type: Object,
      required: true,
    },
    first_renewal_date: {
      type: String,
      required: true,
    },
  },
  data () {
    return {
      submitting: false,
      paymentErrors: [],
      errors: null,
      form: {
        customer: {
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
        },
        payment: {
          cardNumber: '', // required
          month: '', // required
          year: '', // required
          cardCode: '',
          zip: '',
        },
      },
      authorizeNet: null,
      appliedDiscount: null,
    }
  },
  methods: {
    async submitPayment () {
      const errors = {
        ...this.$refs.customer.validate(),
        ...this.$refs.payment.validate(),
      }

      if (Object.keys(errors).length > 0) {
        return
      }

      const payload = {
        cardData: {
          ...this.form.payment,
          zip: this.form.customer.postal_code,
          cardNumber: this.form.payment.cardNumber.split(' ').join('')
        },
      }
      console.log('No errors found, submitting...', payload)

      this.submitting = true
      this.authorizeNet.submitPayment(payload).then((response) => {
        this.handleSuccessfulPayment(response)
      }).catch((response) => {
        this.paymentErrors = response.messages.message
        this.submitting = false
      })
    },
    async handleSuccessfulPayment (response) {
      this.errors = null
      let payload = {
        customer: this.form.customer,
        payments: this.checkout.paymentCount,
        dataDescriptor: response.opaqueData.dataDescriptor,
        dataValue: response.opaqueData.dataValue,
      }

      // include discount if present
      if (this.appliedDiscount) {
        payload = { ...payload, ...this.appliedDiscount }
      }

      try {
        console.log('nonce returned', payload)
        const response = await axios.post(route('transactions.submit', { apn: this.property.apn }), payload)

        this.$inertia.visit(`/confirmation/${response.data.transaction_id}`)
      } catch (e) {
        this.errors = e.response.data.message
        console.log('error submitting transaction', e.response.data)
      } finally {
        this.submitting = false
      }
    },
    discountApplied (data) {
      this.appliedDiscount = data
    }
  },
  mounted () {
    this.$loadScript(import.meta.env.VITE_AUTHORIZE_NET_JS_URL)

    this.authorizeNet = new AuthorizeNet(
        import.meta.env.VITE_AUTHORIZE_NET_CLIENT_KEY,
        import.meta.env.VITE_AUTHORIZE_NET_API_LOGIN_ID,
    )

    this.$el.querySelector('#first_name').focus()
  },
}
</script>