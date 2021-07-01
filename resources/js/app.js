require("./bootstrap");

// Import modules...
import Vue from "vue";
import wysiwyg from "vue-wysiwyg";
import {
    App as InertiaApp,
    plugin as InertiaPlugin
} from "@inertiajs/inertia-vue";
import PortalVue from "portal-vue";

Vue.mixin({ methods: { route } });
Vue.use(InertiaPlugin);
Vue.use(PortalVue);
Vue.use(wysiwyg, { hideModules: { image: true } });

const app = document.getElementById("app");

Vue.prototype.$route = route;

new Vue({
    render: h =>
        h(InertiaApp, {
            props: {
                initialPage: JSON.parse(app.dataset.page),
                resolveComponent: name => require(`./Pages/${name}`).default
            }
        })
}).$mount(app);
