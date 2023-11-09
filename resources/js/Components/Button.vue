<script setup>
const props = defineProps({
    type: {
        type: String,
        default: 'submit',
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    display : {
        type: String,
        default: 'default'
    }
});
</script>

<template>
    <button
        :type="type"
        :disabled="disabled"
        class="inline-flex items-center justify-center px-4 py-3 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:shadow-outline-blue transition ease-in-out duration-150 leading-none"
        :class="{
            'bg-blue-900 hover:bg-discount-blue active:bg-blue-900 focus:border-blue-900': display === 'default',
            'bg-red-500 hover:bg-red-600 active:bg-red-500 focus:border-red-500': display === 'danger',
            'border-blue-900 hover:border-discount-blue active:border-blue-900 focus:border-blue-900 text-blue-900 hover:text-discount-blue': display === 'outline',
            'border-red-600 hover:border-red-600 active:border-red-600 focus:border-red-600 text-red-600 hover:text-red-600': display === 'outline-danger',
            'cursor-not-allowed bg-gray-300 hover:bg-gray-300': disabled
        }"
    >
        <slot />
    </button>
</template>
