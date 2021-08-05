window.Vue = require('vue');
window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.withCredentials = true


import vuetify from "./plugins/vuetify";
import App from "./App";
import router from "./router"
import store from "./store"
import VueToastify from "vue-toastify";

Vue.use(VueToastify);

const main = new Vue({
    router,
    store,
    vuetify,
    render: h => h(App)
}).$mount('#app');
