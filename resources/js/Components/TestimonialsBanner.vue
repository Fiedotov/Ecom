<template>
<div class="testimonials-banner bg-dark-blue text-white py-12">
    <div class="text-center text-4xl font-serif w-1/2 mx-auto">
        <div>We're Not Just a Company, We're a Community.</div>
        <div class="font-bold mt-1">Here's What People Are Saying About Us.</div>
    </div>

    <div class="mx-auto max-w-screen-lg mt-16">
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-10 px-8 xl:px-0">
            <div class="text-center flex-1">
                <div class="embed-container">
                    <iframe
                      src="https://player.vimeo.com/video/744242203?h=f81da7f02b&amp;badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479"
                      frameborder="0"
                      allow="autoplay; fullscreen; picture-in-picture"
                      allowfullscreen=""
                      style="position:absolute;top:0;left:0;width:100%;height:100%;"
                      title="Morehead Homestead"
                      data-ready="true"
                    ></iframe>
                </div>
                <div class="mt-4">
                    <i class="fa fa-star text-yellow-400"></i>
                    <i class="fa fa-star text-yellow-400"></i>
                    <i class="fa fa-star text-yellow-400"></i>
                    <i class="fa fa-star text-yellow-400"></i>
                    <i class="fa fa-star text-yellow-400"></i>
                </div>
                <div>
                    <div class="font-bold my-2">Morehead Homestead</div>
                    <div class="mt-2">
                        Whether you want to build your dream home or do homestead like we do - Discount Lots have the property for you.
                    </div>
                </div>
            </div>
            <div class="text-center flex-1">
                <div class="embed-container">
                    <iframe
                      src="https://player.vimeo.com/video/744241996?h=6d38494b7a&amp;badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479"
                      frameborder="0"
                      allow="autoplay; fullscreen; picture-in-picture"
                      allowfullscreen=""
                      style="position:absolute;top:0;left:0;width:100%;height:100%;"
                      title="Pacific Pines Ranch"
                      data-ready="true"
                    ></iframe>
                </div>
                <div class="mt-4">
                    <i class="fa fa-star text-yellow-400"></i>
                    <i class="fa fa-star text-yellow-400"></i>
                    <i class="fa fa-star text-yellow-400"></i>
                    <i class="fa fa-star text-yellow-400"></i>
                    <i class="fa fa-star text-yellow-400"></i>
                </div>
                <div>
                    <div class="font-bold my-2">Pacific Pines Ranch</div>
                    <div class="mt-2">Open a campground, start a farm, or build your dream house - Discount Lots makes all of that possible.</div>
                </div>
            </div>
            <div class="text-center flex-1">
                <div class="embed-container">
                    <iframe
                      src="https://player.vimeo.com/video/744242372?h=316aaf46de&amp;badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479"
                      frameborder="0"
                      allow="autoplay; fullscreen; picture-in-picture"
                      allowfullscreen=""
                      style="position:absolute;top:0;left:0;width:100%;height:100%;"
                      title="This Off Grid Life"
                      data-ready="true"
                    ></iframe>
                </div>
                <div class="mt-4">
                    <i class="fa fa-star text-yellow-400"></i>
                    <i class="fa fa-star text-yellow-400"></i>
                    <i class="fa fa-star text-yellow-400"></i>
                    <i class="fa fa-star text-yellow-400"></i>
                    <i class="fa fa-star text-yellow-400"></i>
                </div>
                <div>
                    <div class="font-bold my-2">This Off Grid Life</div>
                    <div class="mt-2">No matter your situation, as little as $200 a month, you could be on some land starting your dream.</div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
export default {
    name: "TestimonialsBanner"
}
</script>

<style>
.embed-container {
    position: relative;
    padding-bottom: 100%;
    height: 0;
    overflow: hidden;
    max-width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.embed-container iframe, .embed-container object, .embed-container embed {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}
</style>
