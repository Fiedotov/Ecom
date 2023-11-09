<template>
  <div class="bg-white pt-6">
    <div class="border-b border-gray-300">
      <div class="max-w-screen-lg mx-auto md:flex items-start">
        <div class="md:w-1/3 p-4">
          <div>
            <img
                src="/img/discount-lots-logo.png"
                alt="Discount Lots"
                class="max-w-[200px]"
            />
          </div>
          <div class="py-6">
            <div><i class="fa fa-phone mr-2"></i> <span class="font-bold">Toll Free:</span> 866-808-7809
            </div>
            <div><i class="fa fa-envelope mr-2"></i> sales@discountlots.com</div>
          </div>
          <div>
            <img src="/img/bbb-logo.png" alt="Better Business Bureau" style="max-width: 200px"/>
          </div>
        </div>
        <div class="md:w-1/3 p-4">
          <div class="font-serif font-bold text-2xl">Listings By State</div>
          <ul class="mt-6 text-lg">
            <li>
              <a
                  href="/?states=Arizona"
                  class="font-semibold text-discount-blue py-2 inline-block leading-none"
              >
                Arizona Land For Sale
              </a>
            </li>
            <li>
              <a
                  href="/?states=California"
                  class="font-semibold text-discount-blue py-2 inline-block leading-none">
                California Land For Sale
              </a>
            </li>
            <li>
              <a
                  href="/?states=Florida"
                  class="font-semibold text-discount-blue py-2 inline-block leading-none">
                Florida Land For Sale
              </a>
            </li>
            <li>
              <a
                  href="/?states=Texas"
                  class="font-semibold text-discount-blue py-2 inline-block leading-none">
                Texas Land For Sale
              </a>
            </li>
          </ul>
        </div>
        <div class="md:w-1/3 p-4">
          <div class="font-serif font-bold text-2xl">Join Our VIP Buyers List</div>
          <div class="mt-4">Sales and updates in your inbox!</div>
          <div class="mt-4 lg:flex lg:items-center">
            <div>
              <label class="block text-gray-600 text-sm" for="email">Email *</label>
              <input id="email"
                     class="w-full border border-gray-300 rounded p-2 bg-white focus:outline-none"
                     name="email"
                     type="email"/>
            </div>
            <div>
              <BreezeButton class="mt-2 py-2 lg:px-4 lg:mt-4 lg:ml-2 w-full justify-center lg:leading-4">
                Submit
              </BreezeButton>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="flex flex-col items-center py-4">
      <div class="my-2 px-4 text-lg">
        Please read our policies regarding
        <a
            :href="`https://discountlots.com/policies${$page.props.tracking_query}`"
            target="_blank"
            class="underline text-dark-blue"
        >payment plans</a>
      </div>

      <div class="my-2">
        <img src="/img/cc-logos.jpeg"
             class="max-w-[320px]"
             alt="Credit Cards Accepted"/>
      </div>

      <div class="text-sm my-2 px-4">
        Copyright Discount Lots &copy; {{ (new Date).getFullYear() }}. All rights reserved.
        <a
            :href="`https://discountlots.com/privacy-policy${$page.props.tracking_query}`"
            target="_blank"
            class="underline border-l px-2 border-gray-500"
        >
          Privacy Policy
        </a>
        <a
            :href="`https://discountlots.com/terms-and-conditions${$page.props.tracking_query}`"
            target="_blank"
            class="underline border-l px-2 border-gray-500"
        >Terms &amp; Conditions</a>
      </div>

      <div class="my-2 leading-none text-xs">
        <a href="https://www.facebook.com/BuyDiscountLots/"
           class="fa-stack icon-facebook-f text-dark-blue hover:text-cta-orange mr-1"
           target="_blank"
           title="Discount Lots on Facebook"
           rel="noopener noreferrer">
          <span class="sr-only">Facebook</span>
          <i aria-hidden="true" class="fas fa-circle fa-stack-2x mono text-inherit"></i>
          <i aria-hidden="true" class="fab fa-facebook-f mono fa-stack-1x fa-inverse"></i>
        </a>
        <a href="https://www.youtube.com/c/DiscountLots"
           title="Discount Lots on YouTube"
           class="fa-stack icon-youtube text-dark-blue hover:text-cta-orange mx-1" target="_blank"
           rel="noopener noreferrer"><span class="sr-only">Youtube</span>
          <i aria-hidden="true" class="fas fa-circle fa-stack-2x mono"></i>
          <i aria-hidden="true" class="fab fa-youtube mono fa-stack-1x fa-inverse"></i>
        </a>
        <a href="https://www.instagram.com/discountlots/"
           title="Discount Lots on Instagram"
           class="fa-stack icon-instagram text-dark-blue hover:text-cta-orange mx-1"
           target="_blank"
           rel="noopener noreferrer"><span class="sr-only">Instagram</span>
          <i aria-hidden="true" class="fas fa-circle fa-stack-2x mono"></i>
          <i aria-hidden="true" class="fab fa-instagram mono fa-stack-1x fa-inverse"></i>
        </a>
        <a href="https://www.tiktok.com/@discountlots.com"
           title="Discount Lots on TikTok"
           class="fa-stack tiktok-icon text-dark-blue hover:text-cta-orange ml-1"
           target="_blank"
           rel="noopener noreferrer">
          <span class="sr-only">Tiktok</span>
          <i aria-hidden="true" class="fas fa-circle fa-stack-2x mono"></i>
          <i aria-hidden="true" class="fab fa-tiktok fa-stack-1x fa-inverse mono"></i>
        </a>
      </div>
    </div>

  </div>
</template>

<script>
import BreezeButton from '@/Components/Button.vue'

export default {
  name: 'DiscountLotsFooter',
  components: {
    BreezeButton,
  },
}
</script>
