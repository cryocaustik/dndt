import Vue from "vue";
import VueRouter from "vue-router";
import store from "./store";
import Home from "./views/Home.vue";

Vue.use(VueRouter);

const routes = [
    {
        path: "/",
        name: "home",
        component: Home
    },
    // {
    //     path: "/about",
    //     name: "About",
    //     // route level code-splitting
    //     // this generates a separate chunk (about.[hash].js) for this route
    //     // which is lazy-loaded when the route is visited.
    //     component: () =>
    //         import(/* webpackChunkName: "about" */ "../views/About.vue")
    // }
    {
        path: "/campaign",
        name: "campaign",
        meta: { requiresAuth: true },
        component: () => import("./views/Campaign")
    },
    {
        path: "/inventory",
        name: "inventory",
        meta: { requiresAuth: true },
        component: () => import("./views/Inventory.vue")
    }
];

const router = new VueRouter({
    routes
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (!window.authUser) {
            // next({
            //     path: `${window.Domain}/login`,
            //     query: { redirect: to.fullPath }
            // })
            window.location.href = `${window.Domain}/auth/login?redirect=/#${to.fullPath}`
        } else {
            next()
        }
    } else {
        next() // make sure to always call next()!
    }
})

export default router;
