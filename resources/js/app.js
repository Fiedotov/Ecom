import './bootstrap'
import '../css/app.css'

import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/inertia-vue3'
import { InertiaProgress } from '@inertiajs/progress'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import VueGoogleMaps from '@fawmi/vue-google-maps'
import LoadScript from 'vue-plugin-load-script'
import Maska from 'maska'
import VueCreditCardValidation from 'vue-credit-card-validation'
import Notifications from '@kyvg/vue3-notification'

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel'

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
  setup ({ el, app, props, plugin }) {
    return createApp({ render: () => h(app, props) })
      .use(plugin)
      .use(LoadScript)
      .use(Maska)
      .use(VueCreditCardValidation)
      .use(VueGoogleMaps, { load: { key: import.meta.env.VITE_GOOGLE_MAPS_API_KEY } })
      .use(Notifications)
      .mixin({ methods: { route } })
      .mount(el)
  },
})

InertiaProgress.init({ color: '#489CD8' })
