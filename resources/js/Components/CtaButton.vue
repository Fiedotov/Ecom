<template>
<component
    :is="type"
    class="px-4 bg-cta-orange text-white font-bold rounded py-4 block leading-none"
    :class="{'text-center': align === 'center', 'text-left': align === 'left', 'text-right': align === 'right', 'cursor-not-allowed bg-opacity-50': disabled}"
    :disabled="disabled"
>
    <div v-if="label.length">{{ label }}</div>

    <slot />
</component>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';

export default {
    name: 'CtaButton',
    components: {
        Link,
    },
    props: {
        label: {
            type: String,
            default: '',
        },
        type: {
            type: String,
            default: 'button',
        },
        align: {
            type: String,
            default: 'center',
        },
        disabled: {
          type: Boolean,
          default: false,
        }
    }
}
</script>
