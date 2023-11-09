<template>
  <div class="lg:flex lg:flex-row items-center gap-10 lg:my-2 lg:pr-10">
    <div class="w-1/2">
      <label for="">Amount</label>
      <money3
          v-model="form.amount"
          class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
          @blur="validate('amount')"
          :class="{'border-red-500': Boolean(errors.amount)}"
          :disabled="true"
      />
      <div class="h-6"><div v-if="errors.amount" class="text-red-500">{{ errors.amount }}</div></div>
    </div>
  </div>

  <div class="mt-8 font-bold leading-none text-lg flex items-center">
    <i class="fa fa-bank mr-2"></i>
    <span class="uppercase">Bank Account</span>
  </div>

  <div class="lg:flex lg:flex-row items-center gap-10 lg:my-2">
    <div class="flex-1">
      <label for="">Account Type</label>
      <select-field
          v-model="form.account_type"
          :class="{'border border-red-500': Boolean(errors.account_type)}"
          :options="accountTypes"
          @blur="validate('account_type')"
          class="mt-1"
      />
      <div class="h-6"><div v-if="errors.account_type" class="text-red-500">{{ errors.account_type }}</div></div>
    </div>

    <div class="flex-1">
      <label for="">Routing Number</label>
      <TextInput
          type="number"
          maxlength="9"
          v-model="form.routing_number"
          class="w-full"
          :class="{'border border-red-500': Boolean(errors.routing_number)}"
          @blur="validate('routing_number')"
      />
      <div class="h-6"><div v-if="errors.routing_number" class="text-red-500">{{ errors.routing_number }}</div></div>
    </div>
  </div>

  <div class="lg:flex lg:flex-row items-center gap-10 lg:my-2">
    <div class="flex-1">
      <label for="">Account Number</label>
      <TextInput
          type="number"
          maxlength="17"
          v-model="form.account_number"
          class="w-full"
          :class="{'border border-red-500': Boolean(errors.account_number)}"
          @blur="validate('account_number')"
      />
      <div class="h-6"><div v-if="errors.account_number" class="text-red-500">{{ errors.account_number }}</div></div>
    </div>

    <div class="flex-1">
      <label for="">Name on Account</label>
      <TextInput
          type="text"
          maxlength="22"
          v-model="form.name_on_account"
          class="w-full"
          :class="{'border border-red-500': Boolean(errors.name_on_account)}"
          @blur="validate('name_on_account')"
      />
      <div class="h-6"><div v-if="errors.name_on_account" class="text-red-500">{{ errors.name_on_account }}</div></div>
    </div>
  </div>

  <div class="lg:flex lg:flex-row items-center gap-10 lg:my-2 w-1/2 lg:pr-4">
    <div class="flex-1">
      <label for="">Bank Name</label>
      <TextInput
          type="text"
          maxlength="50"
          v-model="form.bank_name"
          class="w-full"
          :class="{'border border-red-500': Boolean(errors.bank_name)}"
          @blur="validate('bank_name')"
      />
      <div class="h-6"><div v-if="errors.bank_name" class="text-red-500">{{ errors.bank_name }}</div></div>
    </div>
  </div>

  <div class="pt-4">
    <Button
        @click="submit"
    >
      <i class="fa fa-spinner fa-spin mr-2" v-if="submitting"></i> Save
    </Button>
  </div>
</template>

<script>
import { Money3Component } from 'v-money3'
import SelectField from '@/Components/SelectField.vue'
import TextInput from '@/Components/Input.vue'
import Button from '@/Components/Button.vue'

export default {
  name: 'AchPaymentForm',
  components: {
    Button,
    money3: Money3Component,
    SelectField,
    TextInput,
  },
  props: {
    form: {
      type: Object,
      default: () => ({
        amount: '',
        account_type: null,
        routing_number: null,
        account_number: null,
        name_on_account: null,
        bank_name: null,
      }),
    },
    payment: {
      type: Object
    }
  },
  computed: {
    accountTypes() {
      return [
        {label: 'Checking', value: 'checking'},
        {label: 'Savings', value: 'savings'},
        {label: 'Business Checking', value: 'businessChecking'}
      ]
    }
  },
  methods: {
    validate(key = undefined) {
      if (! key) {
        this.errors = {}
        this.validateField('amount', this.getMessage('amount'))
        this.validateField('account_type', this.getMessage('account_type'))
        this.validateField('routing_number', this.getMessage('routing_number'))
        this.validateField('account_number', this.getMessage('account_number'))
        this.validateField('name_on_account', this.getMessage('name_on_account'))
        this.validateField('bank_name', this.getMessage('bank_name'))
        return this.errors
      }

      this.validateField(key, this.getMessage(key))
      return this.errors
    },
    validateField(key, message) {
      this.errors[key] = null

      if (! this.form[key] || this.form[key].length === 0 || Number(this.form[key]) === 0) {
        this.errors[key] = message
      } else {
        delete this.errors[key]
      }
    },
    async submit () {
      this.validate();

      if (Object.keys(this.errors).length > 0) {
        return
      }

      try {
        this.submitting = true
        const response = await axios.post('/api/ach-payments', { ...this.form, payment_id: this.payment.id })
        this.$emit('submitted', response.data)
      } catch (e) {
        alert('error submitting ACH payment')
      } finally {
        this.submitting = false
      }
    },
    getMessage(key) {
      return `${this.fieldName(key)} is Required.`
    },
    fieldName(key) {
      return {
        'amount': 'Amount',
        'account_type': 'Account Type',
        'routing_number': 'Routing Number',
        'account_number': 'Account Number',
        'name_on_account': 'Name on Account',
        'bank_name': 'Bank Name',
      }[key]
    }
  },
  data () {
    return {
      errors: {},
      submitting: false
    }
  },
}
</script>