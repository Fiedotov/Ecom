<template>
    <div class="border-b border-gray-200 py-4">
        <div class="flex items-center">
            <div class="font-bold text-lg">
                <slot name="default" />
            </div>
            <div class="ml-auto pl-2">
                <i @click="expandContent" class="fa leading-none fa-2x text-discount-blue cursor-pointer hover:text-dark-blue" :class="{'fa-plus-circle': ! expanded, 'fa-minus-circle': expanded}"></i>
            </div>
        </div>
        <transition name="slide-fade">
            <div v-if="expanded" class="py-3">
                <slot name="content" />
            </div>
        </transition>
    </div>
</template>

<script>
export default {
    name: "ContentToggler",
    data() {
        return {
            expanded: false,
        }
    },
    methods: {
        expandContent() {
            this.expanded = ! this.expanded
        }
    }
}
</script>

<style scoped>
/*
  Enter and leave animations can use different
  durations and timing functions.
*/
.slide-fade-enter-active {
    transition: all 0.2s ease-in;
}

.slide-fade-enter-from,
.slide-fade-leave-to {
    transform: translateY(-30px);
    opacity: 0;
}
</style>
