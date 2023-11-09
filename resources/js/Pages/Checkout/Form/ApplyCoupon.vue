<template>
    <div>
        <div class="my-2">
            <div class="flex justify-between">
                <TextInput id="coupon"
                           type="text"
                           class="w-80"
                           :class="{ 'border-red-600' : invalidCoupon }"
                           placeholder="Enter a coupon code"
                           autofocus
                           autocomplete="off"
                           :disabled="appliedCoupon"
                           v-model="enteredCoupon" />
                <Button type="button"
                        class="border-2 border-cta-orange py-3 px-2 leading-none rounded uppercase font-bold text-cta-orange mx-auto hover:text-white hover:bg-cta-orange"
                        :disabled="appliedCoupon"
                        @click="applyCoupon">
                    <i class="fa fa-tag align-middle"></i>
                    Apply
                </Button>
            </div>
            <div class="text-red-500 relative text-sm font-bold"
                 v-if="invalidCoupon">
                <i class="fa fa-circle-info"></i> Coupon code is invalid.
            </div>
        </div>

        <div class="overflow-hidden rounded-lg shadow-md bg-blue-50">
            <div class="flex justify-between m-3"
                 v-if="appliedCoupon">
                <label class="font-semibold text-sm">
                     <i class="fa fa-circle-check text-green-600 text-lg align-middle"></i> {{ appliedCoupon.label }}
                 </label>
                <Button type="button"
                        class="text-sm text-red-600 font-bold hover:opacity-80"
                        @click="removeAppliedCoupon">
                        Remove
                </Button>
            </div>
        </div>
    </div>
</template>

<script>
import TextInput from '@/Components/Input.vue'
import axios from 'axios'
import { toRaw } from 'vue'

export default {
    name: 'ApplyCoupon',

    components: {
        TextInput
    },

    props: {
        property: {
            type: Object,
            required: true,
        },

        paymentCount: {
            type: Number,
            required: true,
        },
    },
    
    data () {
        return {
            enteredCoupon: '',
            appliedCoupon: null,
            invalidCoupon: false
        }
    },

    methods: {
        async applyCoupon () {
            let enteredCoupon = this.enteredCoupon.trim()
            if (enteredCoupon.length < 1) {
                return
            }

            this.invalidCoupon = false
            await this.validatePromotionCode(enteredCoupon)
        },

        clearCouponInput () {
            this.enteredCoupon = ''
        },

        removeAppliedCoupon () {
            this.appliedCoupon = null
        },

        async validatePromotionCode (couponCode) {
            const pay_setup = this.paymentCount === 1 ? 'full' : 'monthly';
            await axios.get(`/api/admin/promotions?valid=1&pay_setup=${pay_setup}&coupon=${couponCode}&property=${this.property.id}`)
                .then(response => {
                    // invalid coupon
                    if (response.data.data.length < 1) {
                        this.invalidCoupon = true
                        return
                    }

                    this.appliedCoupon = response.data.data[0]
                })
                .catch(error => {
                    console.log(error)
                })
                .finally(() => {
                    this.clearCouponInput()
                })
        }
    },

    watch: {
        appliedCoupon (newValue, oldValue) {
            this.$emit('applyDiscount', toRaw(newValue))
        }
    }
}
</script>