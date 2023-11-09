<template>
    <div class="mt-6 w-full min-h-[500px] relative">
        <transition-group name="slide-fade">
            <div class="flex items-center justify-center text-gray-800 bg-gray-200 gap-2 h-full absolute top-0 left-0 right-0 bottom-0"
                 v-if="loading">
                <i class="fa fa-spinner fa-spin fa-2x"></i>
                <div>Generating your contract...</div>
            </div>
            <iframe
                    v-else
                    class="w-full min-h-[1200px] border border-gray-200"
                    :src="src"
                    frameborder="0"
            ></iframe>
        </transition-group>
    </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'Docusign',
  props: {
    submission: {
      type: Object,
      required: true,
    },
  },
  data () {
    return {
      src: null,
      loading: true,
    }
  },
  methods: {
    async getUrl () {
      try {
        const response = await axios.get(`/api/docusign/${this.submission.id}`)
        this.src = response.data.url
        this.loading = false
      } catch (e) {
        setTimeout(async () => { await this.getUrl() }, 1000)
      }
    },
  },
  mounted () {
    const timeout = Boolean(this.submission.sf_contract_response) ? 0 : 2000

    setTimeout(async () => { await this.getUrl() }, timeout)
  },
}
</script>

<style scoped>
/*
  Enter and leave animations can use different
  durations and timing functions.
*/
.slide-fade-enter-active {
    transition: all 0.3s ease-out;
}

.slide-fade-leave-active {
    transition: all 0.8s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-fade-enter-from,
.slide-fade-leave-to {
    transform: translateX(20px);
    opacity: 0;
}
</style>