import Vue from 'vue'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'

import colors from 'vuetify/lib/util/colors'

Vue.use(Vuetify)

const opts = {
    theme: {
        dark: true,
        themes: {
            dark: {
                primary: colors.red.darken4,
                secondary: colors.red.darken1,
                accent: colors.indigo.base
            }
        },
    }
}

export default new Vuetify(opts)
