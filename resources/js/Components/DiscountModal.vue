<template>
  <transition>
    <div class="modal-mask" v-if="show">
      <div class="modal-wrapper" @click.self="$emit('close')">
        <div
            class="modal-container bg-white rounded-3xl p-10 mx-auto relative max-h-[80vh] overflow-y-scroll"
            :class="modalSize"
        >
          <i class="fa fa-times text-xl font-bold absolute right-4 top-4 cursor-pointer" @click="$emit('close')"></i>

          <div v-if="$slots.header" class="modal-header pb-4 mb-4 border-b border-gray-200">
            <slot name="header"></slot>
          </div>

          <div class="modal-body">
            <slot>
              default body
            </slot>
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
export default {
  name: 'DiscountModal',
  props: {
    show: { type: Boolean, required: true },
    size: { type: String, default: 'md' }
  },
  watch: {
    show(showing, wasShowing) {
      if (showing) {
        document.querySelector('body').classList.add('overflow-hidden')
      } else {
        document.querySelector('body').classList.remove('overflow-hidden')
      }
    }
  },
  computed: {
    modalSize() {
      return {
        sm: 'lg:max-w-[40vw]',
        md: 'lg:max-w-[60vw]',
        lg: 'lg:max-w-[94vw]',
      }[this.size]
    }
  }
}
</script>

<style>
.modal-mask {
  position: fixed;
  z-index: 9998;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.7);
  display: table;
  transition: opacity 0.3s ease;
}

.modal-wrapper {
  display: table-cell;
  vertical-align: middle;
}

.v-enter-active,
.v-leave-active {
  transition: opacity 0.3s ease;
}

.v-enter-from,
.v-leave-to {
  opacity: 0;
}

</style>