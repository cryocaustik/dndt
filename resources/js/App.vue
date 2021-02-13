<template>
    <v-app>
        <NavBar v-if="!minimizedApp" v-on:toggle="toggleNavDrawer = !toggleNavDrawer"/>
        <NavDrawer v-if="!minimizedApp" :toggle="toggleNavDrawer"/>

        <v-main>
            <router-view></router-view>
        </v-main>
    </v-app>
</template>

<script>
import NavBar from "./components/includes/NavBar";
import NavDrawer from "./components/includes/NavDrawer";
import Vuetify from "./plugins/vuetify";

export default {
    name: "App",
    data: () => ({
        toggleNavDrawer: true
    }),
    computed: {
        minimizedApp(){
            return this.$store.state.minimizedApp
        },
    },
    methods: {
        windowErrors(){
            if(!window.errors) return

            window.errors.forEach(err => {
                window.Vue.$vToastify.error(err, "Error");
            })
        }
    },
    components: {
        NavBar,
        NavDrawer
    },
    mounted() {
        this.$store.commit('setAuthUser', window.authUser);
        this.windowErrors();
        // TODO: figure out a way to reroute
        console.log(this.$route)
    }
};
</script>
