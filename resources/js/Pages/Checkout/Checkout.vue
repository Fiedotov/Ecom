<template>
  <div class="show-property">
    <discount-lots-header/>

    <div class="bg-gray-50 xl:bg-white xl:py-4">
      <div class="bg-gray-100 py-5 mb-4">
        <div class="container mx-auto">
          <div class="xl:w-3/4 mx-auto flex items-center">
            <div class="text-dark-blue">
              <div class="font-serif font-bold text-4xl">Checkout</div>
              <div>
                <i class="fa fa-star text-yellow-400"></i>
                <i class="fa fa-star text-yellow-400"></i>
                <i class="fa fa-star text-yellow-400"></i>
                <i class="fa fa-star text-yellow-400"></i>
                <i class="fa fa-star text-yellow-400"></i>
                Rated 4.9 stars By 3,000+ Clients
              </div>
            </div>
            <img src="/img/discount-lots-bbb-reviews.webp" alt="Better Business Bureau" class="ml-auto w-48"/>
          </div>
        </div>
      </div>
      <div class="container mx-auto">
        <checkout-form
            class="xl:w-3/4 mx-auto"
            :property="property"
            :checkout="checkout"
            :first_renewal_date="first_renewal_date"
        />
      </div>
    </div>

    <discount-lots-footer/>
  </div>
</template>

<script>
import DiscountLotsHeader from '@/Layouts/Partials/DiscountLotsHeader.vue'
import DiscountLotsFooter from '@/Layouts/Partials/DiscountLotsFooter.vue'
import OrderSummary from '@/Pages/Checkout/OrderSummary.vue'
import CheckoutForm from '@/Pages/Checkout/Form/CheckoutForm.vue'

export default {
  name: 'Checkout',
  components: {
    CheckoutForm,
    DiscountLotsFooter,
    DiscountLotsHeader,
    OrderSummary,
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
}
</script>