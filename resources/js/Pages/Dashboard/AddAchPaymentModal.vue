<script>
import Button from '@/Components/Button.vue'
import DiscountModal from '@/Components/DiscountModal.vue'
import { defineComponent } from 'vue'
import TextInput from '@/Components/Input.vue'
import SelectField from '@/Components/SelectField.vue'
import { Money3Component } from 'v-money3'
import AchPaymentForm from '@/Pages/Dashboard/AchPaymentForm.vue'

export default defineComponent({
  components: { AchPaymentForm, SelectField, TextInput, DiscountModal, Button, money3: Money3Component },
  data () {
    return {
      form: {
        amount: 0,
        account_type: null,
        routing_number: null,
        account_number: null,
        name_on_account: null,
        bank_name: null,
      },
      submitting: false,
    }
  },
  props: {
    show: {
      type: Boolean,
      required: true,
    },
    payment: {
      type: Object,
    }
  },
  methods: {
    onAchPayment() {
      window.alert('ACH Payment Submitted Successfully')
      this.$inertia.reload({ preserveScroll: true })
      this.$emit('close')
    }
  },
  watch: {
    payment(newValue, oldValue) {
      if (newValue) {
        this.form.amount = newValue.data.Total_Outstanding_Amount_with_Fees__c
      }
    }
  }
})
</script>

<template>
  <discount-modal :show="show" @close="$emit('close')" size="md">
    <template #header>
      <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
        <i class="fa fa-credit-card text-gray-600 mr-2"></i> Make ACH Payment
      </h2>
    </template>

    <template #default>

      <ach-payment-form ref="form" :form="form" @submitted="onAchPayment" :payment="payment" />

    </template>
  </discount-modal>
</template>