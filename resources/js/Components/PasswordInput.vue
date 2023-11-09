<script setup>
import { ref } from 'vue'
import TextInput from '@/Components/Input.vue'

defineProps(['modelValue']);
defineEmits(['update:modelValue']);
const visible = ref(false);
</script>

<template>
  <div class="relative">
    <TextInput
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        ref="input"
        class="border w-full"
        :type="visible ? 'text' : 'password'"
    />
    <div class="absolute top-2 right-3 cursor-pointer" @click="visible = ! visible">
      <i v-if="visible" class="fa fa-eye"></i>
      <i v-if="! visible" class="fa fa-eye-slash"></i>
    </div>
  </div>
</template>
