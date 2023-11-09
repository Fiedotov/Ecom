<template>
    <div class="bg-navy-blue text-white py-2 px-2">
      <div class="mx-auto">
        <div class="xl:w-3/4 mx-auto flex items-center flex-col md:flex-row md:justify-between">
          <div class="font-bold text-center">
            <i class="fa-solid fa-phone mr-2"></i>
            <a href="tel:866-808-7809">{{ $page.props.tag_line }}</a>
          </div>
          <div class="flex items-center justify-center">
            <a href="https://www.facebook.com/BuyDiscountLots/" class="mr-3" target="_blank"><i
                class="fa-brands fa-facebook-f"></i></a>
            <a href="https://www.youtube.com/c/DiscountLots" class="mr-3" target="_blank"><i
                class="fa-brands fa-youtube"></i></a>
            <a href="https://www.instagram.com/discountlots/" class="mr-3" target="_blank"><i
                class="fa-brands fa-instagram"></i></a>
            <a href="https://www.tiktok.com/@discountlots.com" class="mr-3" target="_blank"><i
                class="fa-brands fa-tiktok"></i></a>
          </div>
        </div>
      </div>
    </div>
    <div class="bg-white drop-shadow-sm">
      <div class="mx-auto">
        <div class="xl:w-3/4 lg:flex lg:items-center mx-auto py-1">
          <div class="flex items-center flex-row">
            <div class="flex-1 py-2 px-4 lg:px-0 mx-auto">
              <a :href="`https://discountlots.com${$page.props.tracking_query}`">
                <img
                    src="/img/discount-lots-logo.png"
                    alt="Discount Lots"
                    class="md:m-auto md:max-w-xs"
                    style="max-width: 240px"/>
              </a>
            </div>
            <div class="flex-1 md:hidden flex-1 py-2 px-4 max-w-screen-xl mx-auto">
              <button class="float-right" @click="toggleMenu">
                <i class="fa-solid fa-bars fa-2x leading-none"></i>
              </button>
            </div>
          </div>

          <ul
              class="pb-3 md:pb-0 mt-4 flex flex-col md:flex-row md:justify-center border-t border-b border-gray-300 lg:border-none lg:ml-auto lg:mr-4 lg:mt-0 tracking-tight md:flex"
              :class="{'hidden': ! menu}"
          >
            <li class="mx-3 lg:mx-2 md:border-t border-gray-300 md:border-none">
              <a
                :href="`https://discountlots.com/about-us${$page.props.tracking_query}`"
                class="px-3 py-3 block"
              >
                About
              </a>
            </li>
            <li class="mx-3 lg:mx-2 border-t border-gray-300 md:border-none">
              <a
                :href="`https://discountlots.com/blog${$page.props.tracking_query}`"
                class="px-3 py-3 block"
              >
                Blog
              </a>
            </li>
            <li class="mx-3 lg:mx-2 border-t border-gray-300 md:border-none">
              <Link :href="route('login')" class="px-3 py-3 block">
                My Account
              </Link>
            </li>
            <li class="mx-3 lg:mx-2 border-t border-gray-300 md:border-none">
              <a
                :href="`https://discountlots.com/contact-us${$page.props.tracking_query}`"
                class="px-3 py-3 block"
              >
                Contact
              </a>
            </li>
            <li class="mx-3 lg:mx-2 border-t border-gray-300 md:border-none">
              <a
                :href="`https://discountlots.com/en-espanol${$page.props.tracking_query}`"
                class="px-3 py-3 block"
              >
                En Español
              </a>
            </li>
            <li class="mx-3 lg:ml-2 lg:mr-0">
              <cta-button
                  label="Search Land for Sale"
                  type="a"
                  align="left"
                  :href="route('home')"
                  target="_blank"
              >
              </cta-button>
            </li>
          </ul>
        </div>
      </div>
    </div>
</template>

<script>
import CtaButton from '@/Components/CtaButton.vue'
import {Link} from '@inertiajs/inertia-vue3';

export default {
    name: 'DiscountLotsHeader',
    components: {
        CtaButton,
        Link,
    },
    data() {
        return {
            menu: false,
        }
    },
    methods: {
        toggleMenu() {
            this.menu = !this.menu
        }
    }
}
</script>
