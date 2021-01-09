import Vue from "vue";
import VueRouter from "vue-router";
import store from "./store";

Vue.use(VueRouter);

const routes = [
    {
        path: "/",
        name: "home",
        meta: { requiresAuth: true },
        component: () => import("./views/Home")
    },

    // Auth
    {
        path: "/login",
        name: "login",
        meta: { minimizedApp: true },
        component: () => import("./views/auth/Login")
    },
    {
        path: "/register",
        name: "register",
        meta: { minimizedApp: true },
        component: () => import("./views/auth/Register")
    },
    {
        path: "/pwd-forgot",
        name: "pwd-forgot",
        meta: { minimizedApp: true },
        component: () => import("./views/auth/PasswordForgot")
    },
    {
        path: "/pwd-reset",
        name: "pwd-reset",
        meta: { minimizedApp: true },
        component: () => import("./views/auth/PasswordReset")
    },

    // App
    {
        path: "/campaign",
        name: "campaign",
        meta: { requiresAuth: true },
        component: () => import("./views/Campaign")
    },
    {
        path: "/permission",
        name: "permission",
        meta: { requiresAuth: true },
        component: () => import("./views/Permission")
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
    let nextRoute = null
    if (to.matched.some(record => record.meta.requiresAuth) && !window.authUser) {
        nextRoute = {
            path: "/login",
            query: { redirect: to.fullPath }
        }
        // next({
        //     path: "/auth",
        //     query: { redirect: to.fullPath }
        // })
        // window.location.href = `${window.Domain}/login`
    }

    if (to.meta.minimizedApp){
        store.state.minimizedApp = true;
    } else if(store.state.minimizedApp){
        store.state.minimizedApp = false
    }

    nextRoute ? next(nextRoute) : next()
})

export default router;
