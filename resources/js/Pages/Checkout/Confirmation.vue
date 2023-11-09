<template>
<div>
  <discount-lots-header/>

  <div class="container mx-auto py-8 px-4 xl:max-w-[50vw]">
    <div class="flex items-center">
      <div><i class="fa fa-check-circle fa-3x pr-6 text-discount-blue"></i></div>
      <div>
        <div>Confirmation #{{ submission.authorize_net_response.transactionResponse.transId }}</div>
        <div class="text-xl font-semibold">Thank You {{ submission.payload.customer.first_name }} {{ submission.payload.customer.last_name }}!</div>
      </div>
    </div>

    <div class="p-6 rounded border border-gray-300 mt-6">
      <property-map :property="submission.property" />

      <property-info :property="submission.property" class="mt-6" />

      <div class="font-semibold tracking-tight text-lg mt-4">Your order is confirmed</div>
      <div class="mt-3">We've accepted your order, and we're getting it ready.</div>
    </div>

    <div class="p-6 rounded border border-gray-300 mt-6">
      <div class="flex flex-col xl:flex-row">
        <div class="xl:w-1/2">
          <div class="mb-4 text-xl font-semibold tracking-tight">Customer Information</div>
          <div>
            <div>{{ submission.payload.customer.first_name }} {{ submission.payload.customer.last_name }}</div>
            <div>{{ submission.payload.customer.address }}</div>
            <div>{{ submission.payload.customer.address2 }}</div>
            <div>{{ submission.payload.customer.city }}, {{ submission.payload.customer.state }}</div>
            <div>{{ submission.payload.customer.postal_code }}</div>
            <div class="mt-4">{{ submission.payload.customer.email }}</div>
            <div>{{ submission.payload.customer.phone }}</div>
          </div>

        </div>
        <div class="pt-6 xl:pt-0 xl:w-1/2">
          <div class="mb-4 text-xl font-semibold tracking-tight">Payment Information</div>

          <div class="tracking-tight text-xl mb-2 font-semibold">{{ Number(submission.amount).toFixed(2) }}</div>
          <div class="flex items-center">
            <i class="fa fa-credit-card mr-2"></i>
            <div>{{ submission.authorize_net_response.transactionResponse.accountType }}</div>
            <div class="ml-2">- {{ submission.authorize_net_response.transactionResponse.accountNumber }}</div>
          </div>
          <div class="mt-1">Reference No. {{ submission.authorize_net_response.transactionResponse.transId }}</div>
        </div>
      </div>
      <div class="pt-4" v-if="user">
        <div v-if="user.email_verified_at">
          Account exists for user <span class="font-bold">{{ user.email }}</span> - <a :href="route('login')" class="text-discount-blue">Login</a>
        </div>
        <div v-if="! user.email_verified_at">
          <div>An account has been created for user <span class="font-bold">{{ user.email }}</span>.</div>
          <div>Check your inbox for an email to create your password and access your account!</div>
        </div>
      </div>
    </div>

    <docusign :submission="submission" v-if="false" />
  </div>

  <discount-lots-footer/>
</div>
</template>

<script>
import DiscountLotsHeader from '@/Layouts/Partials/DiscountLotsHeader.vue'
import DiscountLotsFooter from '@/Layouts/Partials/DiscountLotsFooter.vue'
import Docusign from '@/Pages/Checkout/Docusign.vue'
import PropertyInfo from '@/Pages/Checkout/PropertyInfo.vue'
import PropertyMap from '@/Pages/ShowProperty/PropertyMap.vue'

export default {
  name: 'Confirmation',
  components: {
    DiscountLotsHeader,
    DiscountLotsFooter,
    Docusign,
    PropertyInfo,
    PropertyMap,
  },
  props: {
    submission: {
      type: Object,
      required: true,
    },
    user: {
      type: Object,
      required: false,
    }
  },
}
</script>