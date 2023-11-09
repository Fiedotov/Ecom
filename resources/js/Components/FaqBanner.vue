<template>
<div class="faq-banner bg-white py-12">
    <div class="mx-auto max-w-screen-lg flex flex-col lg:flex-row lg:items-end">
        <div class="text-dark-blue lg:w-7/12 lg:pr-12 px-4">
            <div class="font-bold font-serif text-3xl">Got Questions?</div>
            <div class="font-serif text-3xl">We've Got You Covered</div>

            <content-toggler>
                <template #default>Is this a scam?</template>
                <template #content>
                    No!  We purchase lots through a variety of sources at wholesale prices. Once we own a property, we list it here, on DiscountLots.com.
                </template>
            </content-toggler>
            <content-toggler>
                <template #default>How does it work?</template>
                <template #content>
                    First step is to find your lot. You can use our interactive map to search and filter available properties. Then check out individual listings and see if they meet your needs. Once you have chosen the right property for you, you can purchase your lot online! We make it easy to choose between a payment plan or paying for your lot in a single lump sum.
                </template>
            </content-toggler>
            <content-toggler>
                <template #default>Do you check credit?</template>
                <template #content>
                    No! When you choose to purchase a property from us, you can be secure in knowing that we handle all qualification decisions in-house without doing a credit check. A few basic questions allow us to figure out our program is right for you.
                </template>
            </content-toggler>
            <content-toggler>
                <template #default>Are you a real estate company?</template>
                <template #content>
                    <div>
                        We are a private buyer group based in Chicago with offices in Southern California and new offices opening soon in Florida and Texas.
                    </div>
                    <div class="mt-2">
                        Because we don't have the agents and overhead of a traditional real estate brokerage, we can sell direct to you at lower prices.
                    </div>
                </template>
            </content-toggler>
            <content-toggler>
                <template #default>Can you explain your rent-to-own program?</template>
                <template #content>
                        <div>
                            Yes! We offer rentals for all of our properties. Most importantly, all of our agreements are
                            vetted and approved in house. This means that not only do you get reduced fees, but there is no
                            credit check. In addition, at the end of your rental term, you can purchase the property out
                            right for only one additional payment that is equal to the monthly payment during your contract
                            period.
                        </div>
                        <div class="mt-2">
                            Best of all, you have full use of the land while you are making payments, you can skip a payment
                            if you need to (contact us first!), you can stop payment with no penalty (or bad credit mark) at
                            any time, and we will not sell the property during your contract period. It's yours to use and
                            enjoy.
                        </div>
                </template>
            </content-toggler>
            <content-toggler>
                <template #default>How do I buy a property?</template>
                <template #content>
                    <div>When you find one of our properties that captures your interest, contact us to set up a time to talk to one of our buyer specialists. We will walk you through the process, answer any questions you have about the land, and help you visit the property before you buy.</div>
                    <div class="mt-4">Of course, we have many clients who buy property right from our site using the purchasing options on the listing page. Once you set up a payment plan, we will send you the contract for the land. Closings are typically handled in less than a week.</div>
                </template>
            </content-toggler>
            <content-toggler>
                <template #default>How often do you have new properties for sale?</template>
                <template #content>
                    <div>We visit bank auctions and other wholesaling events across the state on a regular basis. If you would like to get advance notice of new properties before we post them on the site, please join our VIP Buyers List (see form at the bottom of this page).</div>
                    <div class="mt-4">Lots are sold on a first come, first served basis. We cannot hold a lot without taking a deposit.</div>
                </template>
            </content-toggler>
            <content-toggler>
                <template #default>Can I visit a property beforehand?</template>
                <template #content>
                    Absolutely. We encourage all of our buyers to visit the property before buying. Not only does it help you see the value of your investment up front, but you can also being making plans about how you will use the lot once you've purchased it.
                </template>
            </content-toggler>
        </div>
        <div class="lg:flex-1">
            <img src="/images/people.png" alt="Discount Lots" />
        </div>
    </div>
</div>
</template>

<script>
import ContentToggler from "@/Pages/ShowProperty/ContentToggler.vue";

export default {
    name: "FaqBanner",
    components: {
        ContentToggler,
    }
}
</script>
