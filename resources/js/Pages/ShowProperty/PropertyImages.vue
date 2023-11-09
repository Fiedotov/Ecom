<template>
  <div class="property-images">
    <carousel :items-to-show="1" autoplay="3000" :wrap-around="true">
      <slide v-for="image in property.images" :key="`image-${image.id}`">
        <img :src="image.url" :alt="image.id" />
      </slide>

      <template #addons>
        <navigation class="discount-nav-link" />
      </template>
    </carousel>
  </div>
</template>

<script>
import 'vue3-carousel/dist/carousel.css'
import { Carousel, Slide, Pagination, Navigation } from 'vue3-carousel'

export default {
  name: 'PropertyImages',
  components: {
    Carousel,
    Navigation,
    Pagination,
    Slide,
  },
  props: {
    property: {
      type: Object,
      required: true,
    }
  },
  computed: {
    images() {
      return this.property.images
    }
  }
}
</script>

<style>

.discount-nav-link {
    width: 38px;
    height: 54px;
    background-repeat: no-repeat;
    display: none;

    @media screen and (min-width: 768px) {
        display: block;
    }
}

.discount-nav-link svg {
    display: none;
}

.discount-nav-link.carousel__next {
    background-position: left top;
    background-image: url(/img/arrow-next.png);
    right: 20px;
}

.discount-nav-link.carousel__next--disabled {
    background-position: left top;
}

.discount-nav-link.carousel__prev {
    background-position: right top;
    background-image: url(/img/arrow-prev.png);
    left: 20px;
}

.discount-nav-link.carousel__prev--disabled {
    background-position: right top;
}

</style>