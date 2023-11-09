<template>
  <div class="mx-auto sm:px-6 lg:px-8 flex items-center justify-center flex-col">
    <i class="fa-2x leading-none text-gray-500 mr-4" :class="icon"></i>
    <div class="mt-4">{{ message }}</div>
  </div>
</template>

<script>
export default {
  name: 'Empty',
  props: {
    message: {type: String, required: true},
    icon: {type: String, required: true},
  }
}
</script>