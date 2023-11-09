<template>
    <div class="show-property">
        <Head :title="property.title" />

        <discount-lots-header/>

        <property-header :property="property"/>

        <info-banner/>

        <property-description :property="property"/>

        <property-details :property="property"/>

        <property-payment-plan :property="property" v-if="property.is_available" />

        <you-might-also-like :properties="other_properties"/>

        <testimonials-banner/>

        <faq-banner/>

        <discount-lots-footer/>
    </div>
</template>

<script>
import DiscountLotsHeader from '@/Layouts/Partials/DiscountLotsHeader.vue'
import DiscountLotsFooter from '@/Layouts/Partials/DiscountLotsFooter.vue'
import FaqBanner from '@/Components/FaqBanner.vue'
import InfoBanner from '@/Pages/ShowProperty/InfoBanner.vue'
import PropertyDescription from '@/Pages/ShowProperty/PropertyDescription.vue'
import PropertyDetails from '@/Pages/ShowProperty/PropertyDetails.vue'
import PropertyHeader from '@/Pages/ShowProperty/PropertyHeader.vue'
import PropertyPaymentPlan from '@/Pages/ShowProperty/PropertyPaymentPlan.vue'
import TestimonialsBanner from '@/Components/TestimonialsBanner.vue'
import YouMightAlsoLike from '@/Pages/ShowProperty/YouMightAlsoLike.vue'
import { Head } from '@inertiajs/inertia-vue3'

export default {
    name: 'Index',
    components: {
        Head,
        DiscountLotsFooter,
        DiscountLotsHeader,
        FaqBanner,
        InfoBanner,
        PropertyDescription,
        PropertyDetails,
        PropertyHeader,
        PropertyPaymentPlan,
        TestimonialsBanner,
        YouMightAlsoLike,
    },
    props: {
        property: {
            type: Object,
            required: true,
        },
        other_properties: {
            type: Array,
            default: [],
        }
    }
}
</script>
