<template>
  <div>
    <div class="flex items-center">
      <div class="font-bold mb-2 text-xl">Payment</div>
      <div class="ml-4 flex items-center gap-1">
        <img src="/img/card-visa.svg" alt="visa" width="40" height="25" />
        <img src="/img/card-mastercard.svg" alt="mastercard" width="40" height="25" />
        <img src="/img/card-amex.svg" alt="amex" width="40" height="25" />
        <img src="/img/card-discover.svg" alt="discover" width="40" height="25" />
        <img src="/img/card-dinersclub.svg" alt="dinersclub" width="40" height="25" />
        <img src="/img/card-jcb.svg" alt="jcb" width="40" height="25" />
      </div>
    </div>

    <div class="w-full flex flex-wrap bg-gray-100 p-6 rounded">
      <div class="w-full py-2">
        <label for="">Card Number</label>
        <div class="flex items-center">
          <div class="pr-1">
            <i class="fa-2x mt-2" :class="cardBrandClass"></i>
          </div>
          <div class="flex-1">
            <TextInput
                type="text"
                class="mt-1 block w-full"
                :class="{'border-red-500': Boolean(cardErrors.cardNumber)}"
                v-model="form.cardNumber"
                required
                autocomplete="off"
                placeholder="Card number"
                v-cardformat:formatCardNumber
                @change="validateCardNumber"
            />
          </div>
        </div>
        <div v-if="cardErrors.cardNumber" class="text-red-500 relative">{{ cardErrors.cardNumber }}</div>
      </div>
      <div class="w-full xl:w-1/2 py-2">
        <label class="uppercase text-gray-400">Expiration Date</label>
        <div class="flex items-center">
          <div class="flex-1">
            <label for="">Month</label>
            <TextInput
                type="number"
                class="mt-1 block w-full"
                :class="{'border-red-500': Boolean(cardErrors.cardExpiry)}"
                v-model="form.month"
                required
                autocomplete="off"
                placeholder="MM"
                :mask="{ mask: '##' }"
            />
          </div>
          <div class="px-3 pt-6 text-xl">
            /
          </div>
          <div class="flex-1">
            <label for="">Year</label>
            <TextInput
                type="number"
                class="mt-1 block w-full"
                :class="{'border-red-500': Boolean(cardErrors.cardExpiry)}"
                v-model="form.year"
                required
                autocomplete="off"
                placeholder="YY"
                :mask="{ mask: '##' }"
            />
          </div>
        </div>
        <div v-if="cardErrors.cardExpiry" class="text-red-500 relative">{{ cardErrors.cardExpiry }}</div>
      </div>
      <div class="w-full xl:w-1/2 py-2 xl:pl-4 xl:pt-8">
        <label for="">Security Code</label>
        <TextInput
            ref="cardCvcInput"
            type="number"
            class="mt-1 block w-full"
            :class="{'border-red-500': Boolean(cardErrors.cardCode)}"
            v-model="form.cardCode"
            required
            autocomplete="off"
            placeholder="XXX"
            v-cardformat:formatCardCVC
            @change="validateCardCode"
        />
        <div v-if="cardErrors.cardCode" class="text-red-500 relative">{{ cardErrors.cardCode }}</div>
      </div>
    </div>
  </div>
</template>

<script>
import CtaButton from '@/Components/CtaButton.vue'
import TextInput from '@/Components/Input.vue'
import { getBrandClass } from '@/utils'

export default {
  name: 'PaymentForm',
  components: {
    CtaButton,
    TextInput,
  },
  props: {
    form: {
      type: Object,
      default: () => ({
        cardNumber: '',
        month: '',
        year: '',
        cardCode: '',
      }),
    },
  },
  data () {
    return {
      cardBrand: null,
      cardErrors: {},
      submitting: false,
    }
  },
  computed: {
    cardBrandClass () {
      return this.getBrandClass(this.cardBrand)
    },
  },
  methods: {
    getBrandClass,
    validate () {
      this.cardErrors = {}
      this.validateCardNumber()
      this.validateExpiry()
      this.validateCardCode()

      return this.cardErrors
    },
    validateCardNumber () {
      this.cardErrors.cardNumber = null

      if (!this.$cardFormat.validateCardNumber(this.form.cardNumber)) {
        this.cardErrors.cardNumber = 'Invalid Credit Card Number.'
      } else {
        delete this.cardErrors.cardNumber
      }
    },
    validateExpiry () {
      this.cardErrors.cardExpiry = null

      if (!this.$cardFormat.validateCardExpiry(this.form.month, this.form.year)) {
        this.cardErrors.cardExpiry = 'Invalid Expiration Date.'
      } else {
        delete this.cardErrors.cardExpiry
      }
    },
    validateCardCode () {
      this.cardErrors.cardCode = null

      if (!this.$cardFormat.validateCardCVC(this.form.cardCode, this.cardBrand)) {
        this.cardErrors.cardCode = 'Invalid CVC.'
      } else {
        delete this.cardErrors.cardCode
      }
    },
  },
  watch: {
    'form.cardNumber': {
      handler: function () {
        this.validateCardNumber()
      },
    },
    'form.year': {
      handler: function () {
        this.validateExpiry()
      },
    },
    'form.cardCode': {
      handler: function () {
        this.validateCardCode()
      },
    },
  },
}
</script>