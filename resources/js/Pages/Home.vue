<template>
<div class="home">
    <discount-lots-header />

    <div class="max-w-screen-lg mx-auto py-20 text-center text-white font-bold text-xl bg-gray-100">
        <br><br>
    </div>

    <discount-lots-footer />
</div>
</template>

<script>
import DiscountLotsHeader from '@/Layouts/Partials/DiscountLotsHeader.vue'
import DiscountLotsFooter from '@/Layouts/Partials/DiscountLotsFooter.vue'

export default {
    name: 'Home',
    components: {
        DiscountLotsHeader,
        DiscountLotsFooter,
    }
}
</script>
