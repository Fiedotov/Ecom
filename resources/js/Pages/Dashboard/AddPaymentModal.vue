<script>
import AuthorizeNet from '@/Pages/Checkout/Form/AuthorizeNet'
import Button from '@/Components/Button.vue'
import DiscountModal from '@/Components/DiscountModal.vue'
import { defineComponent, defineProps } from 'vue'
import { loadScript } from 'vue-plugin-load-script'
import TextInput from '@/Components/Input.vue'
import SelectField from '@/Components/SelectField.vue'
import { getBrandClass } from '@/utils'
import PaymentForm from '@/Pages/Checkout/Form/PaymentForm.vue'
import AddressForm from '@/Pages/Dashboard/AddressForm.vue'

loadScript(import.meta.env.VITE_AUTHORIZE_NET_JS_URL)
const authorizeNet = new AuthorizeNet(
  import.meta.env.VITE_AUTHORIZE_NET_CLIENT_KEY,
  import.meta.env.VITE_AUTHORIZE_NET_API_LOGIN_ID,
)

export default defineComponent({
  components: { AddressForm, PaymentForm, SelectField, TextInput, DiscountModal, Button },
  data () {
    return {
      form: {
        address: {
          first_name: '',
          last_name: '',
          address: '',
          city: '',
          state: '',
          postal_code: '',
        },
        payment: {
          cardNumber: '',
          month: '',
          year: '',
          cardCode: '',
        },
      },
      submitting: false,
    }
  },
  methods: {
    submitCard () {
      const errors = {
        ...this.$refs.address.validate(),
        ...this.$refs.payment.validate(),
      }

      if (Object.keys(errors).length) {
        return
      }

      const payload = {
        cardData: {
          ...this.form.payment,
          cardNumber: this.form.payment.cardNumber.split(' ').join(''),
          fullName: `${this.form.address.first_name} ${this.form.address.last_name}`,
          zip: this.form.address.postal_code,
        },
      }

      authorizeNet.submitPayment(payload)
        .then((response) => {
          this.handleSuccessfulPayment(response)
        })
    },
    async handleSuccessfulPayment (response) {
      this.errors = null
      let payload = {
        ...this.form.address,
        dataDescriptor: response.opaqueData.dataDescriptor,
        dataValue: response.opaqueData.dataValue,
      }

      try {
        this.submitting = true
        const response = await axios.post(route('dashboard.payment-methods.store'), payload)
        this.$emit('added')
      } catch (e) {
        this.errors = e.response.data.message
        console.log('error submitting card', e.response.data)
      } finally {
        this.submitting = false
      }
    },
  },
  computed: {
    cardBrandClass () {
      return getBrandClass(this.cardBrand)
    },
  },
  props: {
    show: {
      type: Boolean,
      required: true,
    },
  }
})
</script>

<template>
  <discount-modal :show="show" @close="$emit('close')" size="sm">
    <template #header>
      <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
        <i class="fa fa-credit-card text-gray-600 mr-2"></i> Add Payment Method
      </h2>
    </template>

    <template #default>
      <address-form ref="address" :form="form.address" class="mt-4"/>

      <payment-form ref="payment" :form="form.payment" class="mt-4"/>

      <div class="pt-4">
        <Button
            @click="submitCard"
            :disabled="submitting"
        >
          <i class="fa fa-spinner fa-spin mr-2" v-if="submitting"></i> Save
        </Button>
      </div>
    </template>
  </discount-modal>
</template>