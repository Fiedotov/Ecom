<template>
<div class="you-might-also-like py-20 bg-gray-100 relative">
    <div class="text-center font-serif text-dark-blue text-3xl tracking-wide">
      You Might <span class="font-bold">Also Like</span>
    </div>

    <div class="mx-auto max-w-screen-lg mt-12 flex items-center">
        <div class="flex items-center overflow-hidden relative">
          <div class="absolute left-0">
            <button
                @click="slide('right')"
                class="leading-none flex items-center justify-center h-10 w-10 rounded-full text-gray-800 font-bold cursor-pointer text-3xl"
                type="button"
            >
              <i class="fa fa-chevron-left"></i>
            </button>
          </div>

            <property-card
                v-for="(property, index) in properties"
                :property="property"
                :key="`property-${property.id}`"
                class="mx-3 min-w-[94vw] sm:min-w-[46vw] lg:min-w-[320px]"
            />

          <div class="absolute right-0">
            <button
                @click="slide('left')"
                class="leading-none flex items-center justify-center h-10 w-10 rounded-full text-gray-800 font-bold cursor-pointer text-3xl"
                type="button"
            >
              <i class="fa fa-chevron-right"></i>
            </button>
          </div>
        </div>
    </div>

</div>
</template>

<script>
import PropertyCard from '@/Pages/SearchLandForSale/PropertyCard.vue'

export default {
    name: 'YouMightAlsoLike',
    components: {
        PropertyCard,
    },
    props: {
        properties: {
            type: Array,
            default: [],
        }
    },
    data() {
        return {
            slidePosition: 0,
        }
    },
    methods: {
        slide(direction) {
            const firstCard = this.$el.querySelectorAll('.property-card')[0]
            const cardWidth = firstCard.clientWidth + 26
            const style = firstCard.currentStyle || window.getComputedStyle(firstCard);

            if (direction === 'left' && (this.slidePosition > ((this.properties.length - 3) * -1))) {
                firstCard.style.marginLeft = `${(parseInt(style.marginLeft) - cardWidth)}px`
            }

            if (direction === 'right' && this.slidePosition < 0) {
                firstCard.style.marginLeft = `${(parseInt(style.marginLeft) + cardWidth)}px`
            }

            this.slidePosition = Math.round((parseInt((firstCard.style.marginLeft) || 0) - 12) / cardWidth)
        },
    }
}
</script>

<style>
.property-card {
    transition: all 700ms;
}
</style>
