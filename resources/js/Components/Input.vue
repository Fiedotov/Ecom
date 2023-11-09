<script setup>
import { onMounted, ref } from 'vue';

defineProps({
  modelValue: { type: [String, Number], required: true },
  mask: { type: String, default: undefined },
  maxlength: { type: Number, default: undefined },
});

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});
</script>

<template>
  <input
      class="border-gray-300 focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 rounded shadow-sm px-3 py-2 placeholder:text-slate-300 text-gray-800"
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
      ref="input"
      v-maska="mask"
      :maxlength="maxlength"
  />
</template>
